<?php

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\FacilityEntity;
use App\Domain\Services\FacilityDomainService;
use Tests\Unit\Fakes\FakeFacilityRepository;

class FacilityDomainServiceTest extends TestCase
{
    private FakeFacilityRepository $repository;
    private FacilityDomainService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new FakeFacilityRepository();
        $this->service = new FacilityDomainService($this->repository);
    }

    protected function tearDown(): void
    {
        $this->repository->clear();
        parent::tearDown();
    }

    /** @test */
    public function create_facility_with_valid_data_succeeds()
    {
        // Arrange
        $facility = new FacilityEntity(
            name: 'Innovation Lab',
            location: 'Makerere University',
            facilityType: 'Lab'
        );

        // Act
        $result = $this->service->createFacility($facility);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('facility', $result);
        $this->assertEquals('Innovation Lab', $result['facility']->name);
        $this->assertNotNull($result['facility']->id);
    }

    /** @test */
    public function create_facility_with_missing_name_fails()
    {
        // Arrange
        $facility = new FacilityEntity(
            name: '', // Missing name
            location: 'Makerere University',
            facilityType: 'Lab'
        );

        // Act
        $result = $this->service->createFacility($facility);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Facility.Name is required.', $result['errors']);
    }

    /** @test */
    public function create_facility_with_missing_location_fails()
    {
        // Arrange
        $facility = new FacilityEntity(
            name: 'Innovation Lab',
            location: '', // Missing location
            facilityType: 'Lab'
        );

        // Act
        $result = $this->service->createFacility($facility);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Facility.Location is required.', $result['errors']);
    }

    /** @test */
    public function create_facility_with_missing_facility_type_fails()
    {
        // Arrange
        $facility = new FacilityEntity(
            name: 'Innovation Lab',
            location: 'Makerere University',
            facilityType: '' // Missing facility type
        );

        // Act
        $result = $this->service->createFacility($facility);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Facility.FacilityType is required.', $result['errors']);
    }

    /** @test */
    public function create_facility_with_duplicate_name_and_location_fails()
    {
        // Arrange - Create existing facility
        $existingFacility = new FacilityEntity(
            name: 'Innovation Lab',
            location: 'Makerere University',
            facilityType: 'Lab'
        );
        $this->repository->addFacility($existingFacility);

        $duplicateFacility = new FacilityEntity(
            name: 'Innovation Lab', // Same name
            location: 'Makerere University', // Same location
            facilityType: 'Workshop'
        );

        // Act
        $result = $this->service->createFacility($duplicateFacility);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('A facility with this name already exists at this location.', $result['errors']);
    }

    /** @test */
    public function create_facility_with_same_name_different_location_succeeds()
    {
        // Arrange - Create existing facility
        $existingFacility = new FacilityEntity(
            name: 'Innovation Lab',
            location: 'Makerere University',
            facilityType: 'Lab'
        );
        $this->repository->addFacility($existingFacility);

        $newFacility = new FacilityEntity(
            name: 'Innovation Lab', // Same name
            location: 'UIRI Campus', // Different location
            facilityType: 'Lab'
        );

        // Act
        $result = $this->service->createFacility($newFacility);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('facility', $result);
    }

    /** @test */
    public function update_facility_without_capabilities_when_has_services_fails()
    {
        // Arrange - Create facility
        $facility = new FacilityEntity(
            id: 1,
            name: 'Innovation Lab',
            location: 'Makerere University',
            facilityType: 'Lab',
            capabilities: [] // No capabilities
        );
        
        // Simulate facility has services
        $this->repository->setHasServices(true);

        // Act
        $result = $this->service->updateFacility($facility);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Facility.Capabilities must be populated when Services/Equipment exist.', $result['errors']);
    }

    /** @test */
    public function update_facility_without_capabilities_when_has_equipment_fails()
    {
        // Arrange - Create facility
        $facility = new FacilityEntity(
            id: 1,
            name: 'Innovation Lab',
            location: 'Makerere University',
            facilityType: 'Lab',
            capabilities: [] // No capabilities
        );
        
        // Simulate facility has equipment
        $this->repository->setHasEquipment(true);

        // Act
        $result = $this->service->updateFacility($facility);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Facility.Capabilities must be populated when Services/Equipment exist.', $result['errors']);
    }

    /** @test */
    public function delete_facility_with_services_fails()
    {
        // Arrange - Create facility and simulate it has services
        $facility = new FacilityEntity(
            name: 'Innovation Lab',
            location: 'Makerere University',
            facilityType: 'Lab'
        );
        $savedFacility = $this->repository->addFacility($facility);
        $this->repository->setHasServices(true);

        // Act
        $result = $this->service->deleteFacility($savedFacility->id);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Facility has dependent records (Services).', $result['errors']);
    }

    /** @test */
    public function delete_facility_with_equipment_fails()
    {
        // Arrange - Create facility and simulate it has equipment
        $facility = new FacilityEntity(
            name: 'Innovation Lab',
            location: 'Makerere University',
            facilityType: 'Lab'
        );
        $savedFacility = $this->repository->addFacility($facility);
        $this->repository->setHasEquipment(true);

        // Act
        $result = $this->service->deleteFacility($savedFacility->id);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Facility has dependent records (Equipment).', $result['errors']);
    }

    /** @test */
    public function delete_facility_with_projects_fails()
    {
        // Arrange - Create facility and simulate it has projects
        $facility = new FacilityEntity(
            name: 'Innovation Lab',
            location: 'Makerere University',
            facilityType: 'Lab'
        );
        $savedFacility = $this->repository->addFacility($facility);
        $this->repository->setHasProjects(true);

        // Act
        $result = $this->service->deleteFacility($savedFacility->id);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Facility has dependent records (Projects).', $result['errors']);
    }

    /** @test */
    public function delete_facility_with_multiple_dependencies_fails()
    {
        // Arrange - Create facility and simulate it has all dependencies
        $facility = new FacilityEntity(
            name: 'Innovation Lab',
            location: 'Makerere University',
            facilityType: 'Lab'
        );
        $savedFacility = $this->repository->addFacility($facility);
        $this->repository->setHasServices(true);
        $this->repository->setHasEquipment(true);
        $this->repository->setHasProjects(true);

        // Act
        $result = $this->service->deleteFacility($savedFacility->id);

        // Assert
        $this->assertFalse($result['success']);
        $this->assertContains('Facility has dependent records (Services/Equipment/Projects).', $result['errors']);
    }

    /** @test */
    public function delete_facility_without_dependencies_succeeds()
    {
        // Arrange - Create facility with no dependencies
        $facility = new FacilityEntity(
            name: 'Innovation Lab',
            location: 'Makerere University',
            facilityType: 'Lab'
        );
        $savedFacility = $this->repository->addFacility($facility);
        // All dependencies are false by default

        // Act
        $result = $this->service->deleteFacility($savedFacility->id);

        // Assert
        $this->assertTrue($result['success']);
        $this->assertNull($this->repository->findById($savedFacility->id));
    }
}