<?php

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\ProgramEntity;
use App\Domain\Services\ProgramDomainService;
use Tests\Unit\Fakes\FakeProgramRepository;

class ProgramDomainServiceTest extends TestCase
{
    private FakeProgramRepository $repository;
    private ProgramDomainService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new FakeProgramRepository();
        $this->service = new ProgramDomainService($this->repository);
    }

    protected function tearDown(): void
    {
        $this->repository->clear();
        parent::tearDown();
    }

    /** @test */
    public function create_program_with_valid_data_succeeds()
    {
        // Arrange
        $program = new ProgramEntity(
            name: 'Smart IoT Program',
            description: 'A program focused on IoT innovation'
        );

        // Act
        $result = $this->service->createProgram($program);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('program', $result);
        $this->assertEquals('Smart IoT Program', $result['program']->name);
        $this->assertNotNull($result['program']->id);
    }

    /** @test */
    public function create_program_with_missing_name_fails()
    {
        // Arrange
        $program = new ProgramEntity(
            name: '', // Missing name
            description: 'A program description'
        );

        // Act
        $result = $this->service->createProgram($program);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Program.Name is required.', $result['errors']);
    }

    /** @test */
    public function create_program_with_missing_description_fails()
    {
        // Arrange
        $program = new ProgramEntity(
            name: 'Smart IoT Program',
            description: '' // Missing description
        );

        // Act
        $result = $this->service->createProgram($program);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Program.Description is required.', $result['errors']);
    }

    /** @test */
    public function create_program_with_duplicate_name_fails()
    {
        // Arrange - Create existing program
        $existingProgram = new ProgramEntity(
            name: 'Smart IoT Program',
            description: 'Existing program'
        );
        $this->repository->addProgram($existingProgram);

        $duplicateProgram = new ProgramEntity(
            name: 'Smart IoT Program', // Same name
            description: 'New program with duplicate name'
        );

        // Act
        $result = $this->service->createProgram($duplicateProgram);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Program.Name already exists.', $result['errors']);
    }

    /** @test */
    public function create_program_with_duplicate_name_case_insensitive_fails()
    {
        // Arrange - Create existing program
        $existingProgram = new ProgramEntity(
            name: 'Smart IoT Program',
            description: 'Existing program'
        );
        $this->repository->addProgram($existingProgram);

        $duplicateProgram = new ProgramEntity(
            name: 'SMART IOT PROGRAM', // Same name, different case
            description: 'New program with duplicate name'
        );

        // Act
        $result = $this->service->createProgram($duplicateProgram);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Program.Name already exists.', $result['errors']);
    }

    /** @test */
    public function create_program_with_invalid_national_alignment_fails()
    {
        // Arrange
        $program = new ProgramEntity(
            name: 'Smart IoT Program',
            description: 'A program description',
            nationalAlignment: 'InvalidAlignment',
            focusAreas: ['IoT', 'Automation'] // Has focus areas, so national alignment is required
        );

        // Act
        $result = $this->service->createProgram($program);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains(
            'Program.NationalAlignment must include at least one recognized alignment when FocusAreas are specified.',
            $result['errors']
        );
    }

    /** @test */
    public function create_program_with_valid_national_alignment_succeeds()
    {
        // Arrange
        $program = new ProgramEntity(
            name: 'Smart IoT Program',
            description: 'A program description',
            nationalAlignment: 'NDPIII, DigitalRoadmap2023_2028',
            focusAreas: ['IoT', 'Automation']
        );

        // Act
        $result = $this->service->createProgram($program);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('program', $result);
    }

    /** @test */
    public function delete_program_with_projects_fails()
    {
        // Arrange - Create program and simulate it has projects
        $program = new ProgramEntity(
            name: 'Smart IoT Program',
            description: 'A program description'
        );
        $savedProgram = $this->repository->addProgram($program);
        $this->repository->setHasProjects(true);

        // Act
        $result = $this->service->deleteProgram($savedProgram->id);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Program has Projects; archive or reassign before delete.', $result['errors']);
    }

    /** @test */
    public function delete_program_without_projects_succeeds()
    {
        // Arrange - Create program with no projects
        $program = new ProgramEntity(
            name: 'Smart IoT Program',
            description: 'A program description'
        );
        $savedProgram = $this->repository->addProgram($program);
        $this->repository->setHasProjects(false);

        // Act
        $result = $this->service->deleteProgram($savedProgram->id);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertNull($this->repository->findById($savedProgram->id));
    }
}