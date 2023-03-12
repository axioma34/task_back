<?php
$method = 'POST';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Task.php';
include_once '../../../models/Collaborator.php';


$db = Database::connect();
$task = new Task($db);
$data = json_decode(file_get_contents("php://input"));

$task->setHeadline($data->headline);
$task->setCompanyId($data->company_id);
$data->description && $task->setDescription($data->description);
$task->setDueDate($data->due_date);
$data->soved && $task->setSolved($data->solved);

if (!$task->create()) {
    http_response_code(503);
    echo json_encode(["message" => "Cannot create task."]);
}

$collaborator = new Collaborator($db);
$collaborator->setTaskId($task->getId());
$collaborator->setPersonId($data->person_id);

if (!$collaborator->create()) {
    http_response_code(503);
    echo json_encode(["message" => "Cannot create task."]);
}

http_response_code(201);

echo json_encode(["message" => "Task created."]);
