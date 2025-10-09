<?php

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Entities\ServiceEntity;

class ServiceEntityTest extends TestCase
{
    /** @test */
    public function service_with_valid_data_passes_validation()
    {
        // Arrange
        $service = new ServiceEntity(
            facilityId: 1,
            name: '3D Printing Service',
            category: 'Machining',
            skillType: 'Hardware'
        );

        // Act
        $errors = $service->isValid();

        // Assert
        $this->assertEmpty($errors);
    }

    /** @test */
    public function service_with_missing_facility_id_fails_validation()
    {
        // Arrange
        $service = new ServiceEntity(
            facilityId: 0, // Invalid facility ID
            name: '3D Printing Service',
            category: 'Machining',
            skillType: 'Hardware'
        );

        // Act
        $errors = $service->isValid();

        // Assert
        $this->assertContains('Service.FacilityId is required.', $errors);
    }

    /** @test */
    public function service_with_missing_name_fails_validation()
    {
        // Arrange
        $service = new ServiceEntity(
            facilityId: 1,
            name: '', // Missing name
            category: 'Machining',
            skillType: 'Hardware'
        );

        // Act
        $errors = $service->isValid();

        // Assert
        $this->assertContains('Service.Name is required.', $errors);
    }

    /** @test */
    public function service_with_missing_category_fails_validation()
    {
        // Arrange
        $service = new ServiceEntity(
            facilityId: 1,
            name: '3D Printing Service',
            category: '', // Missing category
            skillType: 'Hardware'
        );

        // Act
        $errors = $service->isValid();

        // Assert
        $this->assertContains('Service.Category is required.', $errors);
    }

    /** @test */
    public function service_with_missing_skill_type_fails_validation()
    {
        // Arrange
        $service = new ServiceEntity(
            facilityId: 1,
            name: '3D Printing Service',
            category: 'Machining',
            skillType: '' // Missing skill type
        );

        // Act
        $errors = $service->isValid();

        // Assert
        $this->assertContains('Service.SkillType is required.', $errors);
    }

    /** @test */
    public function service_with_invalid_category_fails_validation()
    {
        // Arrange
        $service = new ServiceEntity(
            facilityId: 1,
            name: '3D Printing Service',
            category: 'InvalidCategory', // Invalid category
            skillType: 'Hardware'
        );

        // Act
        $errors = $service->isValid();

        // Assert
        $this->assertContains('Service.Category must be one of: Machining, Testing, Training', $errors);
    }

    /** @test */
    public function service_with_invalid_skill_type_fails_validation()
    {
        // Arrange
        $service = new ServiceEntity(
            facilityId: 1,
            name: '3D Printing Service',
            category: 'Machining',
            skillType: 'InvalidSkillType' // Invalid skill type
        );

        // Act
        $errors = $service->isValid();

        // Assert
        $this->assertContains('Service.SkillType must be one of: Hardware, Software, Integration', $errors);
    }

    /** @test */
    public function service_scoped_unique_key_combines_facility_id_and_lowercase_name()
    {
        // Arrange
        $service = new ServiceEntity(
            facilityId: 5,
            name: 'Testing Service',
            category: 'Testing',
            skillType: 'Software'
        );

        // Act
        $uniqueKey = $service->getScopedUniqueKey();

        // Assert
        $this->assertEquals('5|testing service', $uniqueKey);
    }

    /** @test */
    public function service_with_all_valid_categories_passes_validation()
    {
        $validCategories = ['Machining', 'Testing', 'Training'];
        
        foreach ($validCategories as $category) {
            // Arrange
            $service = new ServiceEntity(
                facilityId: 1,
                name: 'Test Service',
                category: $category,
                skillType: 'Hardware'
            );

            // Act
            $errors = $service->isValid();

            // Assert
            $this->assertEmpty($errors, "Category '{$category}' should be valid");
        }
    }

    /** @test */
    public function service_with_all_valid_skill_types_passes_validation()
    {
        $validSkillTypes = ['Hardware', 'Software', 'Integration'];
        
        foreach ($validSkillTypes as $skillType) {
            // Arrange
            $service = new ServiceEntity(
                facilityId: 1,
                name: 'Test Service',
                category: 'Testing',
                skillType: $skillType
            );

            // Act
            $errors = $service->isValid();

            // Assert
            $this->assertEmpty($errors, "Skill type '{$skillType}' should be valid");
        }
    }
}