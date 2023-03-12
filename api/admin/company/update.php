<?php

$method = 'POST';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Company.php';


$db = Database::connect();


$company = new Company($db);

$data = json_decode(file_get_contents("php://input"));

$company->setId($_GET['id']);

$data->name && $company->setName($data->name);
$data->mail && $company->setMail($data->mail);
$data->address && $company->setAddress($data->address);
$data->phone && $company->setPhone($data->phone);
$data->website && $company->setWebsite($data->website);


if ($company->update()) {
    echo json_encode(array('message' => 'success'));
} else {
    echo json_encode(array('message' => 'Cannot update company'));
}


