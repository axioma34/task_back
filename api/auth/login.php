<?php
$method = 'POST';

include_once '../../config/core.php';
include_once '../../config/Database.php';
include_once '../../models/Person.php';

const ADMIN_LOGIN = 'admin@admin.admin';
const ADMIN_PASS = 'adminpassword';

$db = Database::connect();


$user = new Person($db);

$data = json_decode(file_get_contents("php://input"));

if (empty($data->login) || empty($data->password)) {
    http_response_code(400);
    echo json_encode(["message" => "Username and pass fields required."]);
    return;
}

if (strtolower($data->login) == ADMIN_LOGIN && strtolower($data->password) == ADMIN_PASS) {
    http_response_code(200);
    echo json_encode([
        'name' => 'admin',
        'mail' => ADMIN_LOGIN,
        'token' => 'adminToken'
    ]);
    return;
}

$user->setMail($data->login);

try {
    $user->findOneByEmail();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["message" => "Server error."]);
    return;
}

if (!$user->getId()) {
    http_response_code(404);
    echo json_encode(["message" => "User not found! Login incorrect."]);
    return;
}

if (!password_verify($data->password, $user->getPassword())) {
    http_response_code(404);
    echo json_encode(["message" => "Password incorrect."]);
    return;
}

$userArr = [
    'id' => $user->getId(),
    'name' => $user->getName(),
    'gender' => $user->getGender(),
    'date_of_birth' => $user->getDateOfBirth(),
    'company_id' => $user->getCompanyId(),
    'mail' => $user->getMail(),
    'position' => $user->getStatus(),
    'token' => 'userToken',
    'company_name' => $user->getCompanyName()
];

http_response_code(200);

echo json_encode($userArr);
