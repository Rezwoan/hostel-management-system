<?php

/**
 * Example configuration file.
 *
 * Copy this file to config.php and update values:
 *   cp config/config.example.php config/config.php
 *
 * NOTE: config/config.php must NOT be committed (it should be in .gitignore).
 */

return [
    'app' => [
        // Use: 'development' or 'production'
        'env' => 'development',

        // Display detailed errors in development only
        'debug' => true,

        // Set a random long string in production (used later for CSRF, etc.)
        'key' => 'CHANGE_ME_TO_A_LONG_RANDOM_STRING',
    ],

    'db' => [
        'driver'  => 'mysql',
        'host'    => '127.0.0.1',
        'port'    => 3306,
        'name'    => 'hostel_management',
        'user'    => 'root',
        'pass'    => '',
        'charset' => 'utf8mb4',

        // PDO options (safe defaults)
        'options' => [
            // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // PDO::ATTR_EMULATE_PREPARES => false,
        ],
    ],
];
