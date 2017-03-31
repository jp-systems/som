<?php

require_once("credentials.php");

class Functions {

  public static function error($message) {
    return [
      "success" => false,
      "message" => $message
    ];
  }

  public static function success($result) {
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

  public static function create_user($username, $pass_hash) {
    $id = Functions::get_random_id();
    return Functions::query("INSERT INTO `user` (`userID`, `username`, `password`, `email`) VALUES (?, ?, ?, 'bob@bobmail.com')", [$id, $username, $pass_hash]);
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
      if (strpos($query, "SELECT") !== false) $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
      $stmt = null;

    } catch (PDOException $e) {
      return Functions::error("An error occured.");
    } finally {
      $db = null;
    }
    return Functions::success($result);
  }

  public static function get_username($userID) {
    return Functions::query("SELECT `username` FROM `user` WHERE `userID` = ?", [$userID]);
  }
}

?>