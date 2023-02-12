<?php
require_once __DIR__ . "/../config.php";

if (APP_ENVIRONMENT === "development") {
  # Silent warning in api dev via postman, etc
  @header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}

header("Access-Control-Allow-Methods: 'GET, POST, PATCH, DELETE, OPTIONS'");
header(
  "Access-Control-Allow-Headers: 'Access-Control-Allow-Headers, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers'"
);
header("Access-Control-Allow-Credentials: true");

require_once __DIR__ . "/../inc/Request.php";
require_once __DIR__ . "/../inc/util.php";

# Handle request
$request = new Request($_SERVER);
$request->OPTIONS();

$url = $_SERVER["REQUEST_URI"];
$parsedURL = parse_url($url);
$path = urldecode(substr($parsedURL["path"], 5));

if (strlen(trim($path)) === 0) {
  send_response(false, 404, ["endpoint not found"]);
}

/**
 * Get last part of an url to handle specific task
 */
preg_match('/[A-z0-9.]+$/i', $path, $matches);
@$specific_part = $matches[0];

try {
  switch ($path) {
    case "login":
        $request->POST("Auth", "login");
        send_response(false, 405, ["method not allowed"]);
      break;
        
    case "logout":
          $request->GET("Auth", "logout");
          send_response(false, 405, ["method not allowed"]);
      break;
  }

} catch (PDOException $e) {

  if (SHOW_PDO_ERROR === true) {
    send_response(false, 500, [$e->getMessage()]);
  } 
  
  send_response(false, 500, [
    "something went wrong, please try again later!",
  ]);
}

send_response(false, 404, ["endpoint not found"]);