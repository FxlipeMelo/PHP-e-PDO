<?php

require_once 'vendor/autoload.php';

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$statement = $pdo->prepare("DELETE FROM students WHERE id = ?");
$statement->bindValue(1, 5, PDO::PARAM_INT);
var_dump($statement->execute());