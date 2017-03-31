<?php

require_once("functions.php");

$get_request = isset($_GET["request"]) ? $_GET["request"] : null;
$post_request = isset($_POST["request"]) ? $_POST["request"] : null;

function respond($response) {
  echo json_encode($response);
  exit();
}

if ($get_request) {
  // GET REQUEST ACTIVE
  switch($get_request) {
    case "get_username":
      if (!isset($_GET["user_id"])) respond(Functions::error("No user ID provided!"));
      respond(Functions::get_username($_GET["user_id"]));
      break;
  }
  // Invalid request
  respond(Functions::error("Invalid request!"));
} else if ($post_request) {
  // POST RQST ACTIVE
  switch($post_request) {
    case "create_user":
      if (!isset($_POST["username"])) respond(Functions::error("No username provided!"));
      if (!isset($_POST["password"])) respond(Functions::error("No password provided!"));
      //
      $pass_hash = password_hash($_POST["password"], PASSWORD_BCRYPT, ["cost" => 12]);
      //
      respond(Functions::create_user($_POST["username"], $pass_hash));
  }
  // Invalid request
  respond(Functions::error("Invalid request!"));
} else {
  respond(Functions::error("No request!"));
}


?>
