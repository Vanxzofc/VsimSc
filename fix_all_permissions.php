<?php
// 🔧 FIX FILE & FOLDER PERMISSIONS
// Gunakan untuk memperbaiki error permission (403/404) di folder project PHP kamu.
// Otomatis set:
//   - Folder: 0755
//   - File:   0644
// Contoh URL: https://vanzhosting.my.id/fix_all_permissions.php

function fixPermissions($dir) {
    $rii = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($rii as $file) {
        $path = $file->getPathname();

        if ($file->isDir()) {
            chmod($path, 0755); // Folder
        } elseif ($file->isFile()) {
            chmod($path, 0644); // File
        }
    }

    // Set juga direktori root-nya
    chmod($dir, 0755);
}

// 🎯 TARGET FOLDER
$targets = ['routes', __DIR__]; // Folder 'routes' dan folder tempat file ini berada

foreach ($targets as $folder) {
    $path = is_dir($folder) ? realpath($folder) : $folder;
    if ($path) {
        fixPermissions($path);
    }
}

echo "✅ Semua Permission Berhasil Di Set.";
