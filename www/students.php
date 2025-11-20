<?php


class Student
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function add(string $name): void
    {
        $stmt = $this->pdo->prepare("INSERT INTO students (name) VALUES (?)");
        $stmt->execute([$name]);
    }

    public function all(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM students ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}