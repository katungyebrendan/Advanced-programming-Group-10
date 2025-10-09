<?php

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\EquipmentEntity;

class EquipmentEntityTest extends TestCase
{
    /** @test */
    public function equipment_with_valid_data_passes_validation()
    {
        // Arrange
        $equipment = new EquipmentEntity(
            facilityId: 1,
            name: 'Laser Cutter',
            inventoryCode: 'LC001'
        );

        // Act
        $errors = $equipment->isValid();

        // Assert
        $this->assertEmpty($errors);
    }

    /** @test */
    public function equipment_with_missing_facility_id_fails_validation()
    {
        // Arrange
        $equipment = new EquipmentEntity(
            facilityId: 0, // Invalid facility ID
            name: 'Laser Cutter',
            inventoryCode: 'LC001'
        );

        // Act
        $errors = $equipment->isValid();

        // Assert
        $this->assertContains('Equipment.FacilityId is required.', $errors);
    }

    /** @test */
    public function equipment_with_missing_name_fails_validation()
    {
        // Arrange
        $equipment = new EquipmentEntity(
            facilityId: 1,
            name: '', // Missing name
            inventoryCode: 'LC001'
        );

        // Act
        $errors = $equipment->isValid();

        // Assert
        $this->assertContains('Equipment.Name is required.', $errors);
    }

    /** @test */
    public function equipment_with_missing_inventory_code_fails_validation()
    {
        // Arrange
        $equipment = new EquipmentEntity(
            facilityId: 1,
            name: 'Laser Cutter',
            inventoryCode: '' // Missing inventory code
        );

        // Act
        $errors = $equipment->isValid();

        // Assert
        $this->assertContains('Equipment.InventoryCode is required.', $errors);
    }

    /** @test */
    public function electronics_equipment_with_training_only_support_phase_fails_validation()
    {
        // Arrange
        $equipment = new EquipmentEntity(
            facilityId: 1,
            name: 'Arduino Kit',
            inventoryCode: 'ARD001',
            usageDomain: 'Electronics',
            supportPhase: 'Training' // Training only
        );

        // Act
        $errors = $equipment->isValid();

        // Assert
        $this->assertContains('Electronics equipment must support Prototyping or Testing.', $errors);
    }

    /** @test */
    public function electronics_equipment_with_prototyping_support_phase_passes_validation()
    {
        // Arrange
        $equipment = new EquipmentEntity(
            facilityId: 1,
            name: 'Arduino Kit',
            inventoryCode: 'ARD001',
            usageDomain: 'Electronics',
            supportPhase: 'Prototyping'
        );

        // Act
        $errors = $equipment->isValid();

        // Assert
        $this->assertEmpty($errors);
    }

    /** @test */
    public function electronics_equipment_with_testing_support_phase_passes_validation()
    {
        // Arrange
        $equipment = new EquipmentEntity(
            facilityId: 1,
            name: 'Oscilloscope',
            inventoryCode: 'OSC001',
            usageDomain: 'Electronics',
            supportPhase: 'Testing'
        );

        // Act
        $errors = $equipment->isValid();

        // Assert
        $this->assertEmpty($errors);
    }

    /** @test */
    public function electronics_equipment_with_prototyping_and_testing_support_phases_passes_validation()
    {
        // Arrange
        $equipment = new EquipmentEntity(
            facilityId: 1,
            name: 'Development Board',
            inventoryCode: 'DEV001',
            usageDomain: 'Electronics',
            supportPhase: 'Prototyping, Testing'
        );

        // Act
        $errors = $equipment->isValid();

        // Assert
        $this->assertEmpty($errors);
    }

    /** @test */
    public function electronics_equipment_with_prototyping_and_training_support_phases_passes_validation()
    {
        // Arrange
        $equipment = new EquipmentEntity(
            facilityId: 1,
            name: 'Training Kit',
            inventoryCode: 'TRN001',
            usageDomain: 'Electronics',
            supportPhase: 'Prototyping, Training' // Has prototyping, so training is allowed
        );

        // Act
        $errors = $equipment->isValid();

        // Assert
        $this->assertEmpty($errors);
    }

    /** @test */
    public function non_electronics_equipment_with_training_only_passes_validation()
    {
        // Arrange
        $equipment = new EquipmentEntity(
            facilityId: 1,
            name: 'Mechanical Tools',
            inventoryCode: 'MEC001',
            usageDomain: 'Mechanical',
            supportPhase: 'Training' // Rule only applies to Electronics
        );

        // Act
        $errors = $equipment->isValid();

        // Assert
        $this->assertEmpty($errors);
    }
}