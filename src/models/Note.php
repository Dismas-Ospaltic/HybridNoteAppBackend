<?php

require_once __DIR__ . '/../../vendor/autoload.php';

class Note {
    public string $id;
    public string $user_id;
    public string $title;
    public string $content;
    public string $noteId;
    public int $timestamp;

    public function __construct($data, $userId) {
        if (!isset($data['title'], $data['content'], $data['timestamp'], $data['noteId'])) {
            throw new Exception("Missing required fields");
        }

        $this->id = isset($data['id']) ? (string) $data['id'] : uniqid('', true);
        $this->user_id = $userId; // Get from authenticated user
        $this->title = trim($data['title']);
        $this->content = trim($data['content']);
        $this->noteId = (string) $data['noteId']; // Use client-provided noteId
        $this->timestamp = (int) $data['timestamp']; // Ensure long format

        // Validate title length
        if (strlen($this->title) < 3 || strlen($this->title) > 100) {
            throw new Exception("Title must be between 3 and 100 characters.");
        }

        // Validate timestamp (must be a valid number)
        if (!is_numeric($this->timestamp) || $this->timestamp <= 0) {
            throw new Exception("Invalid timestamp format.");
        }
    }

    public function toArray(): array {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "title" => $this->title,
            "content" => $this->content,
            "noteId" => $this->noteId,
            "timestamp" => $this->timestamp
        ];
    }
}

?>
