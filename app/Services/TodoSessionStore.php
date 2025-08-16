<?php

namespace App\Services;

class TodoSessionStore
{
    private int $userId;

    public function __construct(?int $userId)
    {
        $this->userId = $userId ?? 0;
    }

    private function todosKey(): string
    {
        return "todos:{$this->userId}";
    }

    private function nextIdKey(): string
    {
        return "todos_next_id:{$this->userId}";
    }

    public function all(): array
    {
        return session()->get($this->todosKey(), []);
    }

    public function putAll(array $todos): void
    {
        session()->put($this->todosKey(), $todos);
    }

    public function nextId(): int
    {
        $k = $this->nextIdKey();
        $id = (int) session()->get($k, 1);
        session()->put($k, $id + 1);
        return $id;
    }

    public function find(int $id): ?array
    {
        foreach ($this->all() as $t) {
            if ($t['id'] === $id) return $t;
        }
        return null;
    }
}
