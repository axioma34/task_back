<?php
$method = 'POST';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Task.php';

$db = Database::connect();
$data = json_decode(file_get_contents("php://input"));

$task = new Task($db);

$task->setId($_GET['id']);
$task->setHeadline($data->headline);
$data->description && $task->setDescription($data->description);
$task->setDueDate($data->due_date);
$data->solved && $task->setSolved((int)$data->solved);

if ($task->update()) {
    http_response_code(200);
    echo json_encode(array('message' => 'Task updated'));
} else {
    http_response_code(400);
    echo json_encode(array('message' => 'Cannot update task'));
}
