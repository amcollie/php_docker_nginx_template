<?php

namespace App\Gateways;

use App\Database;
use PDO;

class TaskGateway
{
    private PDO $conn;

    public function __construct(Database $database)
    {
        $this->conn = $database->getDatabase();
    }

    public function getTasks(): array
    {
        $sql = 'SELECT * FROM tasks ORDER BY name';
        $stmt = $this->conn->query($sql);

        $data = [];
        while ($task = $stmt->fetch()) {
            $data[] = [
                'id' => $task['id'],
                'name' => $task['name'],
                'priority' => $task['priority'],
                'is_completed' => (bool) $task['is_completed']
            ];
        }
        $stmt->closeCursor();

        return $data;
    }

    public function getTask(string $id): array
    {
        $sql = 'SELECT * FROM tasks WHERE id = :id';
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetch();
        if ($data) {
            $data['is_completed'] = (bool) $data['is_completed'];
        } else {
            $data = [];
        }

        return $data;
    }
}