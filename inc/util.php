<?php
require_once __DIR__ . "../../api/model/Response.php";
$response = new Response();

/**
 * Send response
 * @param bool success
 * @param int status code
 * @param array message
 * @param array data
 * @param bool cache
 * @param int cache time in seconds
 * @param int total data length
 */
function send_response(
  bool $success,
  int $status_code,
  array $messages = [],
  array $data = [],
  bool $cache = false,
  int $cache_time = 30,
  int $total_data_length = null
): void {

  global $response;
  
  $response
    ->setSuccess($success)
    ->setStatusCode($status_code)
    ->setMessage($messages)
    ->setData($data)
    ->setCache($cache)
    ->setCacheTime($cache_time)
    ->setTotal($total_data_length)
    ->send();
  exit();
}

function handle_content_type_json(): void
{
  if (
    !isset($_SERVER["CONTENT_TYPE"]) ||
    $_SERVER["CONTENT_TYPE"] != "application/json"
  ) {
    send_response(false, 400, ["content type must be application/json"]);
  }
}

function handle_content_type_multipart(): void
{
  if (
    !isset($_SERVER["CONTENT_TYPE"]) ||
    !preg_match(
      "/multipart\/form-data; ?boundary=.+/i",
      $_SERVER["CONTENT_TYPE"]
    )
  ) {
    send_response(false, 400, [
      "content type must be multipart/form-data;boundary=something",
    ]);
  }
}

function get_json_data(): stdClass
{
  $rawPostData = file_get_contents("php://input");
  if (!($postData = json_decode($rawPostData))) {
    send_response(false, 400, ["all data must be valid json"]);
  }
  return $postData;
}

function sanitize_phone_number(string $number): string
{
  return preg_replace("/[^0-9+-]/", "", $number);
}