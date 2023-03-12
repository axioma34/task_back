<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: X-AUTH-TOKEN, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

error_reporting(E_ALL ^ E_NOTICE);

if($_SERVER['REQUEST_METHOD'] == "OPTIONS") {
    die();
}
if ($method && $_SERVER['REQUEST_METHOD'] != $method) {
    http_response_code(404);
    throw new Exception('Method not Found');
}
