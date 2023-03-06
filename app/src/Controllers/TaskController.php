<?php

namespace App\Controllers;
use App\Gateways\TaskGateway;

class TaskController
{
    public function __construct(private TaskGateway $gateway)
    {
    }
    public function processRequest(string $method, ?string $id): void
    {
        if (is_null($id)) {
            if ($method === 'GET') {
                echo json_encode($this->gateway->getTasks());
            } else if ($method === 'POST') {
                echo 'create';
            } else {
                $this->respondMethodNotAllowed('GET, POST');
            }
        } else {
            $task = $this->gateway->getTask($id);
            if (!$task) {
                $this->respondNotFound($id);
                return;
            }
            switch ($method) {
                case 'GET':
                    echo json_encode($task);
                    break;
                case 'PATCH':
                    echo "update $id";
                    break;
                case 'DELETE':
                    echo "delete $id";
                    break;
                default:
                    $this->respondMethodNotAllowed('GET, PATCH, DELETE');
            }
        }
    }

    private function respondMethodNotAllowed(string $allowedMethods): void
    {
        http_response_code(405);
        header("Allow: $allowedMethods");
    }

    private function respondNotFound(string $id): void
    {
        http_response_code(404);
        echo json_encode([
            'message' => "Task with ID $id was not found"
        ]);
    }
}