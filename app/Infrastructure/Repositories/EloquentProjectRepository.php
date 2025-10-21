<?php
namespace App\Infrastructure\Repositories;

use App\Domain\Entities\ProjectEntity;
use App\Domain\Repositories\IProjectRepository;
use App\Models\Project;

class EloquentProjectRepository implements IProjectRepository
{
    public function findById(int $id): ?ProjectEntity
    {
        $model = Project::find($id);
        if (!$model) return null;
        return new ProjectEntity(
            $model->project_id,
            $model->program_id,
            $model->facility_id,
            $model->title,
            $model->nature_of_project,
            $model->description,
            $model->innovation_focus,
            $model->prototype_stage,
            $model->testing_requirements,
            $model->commercialization_plan,
            $model->status ?? 'Planning'
        );
    }

    public function existsByTitleInProgram(string $title, int $programId): bool
    {
        return Project::where('title', $title)->where('program_id', $programId)->exists();
    }

    public function save(ProjectEntity $project): ProjectEntity
    {
        $model = $project->id ? Project::find($project->id) : new Project();
        $model->program_id = $project->programId;
        $model->facility_id = $project->facilityId;
        $model->title = $project->title;
        $model->nature_of_project = $project->natureOfProject;
        $model->description = $project->description;
        $model->innovation_focus = $project->innovationFocus;
        $model->prototype_stage = $project->prototypeStage;
        $model->testing_requirements = $project->testingRequirements;
        $model->commercialization_plan = $project->commercializationPlan;
        $model->status = $project->status;
        $model->save();
        return new ProjectEntity(
            $model->project_id,
            $model->program_id,
            $model->facility_id,
            $model->title,
            $model->nature_of_project,
            $model->description,
            $model->innovation_focus,
            $model->prototype_stage,
            $model->testing_requirements,
            $model->commercialization_plan,
            $model->status ?? 'Planning'
        );
    }

    public function delete(int $id): void
    {
        Project::destroy($id);
    }

    public function hasTeamMembers(int $projectId): bool
    {
        $p = Project::find($projectId);
        return $p ? $p->participants()->exists() : false;
    }

    public function hasOutcomes(int $projectId): bool
    {
        $p = Project::find($projectId);
        return $p ? $p->outcomes()->exists() : false;
    }

    public function getAll(): array
    {
        return Project::all()->map(function ($m) {
            return new ProjectEntity(
                $m->project_id,
                $m->program_id,
                $m->facility_id,
                $m->title,
                $m->nature_of_project,
                $m->description,
                $m->innovation_focus,
                $m->prototype_stage,
                $m->testing_requirements,
                $m->commercialization_plan,
                $m->status ?? 'Planning'
            );
        })->all();
    }
}
