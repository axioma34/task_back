<?php
$method = 'GET';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Person.php';


$db = Database::connect();

$user = new Person($db);
$result = $user->getAll();

if (!$result->rowCount()) {
    echo json_encode(['message' => 'No records found']);
}


$usersArr = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

    extract($row);
    $userItem = [
        'id' => $id,
        'name' => $name,
        'mail' => $mail,
        'gender' => $gender,
        'date_of_birth' => $date_of_birth,
        'company_name' => $company_name,
        'company_id' => $company_id,
        'position' => $position,
        'tasks_count' => $tasks_count,
        'status' => (bool)$status

    ];

    $usersArr[] = $userItem;

}
echo json_encode($usersArr);
