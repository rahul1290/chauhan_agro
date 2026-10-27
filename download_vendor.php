<?php

$vendorDir = __DIR__ . '/public/assets/vendor';
if (!is_dir($vendorDir . '/css')) mkdir($vendorDir . '/css', 0777, true);
if (!is_dir($vendorDir . '/js')) mkdir($vendorDir . '/js', 0777, true);

$files = [
    $vendorDir . '/css/bootstrap.min.css' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css',
    $vendorDir . '/js/bootstrap.bundle.min.js' => 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js',
    $vendorDir . '/css/bootstrap-icons.min.css' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
];

foreach ($files as $localPath => $url) {
    if (!file_exists($localPath)) {
        echo "Downloading {$url}...\n";
        $content = @file_get_contents($url);
        if ($content) {
            file_put_contents($localPath, $content);
            echo "Saved to {$localPath}\n";
        }
    }
}
echo "Vendor assets setup complete!\n";
