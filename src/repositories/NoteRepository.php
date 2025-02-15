<?php

require_once __DIR__ . '/../../config/mongo_db.php';
require_once __DIR__ . '/../models/Note.php';

class NoteRepository {
    private $collection;

    public function __construct($mongoDB) {
        $this->collection = $mongoDB->selectCollection("notes");
    }

    // ✅ Add Notes
    public function addNotes(array $notes, $userId): bool {
        $formattedNotes = [];
        foreach ($notes as $noteData) {
            try {
                $note = new Note($noteData, $userId);
                $formattedNotes[] = $note->toArray();
            } catch (Exception $e) {
                return false;
            }
        }

        try {
            $this->collection->insertMany($formattedNotes);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    // ✅ Get Notes by User ID
    public function getNotesByUser($userId) {
        return $this->collection->find(["user_id" => $userId])->toArray();
    }

    // ✅ Get a Single Note
    public function getNoteById($noteId) {
        return $this->collection->findOne(["noteId" => $noteId]);
    }

    // ✅ Update Note
    public function updateNote($noteId, $updatedData) {
        return $this->collection->updateOne(
            ["noteId" => $noteId],
            ['$set' => $updatedData]
        );
    }

    // ✅ Delete Note
    public function deleteNote($noteId) {
        return $this->collection->deleteOne(["noteId" => $noteId]);
    }
    
    public function noteExists(string $noteId, string $userId): bool {
        $existingNote = $this->collection->findOne([
            "user_id" => $userId,
            "noteId" => $noteId
        ]);
        return $existingNote !== null;
    }
}




?>
