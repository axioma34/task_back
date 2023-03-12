<?php
$method = 'POST';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Person.php';


$db = Database::connect();

$user = new Person($db);
$data = json_decode(file_get_contents("php://input"));

if (empty($_GET['id']) || empty($data->name) || empty($data->mail) || empty($data->company_id)) {
    http_response_code(400);
    echo json_encode(["message" => "Cannot create person. Data not full!"]);
    return;
}

$user->setId($_GET['id']);
$user->setName($data->name);
$user->setMail($data->mail);
$user->setCompanyId($data->company_id);
$data->gender && $user->setGender($data->gender);
$data->date_of_birth && $user->setDateOfBirth($data->date_of_birth);
$data->position && $user->setPosition($data->position);
$data->stattus && $user->setStatus((int)$data->status);

$data->password && $user->setPassword(password_hash($data->password, PASSWORD_DEFAULT));


if ($user->update()) {
    echo json_encode(array('message' => 'User updated'));
} else {
    echo json_encode(array('message' => 'Cannot update user'));
}
