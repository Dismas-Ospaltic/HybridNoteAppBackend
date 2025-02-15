<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use MongoDB\Client;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mongoClient = new Client($_ENV['MONGO_URI']);
$mongoDB = $mongoClient->selectDatabase($_ENV['MONGO_DB_NAME']);
$notesCollection = $mongoDB->selectCollection("notes");

?>
