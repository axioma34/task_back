<?php
$method = 'POST';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Person.php';


$db = Database::connect();


$user = new Person($db);


$data = json_decode(file_get_contents("php://input"));

if (empty($data->name) || empty($data->mail) || empty($data->password) || empty($data->company_id)) {
    http_response_code(400);
    echo json_encode(["message" => "Cannot create person. Data not full!"]);
    return;
}

$user->setName($data->name);
$user->setMail($data->mail);
$user->setPassword(password_hash($data->password, PASSWORD_DEFAULT));
$user->setCompanyId($data->company_id);

$data->gender && $user->setGender($data->gender);
$data->date_of_birth && $user->setDateOfBirth($data->date_of_birth);
$data->position && $user->setPosition($data->position);
$data->status && $user->setStatus($data->status);

if (!$user->create()) {
    http_response_code(503);
    echo json_encode(["message" => "Cannot create user."]);
}

http_response_code(201);

echo json_encode(["message" => "User created."]);

