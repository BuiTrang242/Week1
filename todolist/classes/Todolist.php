<?php
class Todolist
{
    public array $tasks;

    public $database;

    public function __construct(Database $todo_service)
    {
        $this->tasks = $todo_service->getAll();
    }
    public function refresh(Database $todo_service)
    {
        $this->tasks = $todo_service->getAll();
        return $this->tasks;
    }
    public function search(Database $todo_service, string $key)
    {
        $tasks = $this->refresh($todo_service);
        $result = [];
        foreach ($tasks as $task) {
            if (strpos($task['title'], $key) !== false) {
                $result[] = $task;
            }
        }
        return $result;
    }

    public function filter(Database $todo_service, string $filter)
    {
        $tasks = $this->refresh($todo_service);
        $result = [];
        foreach ($tasks as $task) {
            if ($task['level'] == $filter) {
                $result[] = $task;
            }
        }
        return $result;
    }
}