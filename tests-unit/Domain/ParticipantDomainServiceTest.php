<?php

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\ParticipantEntity;
use App\Domain\Services\ParticipantDomainService;
use Tests\Unit\Fakes\FakeParticipantRepository;

class ParticipantDomainServiceTest extends TestCase
{
    private FakeParticipantRepository $repository;
    private ParticipantDomainService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new FakeParticipantRepository();
        $this->service = new ParticipantDomainService($this->repository);
    }

    protected function tearDown(): void
    {
        $this->repository->clear();
        parent::tearDown();
    }

    /** @test */
    public function create_participant_with_valid_data_succeeds()
    {
        // Arrange
        $participant = new ParticipantEntity(
            fullName: 'John Doe',
            email: 'john.doe@example.com',
            affiliation: 'CS',
            specialization: 'Software',
            institution: 'SCIT'
        );

        // Act
        $result = $this->service->createParticipant($participant);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('participant', $result);
        $this->assertEquals('John Doe', $result['participant']->fullName);
        $this->assertNotNull($result['participant']->id);
    }

    /** @test */
    public function create_participant_with_missing_full_name_fails()
    {
        // Arrange
        $participant = new ParticipantEntity(
            fullName: '', // Missing full name
            email: 'john.doe@example.com',
            affiliation: 'CS'
        );

        // Act
        $result = $this->service->createParticipant($participant);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Participant.FullName is required.', $result['errors']);
    }

    /** @test */
    public function create_participant_with_missing_email_fails()
    {
        // Arrange
        $participant = new ParticipantEntity(
            fullName: 'John Doe',
            email: '', // Missing email
            affiliation: 'CS'
        );

        // Act
        $result = $this->service->createParticipant($participant);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Participant.Email is required.', $result['errors']);
    }

    /** @test */
    public function create_participant_with_invalid_email_fails()
    {
        // Arrange
        $participant = new ParticipantEntity(
            fullName: 'John Doe',
            email: 'invalid-email', // Invalid email format
            affiliation: 'CS'
        );

        // Act
        $result = $this->service->createParticipant($participant);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Participant.Email must be a valid email address.', $result['errors']);
    }

    /** @test */
    public function create_participant_with_missing_affiliation_fails()
    {
        // Arrange
        $participant = new ParticipantEntity(
            fullName: 'John Doe',
            email: 'john.doe@example.com',
            affiliation: '' // Missing affiliation
        );

        // Act
        $result = $this->service->createParticipant($participant);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Participant.Affiliation is required.', $result['errors']);
    }

    /** @test */
    public function create_participant_with_invalid_affiliation_fails()
    {
        // Arrange
        $participant = new ParticipantEntity(
            fullName: 'John Doe',
            email: 'john.doe@example.com',
            affiliation: 'InvalidAffiliation' // Invalid affiliation
        );

        // Act
        $result = $this->service->createParticipant($participant);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Participant.Affiliation must be one of: CS, SE, Engineering, Other', $result['errors']);
    }

    /** @test */
    public function create_participant_with_duplicate_email_fails()
    {
        // Arrange - Create existing participant
        $existingParticipant = new ParticipantEntity(
            fullName: 'Jane Doe',
            email: 'john.doe@example.com',
            affiliation: 'SE'
        );
        $this->repository->addParticipant($existingParticipant);

        $duplicateParticipant = new ParticipantEntity(
            fullName: 'John Doe',
            email: 'john.doe@example.com', // Same email
            affiliation: 'CS'
        );

        // Act
        $result = $this->service->createParticipant($duplicateParticipant);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Participant.Email already exists.', $result['errors']);
    }

    /** @test */
    public function create_participant_with_duplicate_email_case_insensitive_fails()
    {
        // Arrange - Create existing participant
        $existingParticipant = new ParticipantEntity(
            fullName: 'Jane Doe',
            email: 'john.doe@example.com',
            affiliation: 'SE'
        );
        $this->repository->addParticipant($existingParticipant);

        $duplicateParticipant = new ParticipantEntity(
            fullName: 'John Doe',
            email: 'JOHN.DOE@EXAMPLE.COM', // Same email, different case
            affiliation: 'CS'
        );

        // Act
        $result = $this->service->createParticipant($duplicateParticipant);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Participant.Email already exists.', $result['errors']);
    }

    /** @test */
    public function create_participant_cross_skill_trained_without_specialization_fails()
    {
        // Arrange
        $participant = new ParticipantEntity(
            fullName: 'John Doe',
            email: 'john.doe@example.com',
            affiliation: 'CS',
            specialization: null, // No specialization
            crossSkillTrained: true // But cross-skill trained is true
        );

        // Act
        $result = $this->service->createParticipant($participant);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Cross-skill flag requires Specialization.', $result['errors']);
    }

    /** @test */
    public function create_participant_cross_skill_trained_with_specialization_succeeds()
    {
        // Arrange
        $participant = new ParticipantEntity(
            fullName: 'John Doe',
            email: 'john.doe@example.com',
            affiliation: 'CS',
            specialization: 'Software', // Has specialization
            crossSkillTrained: true // Cross-skill trained is true
        );

        // Act
        $result = $this->service->createParticipant($participant);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('participant', $result);
    }

    /** @test */
    public function create_participant_with_invalid_specialization_fails()
    {
        // Arrange
        $participant = new ParticipantEntity(
            fullName: 'John Doe',
            email: 'john.doe@example.com',
            affiliation: 'CS',
            specialization: 'InvalidSpecialization' // Invalid specialization
        );

        // Act
        $result = $this->service->createParticipant($participant);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Participant.Specialization must be one of: Software, Hardware, Business', $result['errors']);
    }

    /** @test */
    public function delete_participant_with_active_projects_fails()
    {
        // Arrange - Create participant and simulate active projects
        $participant = new ParticipantEntity(
            fullName: 'John Doe',
            email: 'john.doe@example.com',
            affiliation: 'CS'
        );
        $savedParticipant = $this->repository->addParticipant($participant);
        $this->repository->setHasActiveProjects(true);

        // Act
        $result = $this->service->deleteParticipant($savedParticipant->id);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Participant cannot be deleted because they are assigned to active projects.', $result['errors']);
    }

    /** @test */
    public function delete_participant_without_active_projects_succeeds()
    {
        // Arrange - Create participant with no active projects
        $participant = new ParticipantEntity(
            fullName: 'John Doe',
            email: 'john.doe@example.com',
            affiliation: 'CS'
        );
        $savedParticipant = $this->repository->addParticipant($participant);
        $this->repository->setHasActiveProjects(false);

        // Act
        $result = $this->service->deleteParticipant($savedParticipant->id);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertNull($this->repository->findById($savedParticipant->id));
    }
}