<?php
$method = 'DELETE';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Task.php';

$db = Database::connect();

$task = new Task($db);
$task->setId($_GET['id']);

if ($task->delete()) {
    echo json_encode(array('message' => 'Task removed!'));
} else {
    echo json_encode(array('message' => 'Task cannot be removed!'));
}
