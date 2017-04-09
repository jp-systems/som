<?php

require_once("credentials.php");

class Functions {

  public static function error($message, $code = null) {
    return [
      "success" => false,
      "message" => $message,
      "code" => $code
    ];
  }

  public static function success($result = null) {
    return [
      "success" => true,
      "result" => $result
    ];
  }

  private static function get_random_id() {
    $chars = "0123456789abcdef";
    $r = '';
    for($i=0; $i < 16; ++$i) {
      $r .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $r;
  }

  public static function create_user($username, $pass_hash, $email) {
    $id = Functions::get_random_id();
    return Functions::query("INSERT INTO `user` (`userID`, `username`, `password`, `email`) VALUES (?, ?, ?, ?)", [$id, $username, $pass_hash, $email]);
  }

  public static function verify_user($username, $password) {
    $result = Functions::query("SELECT `userID`, `password` FROM `user` WHERE `username` = ? LIMIT 1", [$username]);
    if (!$result["success"] || sizeof($result["result"]) == 0) return Functions::error("Incorrect username/password!");
    $user = $result["result"];
    
    if (password_verify($password, $user["password"])) {
      // Create a new login session
      $sessionID = Functions::create_login_session($user["userID"]);
      // Return the login session id
      return Functions::success($sessionID);
    } else {
      return Functions::error("Incorrect username/password!");
    }
  }

  public static function verify_session($login_token) {
    $result = Functions::query("SELECT * FROM `session` WHERE `sessionID` = ? LIMIT 1", [$login_token]);
    if ($result["success"] && $result["result"] !== null) return Functions::success($result["result"]["userID"]);
    else return $result;
  }

  public static function create_login_session($userID) {
    $sessionID = Functions::get_random_id();
    Functions::query("INSERT INTO `session` (`sessionID`, `userID`, `expiresOn`) VALUES (?, ?, NOW() + INTERVAL 30 DAY)", [$sessionID, $userID]);
    return $sessionID;
  }

  public static function destroy_session($login_token) {
    return Functions::query("DELETE FROM `session` WHERE `sessionID` = ?", [$login_token]);
  }
  
  private static function query($query, $params = null) {
    $host = Credentials::$host;
    $dbname = Credentials::$dbname;
    $username = Credentials::$username;
    $password = Credentials::$password;

    $result = null;

    try {
      $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

      $stmt = $db->prepare($query);
      $stmt->execute($params);
      if (strpos($query, "SELECT") !== false) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (strpos($query, "LIMIT 1") !== false) $result = sizeof($result) > 0 ? $result[0] : [];
      }
      
      $stmt = null;

    } catch (PDOException $e) {
      return Functions::error($e->getMessage());
      // return Functions::error("An error occurred.");
    } finally {
      $db = null;
    }
    return Functions::success($result);
  }

  public static function get_username($userID) {
    return Functions::query("SELECT `username` FROM `user` WHERE `userID` = ? LIMIT 1", [$userID]);
  }

  public static function get_module($moduleID) {
    return Functions::query("SELECT * FROM `module` WHERE `moduleID` = ? OR `ref` = ? LIMIT 1", [$moduleID, $moduleID]);
  }

  public static function search_modules($query) {
    $query = "%" . $query . "%";
    return Functions::query("SELECT `moduleID`, `code`, `name`, `ref` FROM `module` WHERE `code` LIKE ? OR `name` LIKE ?", [$query, $query]);
  }

  public static function user_modules($sessionID) {
    return Functions::query("SELECT `module`.`code`, `module`.`name`, `module`.`ref`
    FROM `userModule`
	    JOIN `module` ON `module`.`moduleID` = `userModule`.`moduleID`
      JOIN `user` ON `user`.`userID` = `userModule`.`userID`
      JOIN `session` ON `session`.`userID` = `user`.`userID`
    WHERE `session`.`sessionID` = ?", [$sessionID]);
  }

  /***** TO DELETE ******/
  public static function add_module($ref, $code, $name, $outline) {
    $id = Functions::get_random_id();
    return Functions::query("INSERT INTO `module` (`moduleID`, `ref`, `code`, `name`, `outline`) VALUES (?,?,?,?,?)", [$id, $ref, $code, $name, $outline]);
  }
}

?>
