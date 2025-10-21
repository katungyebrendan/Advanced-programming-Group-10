<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\ProgramEntity;
use App\Domain\Repositories\IProgramRepository;
use App\Models\Program;

class EloquentProgramRepository implements IProgramRepository
{
    public function findById(int $id): ?ProgramEntity
    {
        $model = Program::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByName(string $name): ?ProgramEntity
    {
        $m = Program::where('name', $name)->first();
        return $m ? $this->toEntity($m) : null;
    }

    public function exists(string $name): bool
    {
        return Program::where('name', $name)->exists();
    }

    public function existsCaseInsensitive(string $name): bool
    {
        return Program::whereRaw('lower(name) = ?', [strtolower($name)])->exists();
    }

    public function save(ProgramEntity $program): ProgramEntity
    {
        $model = $program->id ? Program::find($program->id) : new Program();
        $model->name = $program->name;
        $model->description = $program->description;
        $model->national_alignment = $program->nationalAlignment;
        $model->focus_areas = $program->focusAreas;
        $model->phases = $program->phases;
        $model->save();

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        Program::destroy($id);
    }

    public function hasProjects(int $programId): bool
    {
        $p = Program::find($programId);
        return $p ? $p->projects()->exists() : false;
    }

    public function getAll(): array
    {
        return Program::all()->map(fn ($m) => $this->toEntity($m))->all();
    }

    private function toEntity(Program $m): ProgramEntity
    {
        // Normalize JSON-cast fields to arrays
        $focus = $m->focus_areas;
        if (!is_array($focus)) {
            if (is_string($focus) && $focus !== '') {
                $decoded = json_decode($focus, true);
                $focus = is_array($decoded) ? $decoded : [];
            } else {
                $focus = [];
            }
        }

        $phases = $m->phases;
        if (!is_array($phases)) {
            if (is_string($phases) && $phases !== '') {
                $decoded = json_decode($phases, true);
                $phases = is_array($decoded) ? $decoded : [];
            } else {
                $phases = [];
            }
        }

        return new ProgramEntity(
            id: $m->program_id,
            name: $m->name,
            description: $m->description,
            nationalAlignment: $m->national_alignment,
            focusAreas: $focus,
            phases: $phases,
        );
    }
}
