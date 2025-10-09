<?php

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\ProjectEntity;

class ProjectEntityTest extends TestCase
{
    /** @test */
    public function project_with_valid_data_passes_validation()
    {
        // Arrange
        $project = new ProjectEntity(
            programId: 1,
            facilityId: 2,
            title: 'Smart Irrigation System'
        );

        // Act
        $errors = $project->isValid();

        // Assert
        $this->assertEmpty($errors);
    }

    /** @test */
    public function project_with_missing_program_id_fails_validation()
    {
        // Arrange
        $project = new ProjectEntity(
            programId: 0, // Invalid program ID
            facilityId: 2,
            title: 'Smart Irrigation System'
        );

        // Act
        $errors = $project->isValid();

        // Assert
        $this->assertContains('Project.ProgramId is required.', $errors);
    }

    /** @test */
    public function project_with_missing_facility_id_fails_validation()
    {
        // Arrange
        $project = new ProjectEntity(
            programId: 1,
            facilityId: 0, // Invalid facility ID
            title: 'Smart Irrigation System'
        );

        // Act
        $errors = $project->isValid();

        // Assert
        $this->assertContains('Project.FacilityId is required.', $errors);
    }

    /** @test */
    public function project_with_missing_title_fails_validation()
    {
        // Arrange
        $project = new ProjectEntity(
            programId: 1,
            facilityId: 2,
            title: '' // Missing title
        );

        // Act
        $errors = $project->isValid();

        // Assert
        $this->assertContains('Project.Title is required.', $errors);
    }

    /** @test */
    public function project_program_scoped_unique_key_combines_program_id_and_lowercase_title()
    {
        // Arrange
        $project = new ProjectEntity(
            programId: 5,
            facilityId: 2,
            title: 'Smart Irrigation System'
        );

        // Act
        $uniqueKey = $project->getProgramScopedUniqueKey();

        // Assert
        $this->assertEquals('5|smart irrigation system', $uniqueKey);
    }

    /** @test */
    public function project_can_be_completed_with_team_members_and_outcomes()
    {
        // Arrange
        $project = new ProjectEntity(
            programId: 1,
            facilityId: 2,
            title: 'Smart Irrigation System'
        );

        // Act
        $errors = $project->canBeCompleted(
            hasTeamMembers: true,
            hasOutcomes: true
        );

        // Assert
        $this->assertEmpty($errors);
    }

    /** @test */
    public function project_cannot_be_completed_without_team_members()
    {
        // Arrange
        $project = new ProjectEntity(
            programId: 1,
            facilityId: 2,
            title: 'Smart Irrigation System'
        );

        // Act
        $errors = $project->canBeCompleted(
            hasTeamMembers: false, // No team members
            hasOutcomes: true
        );

        // Assert
        $this->assertContains('Project must have at least one team member assigned.', $errors);
    }

    /** @test */
    public function project_cannot_be_completed_without_outcomes()
    {
        // Arrange
        $project = new ProjectEntity(
            programId: 1,
            facilityId: 2,
            title: 'Smart Irrigation System'
        );

        // Act
        $errors = $project->canBeCompleted(
            hasTeamMembers: true,
            hasOutcomes: false // No outcomes
        );

        // Assert
        $this->assertContains('Completed projects must have at least one documented outcome.', $errors);
    }

    /** @test */
    public function project_cannot_be_completed_without_team_members_and_outcomes()
    {
        // Arrange
        $project = new ProjectEntity(
            programId: 1,
            facilityId: 2,
            title: 'Smart Irrigation System'
        );

        // Act
        $errors = $project->canBeCompleted(
            hasTeamMembers: false, // No team members
            hasOutcomes: false     // No outcomes
        );

        // Assert
        $this->assertContains('Project must have at least one team member assigned.', $errors);
        $this->assertContains('Completed projects must have at least one documented outcome.', $errors);
    }

    /** @test */
    public function project_with_all_optional_fields_passes_validation()
    {
        // Arrange
        $project = new ProjectEntity(
            programId: 1,
            facilityId: 2,
            title: 'Smart Irrigation System',
            natureOfProject: 'Research',
            description: 'An innovative irrigation system using IoT sensors',
            innovationFocus: 'IoT Devices',
            prototypeStage: 'MVP',
            testingRequirements: 'Field testing required',
            commercializationPlan: 'Partner with agricultural companies',
            status: 'Active'
        );

        // Act
        $errors = $project->isValid();

        // Assert
        $this->assertEmpty($errors);
    }
}