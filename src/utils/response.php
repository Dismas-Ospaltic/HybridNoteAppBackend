<?php
/**
 * Standardizes JSON responses throughout the app
 *
 * @param int $httpStatus HTTP response code (default: 500)
 * @param string $status "success" or "error" (default: "error")
 * @param string $message Message describing the response (default: "Something went wrong")
 * @param mixed $data Data payload (default: null)
 */
function jsonResponse($httpStatus = 500, $status = "error", $message = "Something went wrong", $data = null) {
    http_response_code($httpStatus);
    echo json_encode([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}
?>
