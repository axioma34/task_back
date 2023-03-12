<?php
$method = 'GET';

include_once '../../../config/core.php';
include_once '../../../config/Database.php';
include_once '../../../models/Task.php';

$db = Database::connect();

$task = new Task($db);

$result = $task->findByPerson($_GET['id']);
$num = $result->rowCount();
if ($num == 0) {
    echo json_encode(['message' => 'No records found']);
    return;
}

$taskArr = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $taskItem = [
        'id' => $id,
        'headline' => $headline,
        'description' => $description,
        'due_date' => $due_date,
        'collaborators' => $collaborators,
        'solved' => (bool)$solved,
        'company_id' => $company_id
    ];
    $taskArr[] = $taskItem;

}

echo json_encode($taskArr, JSON_UNESCAPED_UNICODE);


