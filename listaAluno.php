<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$statement = $pdo->query('SELECT * FROM students;');
$studentData = $statement->fetchAll(PDO::FETCH_ASSOC);
$studentList = [];

foreach($studentData as $student){
    $studentList[] = new Student($student['id'], $student['name'], new DateTimeImmutable($student['birthDate']));
}

var_dump($studentList);