<?php
require_once __DIR__ . '/../auth/register.php';
require_once __DIR__ . '/../auth/login.php';
require_once __DIR__ . '/../auth/refresh.php';
require_once __DIR__ . '/../auth/logout.php';
require_once __DIR__ . '/../auth/profile.php';
require_once __DIR__ . '/../noteManagement/addNote.php';


header("Content-Type: application/json");

// Get request URI relative to your project
$requestUri = str_replace('/HybridNoteAppBackend/public', '', strtok($_SERVER['REQUEST_URI'], '?')); 
// $requestUri = str_replace(['/HybridNoteAppBackend/public', '/HybridNoteAppBackend'], '', strtok($_SERVER['REQUEST_URI'], '?'));
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestUri) {
    case '/':
        if ($requestMethod === 'GET') {
            http_response_code(200);
            echo json_encode(["message" => "API is healthy and running"]);
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case '/api/register':
        if ($requestMethod === 'POST') {
            register();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case '/api/login':
        if ($requestMethod === 'POST') {
            login();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case '/api/refresh':
        if ($requestMethod === 'POST') {
            refreshToken();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    case '/api/logout':
        if ($requestMethod === 'POST') {
            logout();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Method Not Allowed"]);
        }
        break;

    

        case '/api/notes/add':
            if ($requestMethod === 'POST') {
                addNotes();
            } else {
                http_response_code(405);
                echo json_encode(["message" => "Method Not Allowed"]);
            }
            break;

            // get all notes by user_id
         case '/api/notes/get':
                if ($requestMethod === 'GET') {
                   // to do function
                   getNotes();
                } else {
                    http_response_code(405);
                    echo json_encode(["message" => "Method Not Allowed"]);
                }
                break;


        //   update note by not_id
                case '/api/notes/update':
                    if ($requestMethod === 'PUT') {
                           // to do function
                           updateNote();
                    } else {
                        http_response_code(405);
                        echo json_encode(["message" => "Method Not Allowed"]);
                    }
                    break;


                        //   delete note by not_id
                case '/api//notes/delete':
                    if ($requestMethod === 'DELETE') {
                           // to do function
                           deleteNote();
                    } else {
                        http_response_code(405);
                        echo json_encode(["message" => "Method Not Allowed"]);
                    }
                    break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Endpoint Not Found"]);
        break;
}
?>
