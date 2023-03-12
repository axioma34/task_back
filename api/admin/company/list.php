<?php

$method = 'GET';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Company.php';


$db = Database::connect();


$company = new Company($db);
$result = $company->getAll();
$num = $result->rowCount();

if ($num == 0) {
    echo json_encode(['message' => 'No records found']);
    return;
}
$companyArr = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $companyItem = [
        'id' => $id,
        'name' => $name,
        'mail' => $mail,
        'phone' => $phone,
        'address' => $address,
        'website' => $website,
        'users_count' => $users_count,
        'tasks_count' => $tasks_count
    ];

    $companyArr[] = $companyItem;
}
echo json_encode($companyArr);

