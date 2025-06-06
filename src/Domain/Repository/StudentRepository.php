<?php

namespace Alura\Pdo\Domain\Repository;

use Alura\Pdo\Domain\Model\Student;
use DateTimeImmutable;

interface  StudentRepository {
    public function allStudents(): array;
    public function studentWithPhones(): array;
    public function studentBirthAt(DateTimeImmutable $birthDate): array;
    public function save(Student $student): bool;
    public function remove(Student $student): bool;
}