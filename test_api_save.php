<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/api/admin/login', 'POST', [
    'email' => 'admin@csa.com',
    'password' => 'password'
]);
$response = $kernel->handle($request);
echo "Login Response: " . $response->getContent() . "\n";

$cookies = [];
foreach ($response->headers->getCookies() as $cookie) {
    $cookies[$cookie->getName()] = $cookie->getValue();
}

$sessionCookie = $cookies['laravel_session'] ?? null;
$csrfCookie = $cookies['XSRF-TOKEN'] ?? null;

$req2 = Illuminate\Http\Request::create('/api/admin/products', 'POST', [
    'name' => 'Test Product',
    'tag' => 'TEST',
    'desc' => 'Test Desc'
]);
if ($sessionCookie) {
    $req2->cookies->set('laravel_session', $sessionCookie);
}
if ($csrfCookie) {
    $req2->headers->set('X-XSRF-TOKEN', $csrfCookie);
    $req2->cookies->set('XSRF-TOKEN', $csrfCookie);
}

$res2 = $kernel->handle($req2);
echo "Create Product Response: " . $res2->getContent() . "\n";

$req3 = Illuminate\Http\Request::create('/api/admin/cms', 'POST', [
    'contents' => [
        ['key' => 'hero_badge', 'value' => 'New Badge']
    ]
]);
if ($sessionCookie) {
    $req3->cookies->set('laravel_session', $sessionCookie);
}
if ($csrfCookie) {
    $req3->headers->set('X-XSRF-TOKEN', $csrfCookie);
    $req3->cookies->set('XSRF-TOKEN', $csrfCookie);
}

$res3 = $kernel->handle($req3);
echo "Update CMS Response: " . $res3->getContent() . "\n";
