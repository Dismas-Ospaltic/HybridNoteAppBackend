<?php
require_once '../config/database.php';
require_once __DIR__. '/../models/User.php';
require_once __DIR__. '/../utils/response.php'; // ✅ Include the helper

header("Content-Type: application/json");

function register() {
    global $mysqli;

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['email']) || !isset($data['password'])) {
        jsonResponse(400, "error", "Email and password are required");
    }

    $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
    if (!$email) {
        jsonResponse(400, "error", "Invalid email format");
    }

    $password = password_hash($data['password'], PASSWORD_BCRYPT);
    $user = new User($mysqli);

    if ($user->createUser($email, $password)) {
        jsonResponse(201, "success", "User registered successfully", [
            "email" => $email
        ]);
    } else {
        jsonResponse(500, "error", "Registration failed");
    }
}
?>
