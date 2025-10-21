<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domain\Entities\FacilityEntity;

$service = app(App\Domain\Services\FacilityDomainService::class);

$entity = new FacilityEntity(
    id: null,
    name: 'DomainServiceFacility',
    location: 'DomainLocation',
    facilityType: 'Lab',
    description: 'Created via domain service',
    partnerOrganization: null,
    capabilities: ['r&d','testing']
);

$result = $service->createFacility($entity);

echo json_encode($result, JSON_PRETTY_PRINT) . PHP_EOL;

if (!empty($result['success']) && !empty($result['facility'])) {
    echo 'Saved Facility ID: ' . $result['facility']->id . PHP_EOL;
}
