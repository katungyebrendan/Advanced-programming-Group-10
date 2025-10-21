<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domain\Entities\EquipmentEntity;

$equipmentService = app(App\Domain\Services\EquipmentDomainService::class);

$entity = new EquipmentEntity(
    id: null,
    facilityId: 1,
    name: 'Test Equipment via Service',
    inventoryCode: 'TEST-EQ-' . time(),
    description: 'Created via equipment domain service',
    capabilities: 'Testing',
    usageDomain: 'Research',
    supportPhase: 'Prototyping'
);

$result = $equipmentService->createEquipment($entity);

echo json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;

if (!empty($result['success']) && !empty($result['equipment'])) {
    echo 'Saved Equipment ID: ' . $result['equipment']->id . PHP_EOL;
}
