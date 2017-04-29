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
    case "get_module":
      if (!isset($_GET["module_id"])) respond(Functions::error("No module ID provided!"));
      respond(Functions::get_module($_GET["module_id"]));
      break;
    case "search_modules":
      if (!isset($_GET["query"])) respond(Functions::error("No query provided!"));
      respond(Functions::search_modules($_GET["query"]));
      break;
    case "username_taken":
      if (!isset($_GET["username"])) respond(Functions::error("No username provided!"));
      respond(Functions::username_taken($_GET["username"]));
      break;
    case "email_taken":
      if (!isset($_GET["email"])) respond(Functions::error("No email provided!"));
      respond(Functions::email_taken($_GET["email"]));
      break;
    case "user_questions":
      if (!isset($_GET["login_token"])) respond(Functions::error("No token provided!"));
      respond(Functions::user_questions($_GET["login_token"]));
      break;
    case "answer_rating":
      if (!isset($_GET["answer_id"])) respond(Functions::error("No answer id provided!"));
      respond(Functions::answer_rating($_GET["answer_id"]));
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
      if (!isset($_POST["email"])) respond(Functions::error("No email provided"));
      //
      $pass_hash = password_hash($_POST["password"], PASSWORD_BCRYPT, ["cost" => 12]);
      //
      respond(Functions::create_user($_POST["username"], $pass_hash, $_POST["email"]));
      break;
    case "verify_user":
      if (!isset($_POST["username"])) respond(Functions::error("No username provided!"));
      if (!isset($_POST["password"])) respond(Functions::error("No password provided!"));
      respond(Functions::verify_user($_POST["username"], $_POST["password"]));
      break;
    case "verify_session":
      if (!isset($_POST["login_token"])) respond(Functions::error("No token provided!"));
      respond(Functions::verify_session($_POST["login_token"]));
      break;
    case "destroy_session":
      if (!isset($_POST["login_token"])) respond(Functions::error("No token provided!"));
      respond(Functions::destroy_session($_POST["login_token"]));
      break;
    case "user_modules":
      if (!isset($_POST["login_token"])) respond(Functions::error("Invalid token!"));
      respond(Functions::user_modules($_POST["login_token"]));
      break;
    case "user_data":
      if (!isset($_POST["login_token"])) respond(Functions::error("Invalid token!"));
      respond(Functions::user_data($_POST["login_token"]));
      break;
    case "fav_module":
      if (!isset($_POST["login_token"])) respond(Functions::error("Invalid token!"));
      if (!isset($_POST["module_ID"])) respond(Functions::error("No module provided!"));
      respond(Functions::follow_module($_POST["login_token"], $_POST["module_ID"]));
      break;
    case "update_module":
      if (!isset($_POST["login_token"])) respond(Functions::error("Invalid token!"));
      if (!isset($_POST["module_ID"])) respond(Functions::error("No module provided"));
      if (!isset($_POST["module_outline"])) respond(Functions::error("No outline provided"));
      respond(Functions::update_module($_POST["login_token"], $_POST["module_ID"], $_POST["module_outline"]));
      break;
    case "unfollow_module":
      if (!isset($_POST["login_token"])) respond(Functions::error("Invalid token!"));
      if (!isset($_POST["module_ID"])) respond(Functions::error("No module provided"));
      respond(Functions::unfollow_module($_POST["login_token"], $_POST["module_ID"]));
      break;
    case "post_question":
      if (!isset($_POST["login_token"])) respond(Functions::error("Invalid token!"));
      if (!isset($_POST["module_ID"])) respond(Functions::error("No module provided"));
      if (!isset($_POST["text"])) respond(Functions::error("No text provided"));
      if (!isset($_POST["anonymous"])) respond(Functions::error("No anonymous value provided"));
      respond(Functions::post_question($_POST["login_token"], $_POST["module_ID"], $_POST["text"], $_POST["anonymous"]));
      break;
    case "update_question":
      if (!isset($_POST["login_token"])) respond(Functions::error("Invalid token!")); 
      if (!isset($_POST["question_ID"])) respond(Functions::error("No question id provided!"));
      if (!isset($_POST["text"])) respond(Functions::error("No text provided!"));
      respond(Functions::update_question($_POST["login_token"], $_POST["question_ID"], $_POST["text"]));
      break;
    case "post_reply":
      if (!isset($_POST["login_token"])) respond(Functions::error("Invalid token!")); 
      if (!isset($_POST["question_ID"])) respond(Functions::error("No question id provided!"));
      if (!isset($_POST["text"])) respond(Functions::error("No text provided!"));
      if (!isset($_POST["anonymous"])) respond(Functions::error("No anonymous value provided"));
      respond(Functions::post_reply($_POST["login_token"], $_POST["question_ID"], $_POST["text"], $_POST["anonymous"]));
      break;
    case "update_reply":
      if (!isset($_POST["login_token"])) respond(Functions::error("Invalid token!")); 
      if (!isset($_POST["question_ID"])) respond(Functions::error("No question id provided!"));
      if (!isset($_POST["text"])) respond(Functions::error("No text provided!"));
      respond(Functions::update_reply($_POST["login_token"], $_POST["question_ID"], $_POST["text"]));
      break;
    case "add_rating":
      if (!isset($_POST["login_token"])) respond(Functions::error("Invalid token!"));
      if (!isset($_POST["answer_ID"])) respond(Functions::error("No answer id provided!"));
      if (!isset($_POST["positive"])) respond(Functions::error("No value provided!"));
      respond(Functions::add_rating($_POST["login_token"], $_POST["answer_ID"], $_POST["positive"]));
      break;
    
    /*** TO DELETE ****/
    case "add_module":
      if (!isset($_POST["login_token"])) respond(Functions::error("Invalid token!"));
      if(!isset($_POST["mod_ref"])) respond(Functions::error("No ref provided"));
      if(!isset($_POST["mod_code"])) respond(Functions::error("No code provided"));
      if(!isset($_POST["mod_name"])) respond(Functions::error("No name provided"));
      if(!isset($_POST["mod_outline"])) respond(Functions::error("No outline provided"));
      respond(Functions::add_module($_POST["login_token"], $_POST["mod_ref"], $_POST["mod_code"], $_POST["mod_name"], $_POST["mod_outline"]));
      break;
  }
  // Invalid request
  respond(Functions::error("Invalid request!"));
} else {
  respond(Functions::error("No request!"));
}


?>
