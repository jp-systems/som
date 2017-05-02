<?php
// Import credentials for login
require_once("credentials.php");

class Functions {
  // Function to return error
  public static function error($message, $code = null) {
    return [
      "success" => false,
      "message" => $message,
      "code" => $code
    ];
  }
  // Function to return successful data from query
  public static function success($result = null) {
    return [
      "success" => true,
      "result" => $result
    ];
  }
  // Function to create a random ID, used for unique ID in sql tables
  private static function get_random_id() {
    $chars = "0123456789abcdef";
    $r = '';
    for($i=0; $i < 16; ++$i) {
      $r .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $r;
  }
  // Function to check if username is unique
  public static function username_taken($username) {
    $result = Functions::query("SELECT 1 FROM `user` WHERE `username` = ? LIMIT 1", [$username]);
    if ($result["success"]) {
      return Functions::success($result["result"] != null);
    } else {
      return $result;
    }
  }
  // Function to check if email is unique
  public static function email_taken($email) {
    $result = Functions::query("SELECT 1 FROM `user` WHERE `email` = ? LIMIT 1", [$email]);
    if ($result["success"]) {
      return Functions::success($result["result"] != null);
    } else {
      return $result;
    }
  }
  // Function to return user detail
  public static function user_data($sessionID) {
    return Functions::query("SELECT `user`.`avatar`, `user`.`email`, `user`.`username` FROM `user` JOIN `session` ON `session`.`userID` = `user`.`userID` WHERE `session`.`sessionID` = ? LIMIT 1", [$sessionID]);
  }
  // Function to return all questions asked by the user and number of answers
  public static function user_questions($sessionID) {
    // Need the question ID, text
    // Need the module name, ref, code
    // Need the number of replies to question
    return Functions::query("SELECT `question`.`questionID`, `question`.`text`, `module`.`name`, `module`.`ref`, `module`.`code`,
      (SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionID` = `question`.`questionID`) AS 'replies' FROM `question`
      JOIN `module` ON `module`.`moduleID` = `question`.`moduleID`
      WHERE `question`.`userID` = (SELECT `userID` FROM `session` WHERE `sessionID` = ?)",
      [ $sessionID ]
    );
  }
  // Function to return number of positive replies to an answer
  public static function answer_rating($answerID) {
    return Functions::query("SELECT COUNT(*) AS total FROM `rating` WHERE `answerID` = ? AND `positive` = 1", [$answerID]);
  }
  // Function to create a new user
  public static function create_user($username, $pass_hash, $email) {
    $id = Functions::get_random_id();
    if (preg_match('/^[A-Za-z]{1}[A-Za-z0-9\\-]{2,31}$/', $username) === 0) {
      // Invalid username type
      return Functions::error("Invalid username format!");
    }
    $result = Functions::query("INSERT INTO `user` (`userID`, `username`, `password`, `email`) VALUES (?, ?, ?, ?)", [$id, $username, $pass_hash, $email]);
    if ($result["success"]) {
      $sessionID = Functions::create_login_session($id);
      return Functions::success($sessionID);
    } else {
      return Functions::error("Could not create user");
    }
  }
  // Function to verify the user logging in and verifying credentials and setting token
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
  // Function to verify the current user session with login token
  public static function verify_session($login_token) {
    $result = Functions::query("SELECT * FROM `session` WHERE `sessionID` = ? LIMIT 1", [$login_token]);
    if ($result["success"] && $result["result"] !== null) return Functions::success($result["result"]["userID"]);
    else return $result;
  }
  // Function to create a new login session
  public static function create_login_session($userID) {
    $sessionID = Functions::get_random_id();
    Functions::query("INSERT INTO `session` (`sessionID`, `userID`, `expiresOn`) VALUES (?, ?, NOW() + INTERVAL 30 DAY)", [$sessionID, $userID]);
    return $sessionID;
  }
  // Function to remove the login token in the session table
  public static function destroy_session($login_token) {
    return Functions::query("DELETE FROM `session` WHERE `sessionID` = ?", [$login_token]);
  }
  // Main function used to query the database
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
  // Function to get the user's username
  public static function get_username($userID) {
    return Functions::query("SELECT `username` FROM `user` WHERE `userID` = ? LIMIT 1", [$userID]);
  }
  // Function to get all the module details either via its ID code of ref found in the table module 
  public static function get_module($moduleID) {
    return Functions::query("SELECT `module`.`code`, `module`.`moduleID`, `module`.`name`, `module`.`outline`, `module`.`ref`, `module`.`updatedOn`, `user`.`username` FROM `user` JOIN `module` ON `module`.`userID` = `user`.`userID` WHERE `module`.`moduleID` = ? OR `module`.`code` = ? OR `module`.`ref` = ? LIMIT 1", [$moduleID, $moduleID, $moduleID]);
  }
  // Function to find the module table
  public static function search_modules($query) {
    $query = "%" . $query . "%";
    return Functions::query("SELECT `module`.`moduleID`, `module`.`code`, `module`.`name`, `module`.`ref`, (SELECT COUNT(*) FROM `usermodule` WHERE `usermodule`.`moduleID` = `module`.`moduleID`) AS 'followers' FROM `module` WHERE `code` LIKE ? OR `name` LIKE ?", [$query, $query]);
  }
  // Function to get the individual user's modules information
  public static function user_modules($sessionID) {
    return Functions::query("SELECT `module`.`code`, `module`.`name`, `module`.`ref`
    FROM `usermodule`
	    JOIN `module` ON `module`.`moduleID` = `usermodule`.`moduleID`
      JOIN `user` ON `user`.`userID` = `usermodule`.`userID`
      JOIN `session` ON `session`.`userID` = `user`.`userID`
    WHERE `session`.`sessionID` = ?", [$sessionID]);
  }
  // Function to allow a user to 'bookmark' a module
  public static function follow_module($sessionID, $moduleID) {
    $id = Functions::get_random_id();
    return Functions::query("INSERT IGNORE INTO `usermodule` (`userModuleID`, `userID`, `moduleID`) VALUES (?, (SELECT `userID` FROM `session` WHERE sessionID = ?), ?)", [$id, $sessionID, $moduleID]);
  }
  // Function to 'un-bookmark' and stop following a module
  public static function unfollow_module($sessionID, $moduleID) {
    return Functions::query("DELETE FROM `usermodule` WHERE `userID` = (SELECT `userID` FROM `session` WHERE `sessionID` = ?) AND `moduleID` = ?", [$sessionID, $moduleID]);
  }
  // Function to update the wiki for a module and the user who did it
  public static function update_module($sessionID, $moduleID, $moduleOutline) {
    return Functions::query("UPDATE `module` SET `userID` = (SELECT `userID` FROM `session` WHERE `sessionID` = ?), `outline` = ? WHERE `ref` = ? OR `code` = ?", [$sessionID, $moduleOutline, $moduleID, $moduleID]);
  }
  // Function for a user to post a question into database
  public static function post_question($sessionID, $moduleID, $text, $anonymous) {
    $id = Functions::get_random_id();
    return Functions::query("INSERT INTO `question` (`questionID`, `userID`, `moduleID`, `text`, `anonymous`) VALUES (?, (SELECT `userID` FROM `session` WHERE `sessionID` = ?), ?, ?, ?)", [$id, $sessionID, $moduleID, $text, $anonymous ? 1 : 0]);
  }
  // Function to get all the questions asked for each module, the number of replies, and the username if they've set anonymous to false otherwise a blank string
  public static function get_questions($moduleID) {
    return Functions::query("SELECT `question`.`questionID`, `question`.`text`, `question`.`createdOn`, `question`.`updatedOn`, CASE `question`.`anonymous` WHEN 1 THEN '' ELSE `user`.`username` END AS 'user', (SELECT COUNT(*) FROM `answer` WHERE `answer`.`questionID` = `question`.`questionID`) AS 'replies' FROM `question` JOIN `user` ON `user`.`userID` = `question`.`userID` WHERE `moduleID` = ? ORDER BY `question`.`createdOn` DESC", [$moduleID]);
  }
  // Function to get all the individual question information
  public static function get_question($questionID) {
    return Functions::query("SELECT `question`.`text`, `question`.`createdOn`, `question`.`updatedOn` FROM `question` WHERE `questionID` = ? LIMIT 1", [$questionID]);
  }
  // Function to update the question asked
  public static function update_question($sessionID, $questionID, $text) {
    return Functions::query("UPDATE `question` SET `text` = ? WHERE `questionID` = ? AND `userID` = (SELECT `userID` FROM `session` WHERE `sessionID` = ?", [$text, $questionID, $sessionID]);
  }
  // Function to get all the answers' information to a question and the number of positive and negative votes
  public static function get_answers($questionID) {
    return Functions::query("SELECT `answer`.`answerID`, `answer`.`createdOn`, `answer`.`text`,
      CASE `answer`.`anonymous` WHEN 1 THEN '' ELSE (SELECT `user`.`username` FROM `user` WHERE `user`.`userID` = `answer`.`userID`) END AS 'user',
      (SELECT COUNT(*) FROM `rating` WHERE `rating`.`answerID` = `answer`.`answerID` AND `rating`.`positive` = 1) AS `positive`,
      (SELECT COUNT(*) FROM `rating` WHERE `rating`.`answerID` = `answer`.`answerID` AND `rating`.`positive` = 0) AS `negative`
      FROM `answer` WHERE `questionID` = ?",
      [ $questionID ]
    );
  }
  // Function to post a reply to a question, with the option to be either anonymous or show their name
  public static function post_reply($sessionID, $questionID, $text, $anonymous) {
    $id = Functions::get_random_id();
    return Functions::query("INSERT INTO `answer` (`answerID`, `userID`, `questionID`, `text`, `anonymous`) VALUES (?, (SELECT `userID` FROM `session` WHERE `sessionID` = ?), ?, ?, ?)", [$id, $sessionID, $questionID, $text, $anonymous ? 1 : 0]);
  }
  // Function to update the reply text to a question
  public static function update_reply($sessionID, $questionID, $text) {
    return Functions::query("UPDATE `answer` SET `text` = ? WHERE `questionID` = ? AND `userID` = (SELECT `userID` FROM `session` WHERE `sessionID` = ?", [$text, $questionID, $sessionID]);
  }
  // Add a rating to an answer in the rating table
  public static function add_rating($sessionID, $answerID, $positive) {
    $id = Functions::get_random_id();
    return Functions::query("INSERT IGNORE INTO `rating` (`ratingID`, `userID`, `answerID`, `positive`) VALUES (?, (SELECT `userID` FROM `session` WHERE `sessionID` = ?), ?, ?)", [$id, $sessionID, $answerID, $positive]);
  }

  /***** Removed in production, used for adding modules ******/
  // public static function add_module($sessionID, $ref, $code, $name, $outline) {
  //   $id = Functions::get_random_id();
  //   return Functions::query("INSERT INTO `module` (`moduleID`, `userID`, `ref`, `code`, `name`, `outline`) VALUES (?,(SELECT `userID` FROM `session` WHERE sessionID = ?),?,?,?,?)", [$id, $sessionID, $ref, $code, $name, $outline]);
  // }
}

?>
