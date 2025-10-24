<?php
// Config.php
return (object)[
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'name' => getenv('DB_NAME') ?: 'saveeat_lab',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4'
    ],
    'app' => [
        'base_url' => getenv('BASE_URL') ?: 'http://localhost/saveeat-lab/public'
    ],
    'mail' => [
        'from' => 'noreply@example.com',
        'from_name' => 'SaveEat Lab'
    ]
];
