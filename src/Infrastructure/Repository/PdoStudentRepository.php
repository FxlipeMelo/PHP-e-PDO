<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Phone;
use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Domain\Repository\StudentRepository;
use DateTimeImmutable;
use PDO;
use PDOStatement;
use RuntimeException;

class PdoStudentRepository implements StudentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function allStudents(): array
    {
        $statement = $this->connection->query("SELECT * FROM students;");

        return $this->hydrateStudentList($statement);
    }

    public function studentBirthAt(DateTimeImmutable $birthDate): array
    {
        $statement = $this->connection->prepare("SELECT * FROM students WHERE birthDate = :birthDate");
        $statement->bindValue(':birthDate', $birthDate->format('Y-m-d'));
        $statement->execute();

        return $this->hydrateStudentList($statement);
    }

    private function hydrateStudentList(PDOStatement $statement): array
    {
        $studentDataList = $statement->fetchAll();
        $studentLista = [];

        foreach ($studentDataList as $student) {
            $studentLista[] = new Student($student['id'], $student['name'], new DateTimeImmutable($student['birthDate']));
        }

        return $studentLista;
    }

    // private function fillPhonesOf(Student $student): void
    // {
    //     $sqlQuery = "SELECT id, areaCode, number FROM phone WHERE id = :id";
    //     $statement = $this->connection->prepare($sqlQuery);
    //     $statement->bindValue(':id', $student->id(), PDO::PARAM_INT);
    //     $statement->execute();

    //     $phoneDataList = $statement->fetchAll();
    //     foreach ($phoneDataList as $phoneData) {
    //         $phone = new Phone(
    //             $phoneData['id'],
    //             $phoneData['areaCode'],
    //             $phoneData['number']
    //         );

    //         $student->addPhone($phone);
    //     }

    // }

    public function save(Student $student): bool
    {
        if ($student->id() === null) {
            return $this->insert($student);
        }

        return $this->update($student);
    }

    private function insert(Student $student)
    {
        $statement = $this->connection->prepare("INSERT INTO students (name, birthDate) VALUES (:name, :birthDate);");
        if ($statement === false){
            throw new RuntimeException($this->connection->errorInfo()[2]);
        }

        $success = $statement->execute([
            ':name' => $student->name(),
            'birthDate' => $student->birthDate()->format('Y-m-d'),
        ]);

        if ($success) {
            $student->defineId($this->connection->lastInsertId());
        }

        return $success;
    }

    private function update(Student $student): bool
    {
        $statement = $this->connection->prepare("UPDATE studets SET name = :name, birthDate = :birthDate WHERE id = :id;");
        $statement->bindValue(':name', $student->name(), PDO::PARAM_STR);
        $statement->bindValue(':birthDate', $student->birthDate(), PDO::PARAM_STR);
        $statement->bindValue(':id', $student->id(), PDO::PARAM_INT);

        return $statement->execute();
    }

    public function remove(Student $student): bool
    {
        $statement = $this->connection->prepare("DELETE FROM students WHERE id = :id;");
        $statement->bindValue(':id', $student->id(), PDO::PARAM_INT);

        return $statement->execute();
    }

    public function studentWithPhones(): array
    {
        $sqlQuery = "SELECT students.id, students.name, students.birthDate, phone.id AS phoneId, phone.areaCode, phone.number FROM students JOIN phone ON students.id = phone.studentId;";
        $statement = $this->connection->query($sqlQuery);
        $result = $statement->fetchAll();
        $studentList = [];

        foreach ($result as $row) {
            if (!array_key_exists($row['id'], $studentList)) { 
                $studentList[$row['id']] = new Student($row['id'], $row['name'], new DateTimeImmutable($row['birthDate']));  
            }
            $phone = new Phone($row['id'], $row['areaCode'], $row['number']);
            $studentList[$row['id']]->addPhone($phone);
        }

        return $studentList;
    }
}
