<?php

require_once __DIR__ . '/../../config/mongo_db.php';
require_once __DIR__ . '/../repositories/NoteRepository.php';
require_once __DIR__ . '/../middleware/auth_middleware.php';

header("Content-Type: application/json");

$noteRepo = new NoteRepository($mongoDB);

// function addNotes() {
//     $user = authenticate();
//     if (!$user) {
//         http_response_code(401);
//         echo json_encode(["message" => "Unauthorized"]);
//         exit();
//     }

//     $data = json_decode(file_get_contents("php://input"), true);
//     if (!isset($data['note_data']) || !is_array($data['note_data'])) {
//         http_response_code(400);
//         echo json_encode(["message" => "Invalid note data format"]);
//         exit();
//     }

//     global $noteRepo;
//     if ($noteRepo->addNotes($data['note_data'], $user['user_id'])) {
//         echo json_encode(["message" => "Notes added successfully"]);
//     } else {
//         http_response_code(500);
//         echo json_encode(["message" => "Error inserting notes"]);
//     }
// }

function addNotes() {
    $user = authenticate();
    if (!$user) {
        http_response_code(401);
        echo json_encode(["message" => "Unauthorized"]);
        exit();
    }

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['note_data']) || !is_array($data['note_data'])) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid note data format"]);
        exit();
    }

    global $noteRepo;
    
    $newNotes = [];
    $updatedNotes = 0;

    foreach ($data['note_data'] as $note) {
        $existingNote = $noteRepo->getNoteById($note['noteId']);

        if ($existingNote) {
            // ✅ Update the existing note's content and timestamp
            $updateData = [
                "content" => $note['content'],
                "title" => $note['title'],
                "timestamp" => $note['timestamp']
            ];
            $noteRepo->updateNote($note['noteId'], $updateData);
            $updatedNotes++;
        } else {
            // ✅ Add new note if it does not exist
            $newNotes[] = $note;
        }
    }

    // Insert only new notes
    if (!empty($newNotes)) {
        $noteRepo->addNotes($newNotes, $user['user_id']);
    }

    // Return response
    if (empty($newNotes) && $updatedNotes === 0) {
        http_response_code(409);
        echo json_encode(["message" => "No changes made (notes already up to date)"]);
    } else {
        echo json_encode([
            "message" => "Operation successful",
            "notes_added" => count($newNotes),
            "notes_updated" => $updatedNotes
        ]);
    }
}


function getNotes() {
    $user = authenticate();
    if (!$user) {
        http_response_code(401);
        echo json_encode(["message" => "Unauthorized"]);
        exit();
    }

    global $noteRepo;
    $notes = $noteRepo->getNotesByUser($user['user_id']);

    echo json_encode(["notes" => $notes]);
}

function updateNote() {
    $user = authenticate();
    if (!$user) {
        http_response_code(401);
        echo json_encode(["message" => "Unauthorized"]);
        exit();
    }

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['noteId'], $data['update_data'])) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid update data"]);
        exit();
    }

    global $noteRepo;
    $result = $noteRepo->updateNote($data['noteId'], $data['update_data']);

    if ($result->getModifiedCount() > 0) {
        echo json_encode(["message" => "Note updated successfully"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "No changes made"]);
    }
}

function deleteNote() {
    $user = authenticate();
    if (!$user) {
        http_response_code(401);
        echo json_encode(["message" => "Unauthorized"]);
        exit();
    }

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['noteId'])) {
        http_response_code(400);
        echo json_encode(["message" => "Invalid noteId"]);
        exit();
    }

    global $noteRepo;
    $result = $noteRepo->deleteNote($data['noteId']);

    if ($result->getDeletedCount() > 0) {
        echo json_encode(["message" => "Note deleted successfully"]);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Note not found"]);
    }
}

?>
