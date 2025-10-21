<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\FacilityController;
use App\Http\Requests\CreateFacilityRequest;

// Create a request-like object
$request = CreateFacilityRequest::create('/facilities', 'POST', [
    'name' => 'ControllerFacility',
    'location' => 'ControllerLocation',
    'facility_type' => 'Lab',
    'capabilities' => 'x,y,z'
]);

// Prepare the FormRequest like the framework would: set container and run validation
$request->setContainer(app());
$request->validateResolved();

$controller = app(FacilityController::class);

$response = $controller->store($request);

if (method_exists($response, 'getTargetUrl')) {
    echo 'Redirecting to: ' . $response->getTargetUrl() . PHP_EOL;
} else {
    echo get_class($response) . PHP_EOL;
}
