<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;

require_once 'vendor/autoload.php';

$pdo = ConnectionCreator::createConnection();

$students = new Student(
    2,
    "Eduardo",
    new DateTimeImmutable('2000-02-13')
);

$sqlInsert = "INSERT INTO students(name, birthDate) VALUES (:name, :birthDate);";
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(':name', $students->name());
$statement->bindValue(':birthDate', $students->birthDate()->format("Y-m-d"));

if ($statement->execute()){
    echo "Aluno incluido";
}