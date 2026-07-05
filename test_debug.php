<?php
// Debug 500 errors

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$user = App\Models\User::first();
if ($user) {
    auth()->login($user);
    
    // Test transaksi index with error details
    try {
        $request = Illuminate\Http\Request::create('/transaksi', 'GET');
        $request->setUserResolver(function () use ($user) { return $user; });
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        if ($status === 500) {
            echo "=== TRANSAKSI INDEX ERROR ===" . PHP_EOL;
            echo $response->getContent() . PHP_EOL;
        }
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . PHP_EOL;
        echo "File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    }

    auth()->logout();
}
