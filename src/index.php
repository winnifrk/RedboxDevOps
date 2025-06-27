<?php

if (!file_exists(__DIR__ . '/../vendor/autoload.php')){
    echo "Composer not installed<br>\n";
    exit(1);
}

require __DIR__ . '/../vendor/autoload.php';

$redisPing = false;
$postgresMessage = false;


if (!class_exists("Redis")){
    echo "Redis class not installed<br>\n";
} else {
    $redis = new \Redis();
    $redisHost = getenv('REDIS_HOST') ?: 'redis';
    $redisPort = getenv('REDIS_PORT') ?: 6379;
    try {
        $redis->connect($redisHost,$redisPort);
        $redisPing = $redis->ping();
    } catch (\Throwable $e) {
        echo "Redis connection (tcp://:@$redisHost:$redisPort) failed: {$e->getMessage()}<br>\n";
    }
}

if (!class_exists("PDO")){
    echo "PDO class not installed<br>\n";
} else {
    $pdoHost = getenv('DB_HOST') ?: 'db';
    $pdoDatabase = getenv('DB_DATABASE') ?: 'testdb';
    $pdoUsername = getenv('DB_USERNAME') ?: 'testuser';
    $pdoPassword = getenv('DB_PASSWORD') ?: 'testpass';
    try {
        $pdo = new PDO(
            sprintf('pgsql:host=%s;port=5432;dbname=%s', $pdoHost, $pdoDatabase),
            $pdoUsername,
            $pdoPassword
        );
        $stmt = $pdo->query("SELECT 'Hello from PostgreSQL' as message");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $postgresMessage = $row['message'] ?? null;
    } catch (\Throwable $e) {
        echo "PDO connection (pgsql://$pdoUsername:$pdoPassword@$pdoHost/$pdoDatabase) failed: {$e->getMessage()}<br>\n";
    }
}


echo "<h1>PHP DevOps Test Project</h1><br>\n";
echo "<p>Redis connected: " . ($redisPing ? 'Yes' : 'No') . "</p><br>\n";
echo "<p>PostgreSQL connected: " . ($postgresMessage ? "Yes": "No") . "</p><br>\n";
