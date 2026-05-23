<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\Admin\CmsController;
use Illuminate\Http\Request;

$request = Request::create('/api/admin/cms', 'POST', [
    'contents' => [
        ['key' => 'hero_badge', 'value' => 'New Badge']
    ]
]);

$controller = new CmsController();
try {
    $response = $controller->updateBatch($request);
    echo $response->getContent();
} catch (\Illuminate\Validation\ValidationException $e) {
    echo "Validation Error:\n";
    print_r($e->errors());
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
