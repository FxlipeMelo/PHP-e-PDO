<?php

require 'vendor/autoload.php';

use Alura\Pdo\Domain\Repository\StudentRepository;

var_dump(interface_exists(StudentRepository::class));