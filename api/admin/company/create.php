<?php

$method = 'POST';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Company.php';


$db = Database::connect();

$company = new Company($db);


$data = json_decode(file_get_contents("php://input"));

if (empty($data->name)) {
    http_response_code(400);
    echo json_encode(["message" => "Cannot create company. Data not full!"]);
    return;
}


$company->setName($data->name);
$data->mail && $company->setMail($data->mail);
$data->address && $company->setAddress($data->address);
$data->phone && $company->setPhone($data->phone);
$data->website && $company->setWebsite($data->website);


if (!$company->create()) {
    http_response_code(503);
    echo json_encode(["message" => "Cannot create company."]);
}

http_response_code(201);

echo json_encode(["message" => "Company created.", 'id' => $company->getId()]);

