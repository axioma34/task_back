<?php
$method = 'DELETE';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Person.php';


$db = Database::connect();

$user = new Person($db);

$user->setId($_GET['id']);

if ($user->delete()) {
    echo json_encode(['message' => 'User deleted']);
    return;
}

echo json_encode(['message' => 'Cannot create user']);
