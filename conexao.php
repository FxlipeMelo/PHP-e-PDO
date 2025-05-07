<?php


$caminhoBanco = __DIR__ . '/banco.sqlite';
$pdo = new PDO('sqlite:' . $caminhoBanco);

echo 'conectei';

$pdo->exec("INSERT INTO phone (areaCode, number, studentId) VALUES ('17', '999999999', 1), ('17', '111111111', 1);");

// $pdo->exec('CREATE TABLE students (id INTERGER PRIMARY KEY, name TEXT, birthDate TEXT);');

// $createTable = ("CREATE TABLE IF NOT EXISTS phone(id INTEGER PRIMARY KEY, areaCode TEXT, number TEXT, studentId INTEGER, FOREIGN KEY(studentId) REFERENCES student(id));");

// $pdo->exec($createTable);