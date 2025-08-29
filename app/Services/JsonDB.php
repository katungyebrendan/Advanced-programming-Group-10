<?php

namespace App\Services;

class JsonDB
{
    protected string $path;

    public function __construct(string $filename)
    {
        $this->path = storage_path("json/{$filename}.json");
    }

    protected function load(): array
    {
        if (!file_exists($this->path)) {
            return [];
        }
        return json_decode(file_get_contents($this->path), true) ?? [];
    }

    protected function save(array $data): void
    {
        file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function all(): array
    {
        return $this->load();
    }

    public function find($id): ?array
    {
        return collect($this->load())->firstWhere('facility_id', $id);
    }

    public function create(array $record): array
    {
        $data = $this->load();
        $record['facility_id'] = count($data) + 1; // auto-increment
        $data[] = $record;
        $this->save($data);
        return $record;
    }

    public function update($id, array $record): ?array
    {
        $data = $this->load();
        foreach ($data as &$row) {
            if ($row['facility_id'] == $id) {
                $row = array_merge($row, $record);
                $this->save($data);
                return $row;
            }
        }
        return null;
    }

    public function delete($id): bool
    {
        $data = $this->load();
        $filtered = array_filter($data, fn ($row) => $row['facility_id'] != $id);
        if (count($data) === count($filtered)) {
            return false;
        }
        $this->save(array_values($filtered));
        return true;
    }
}
