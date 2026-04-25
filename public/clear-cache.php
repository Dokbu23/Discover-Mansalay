<?php
// This file can be deleted after use
require __DIR__ . '/../bootstrap/autoload.php';

try {
    $app = require __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
    
    // Clear all caches
    $kernel->call('cache:clear');
    $kernel->call('view:clear');
    $kernel->call('config:clear');
    $kernel->call('route:clear');
    
    echo "✅ All caches cleared! You can now delete this file.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
