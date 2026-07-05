<?php
// Quick test script for route protection and view rendering

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$routes_to_test = [
    '/' => 'Redirect to login',
    '/buku' => 'Auth redirect',
    '/anggota' => 'Auth redirect',
    '/transaksi' => 'Auth redirect',
    '/dashboard' => 'Auth redirect',
];

echo "=== PRAKTIKUM 3: Route Protection Test ===" . PHP_EOL;
foreach ($routes_to_test as $uri => $expected) {
    $request = Illuminate\Http\Request::create($uri, 'GET');
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    $location = $response->headers->get('Location', '-');
    
    if ($status === 302) {
        echo "  [OK] GET $uri -> $status (Redirect to: $location)" . PHP_EOL;
    } else {
        echo "  [!!] GET $uri -> $status (Expected redirect, got $status)" . PHP_EOL;
    }
    $kernel->terminate($request, $response);
}

echo PHP_EOL . "=== PRAKTIKUM 4: Model & Migration Test ===" . PHP_EOL;
try {
    // Test Transaksi model can query
    $count = App\Models\Transaksi::count();
    echo "  [OK] Transaksi model: Table exists, count = $count" . PHP_EOL;
} catch (Exception $e) {
    echo "  [ERROR] Transaksi model: " . $e->getMessage() . PHP_EOL;
}

try {
    // Test relationships
    $anggota = new App\Models\Anggota();
    $rel = $anggota->transaksis();
    echo "  [OK] Anggota->transaksis() relationship exists" . PHP_EOL;
} catch (Exception $e) {
    echo "  [ERROR] Anggota->transaksis(): " . $e->getMessage() . PHP_EOL;
}

try {
    $buku = new App\Models\Buku();
    $rel = $buku->transaksis();
    echo "  [OK] Buku->transaksis() relationship exists" . PHP_EOL;
} catch (Exception $e) {
    echo "  [ERROR] Buku->transaksis(): " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== PRAKTIKUM 2: View Rendering Test ===" . PHP_EOL;

// Test dashboard view rendering (need auth)
try {
    // Create a fake user to test authenticated views
    $user = App\Models\User::first();
    if ($user) {
        auth()->login($user);
        
        // Test dashboard
        $request = Illuminate\Http\Request::create('/dashboard', 'GET');
        $request->setUserResolver(function () use ($user) { return $user; });
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        if ($status === 200) {
            echo "  [OK] Dashboard view renders (status $status)" . PHP_EOL;
        } else {
            echo "  [!!] Dashboard view: status $status" . PHP_EOL;
        }
        $kernel->terminate($request, $response);
        
        // Test transaksi index
        $request = Illuminate\Http\Request::create('/transaksi', 'GET');
        $request->setUserResolver(function () use ($user) { return $user; });
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        if ($status === 200) {
            echo "  [OK] Transaksi index view renders (status $status)" . PHP_EOL;
        } else {
            echo "  [!!] Transaksi index: status $status" . PHP_EOL;
        }
        $kernel->terminate($request, $response);

        // Test transaksi create
        $request = Illuminate\Http\Request::create('/transaksi/create', 'GET');
        $request->setUserResolver(function () use ($user) { return $user; });
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        if ($status === 200) {
            echo "  [OK] Transaksi create view renders (status $status)" . PHP_EOL;
        } else {
            echo "  [!!] Transaksi create: status $status" . PHP_EOL;
        }
        $kernel->terminate($request, $response);

        // Test anggota index
        $request = Illuminate\Http\Request::create('/anggota', 'GET');
        $request->setUserResolver(function () use ($user) { return $user; });
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        if ($status === 200) {
            echo "  [OK] Anggota index view renders (status $status)" . PHP_EOL;
        } else {
            echo "  [!!] Anggota index: status $status" . PHP_EOL;
        }
        $kernel->terminate($request, $response);

        // Test anggota create (auto-generate kode)
        $request = Illuminate\Http\Request::create('/anggota/create', 'GET');
        $request->setUserResolver(function () use ($user) { return $user; });
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        if ($status === 200) {
            echo "  [OK] Anggota create view renders (status $status) - auto-generate kode works" . PHP_EOL;
        } else {
            echo "  [!!] Anggota create: status $status" . PHP_EOL;
        }
        $kernel->terminate($request, $response);

        // Test buku index
        $request = Illuminate\Http\Request::create('/buku', 'GET');
        $request->setUserResolver(function () use ($user) { return $user; });
        $response = $kernel->handle($request);
        $status = $response->getStatusCode();
        if ($status === 200) {
            echo "  [OK] Buku index view renders (status $status)" . PHP_EOL;
        } else {
            echo "  [!!] Buku index: status $status" . PHP_EOL;
        }
        $kernel->terminate($request, $response);

        auth()->logout();
    } else {
        echo "  [SKIP] No user in DB - cannot test authenticated views" . PHP_EOL;
    }
} catch (Exception $e) {
    echo "  [ERROR] View test: " . $e->getMessage() . PHP_EOL;
    echo "         File: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
}

echo PHP_EOL . "=== PRAKTIKUM 5: TransaksiController Logic Test ===" . PHP_EOL;
try {
    $controller = new App\Http\Controllers\TransaksiController();
    echo "  [OK] TransaksiController instantiated" . PHP_EOL;
} catch (Exception $e) {
    echo "  [ERROR] TransaksiController: " . $e->getMessage() . PHP_EOL;
}

// Test generateKodeTransaksi via reflection
try {
    $controller = new App\Http\Controllers\TransaksiController();
    $method = new ReflectionMethod($controller, 'generateKodeTransaksi');
    $method->setAccessible(true);
    $kode = $method->invoke($controller);
    echo "  [OK] generateKodeTransaksi() = $kode" . PHP_EOL;
} catch (Exception $e) {
    echo "  [ERROR] generateKodeTransaksi: " . $e->getMessage() . PHP_EOL;
}

// Test AnggotaController generateKodeAnggota
try {
    $controller = new App\Http\Controllers\AnggotaController();
    $method = new ReflectionMethod($controller, 'generateKodeAnggota');
    $method->setAccessible(true);
    $kode = $method->invoke($controller);
    echo "  [OK] generateKodeAnggota() = $kode" . PHP_EOL;
} catch (Exception $e) {
    echo "  [ERROR] generateKodeAnggota: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== ALL TESTS COMPLETE ===" . PHP_EOL;
