<?php

$method = 'DELETE';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Company.php';


$db = Database::connect();


$company = new Company($db);

$company->setId($_GET['id']);

if ($company->delete()) {
    http_response_code(200);
    echo json_encode(['message' => 'Company deleted']);
    return;
}

http_response_code(404);
echo json_encode(['message' => 'Cannot delete company']);
