<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionCreator;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require_once 'vendor/autoload.php';

$connection = ConnectionCreator::createConnection();
$studentRepository = new PdoStudentRepository($connection);

try {
    $connection->beginTransaction();
    $aStudent = new Student(
        null,
        'Pedro Henrique',
        new DateTimeImmutable('1980-05-01'),
    );

    $studentRepository->save($aStudent);

    $anotherStudent = new Student(
        null,
        'Liminha',
        new DateTimeImmutable('1995-12-25'),
    );

    $studentRepository->save($anotherStudent);

    $connection->commit();

} catch (PDOException $e) {
    echo $e->getMessage();
    $connection->rollBack();
}
