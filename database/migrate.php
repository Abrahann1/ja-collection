<?php
declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(function (string $class) {
    $prefixes = [
        'App\\' => BASE_PATH . '/app/',
        'Database\\Seeders\\' => BASE_PATH . '/database/seeders/'
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

use App\Core\Env;
use App\Core\Database;
use Database\Seeders\DatabaseSeeder;

Env::load(BASE_PATH);

$config = require BASE_PATH . '/config/database.php';

echo "==================================================\n";
echo "   J.A COLLECTION - MIGRACIÓN DE BASE DE DATOS    \n";
echo "==================================================\n";

try {
    // 1. Conectar a MySQL sin seleccionar DB para crearla si no existe
    $pdoRoot = new PDO(
        sprintf('%s:host=%s;port=%d;charset=%s', $config['driver'], $config['host'], $config['port'], $config['charset']),
        $config['username'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $dbName = $config['database'];
    $pdoRoot->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "[OK] Base de datos '{$dbName}' verificada/creada.\n";

    // 2. Ejecutar archivo de esquema
    $db = Database::getConnection();
    $sql = file_get_contents(BASE_PATH . '/database/migrations/001_initial_schema.sql');
    $db->exec($sql);
    echo "[OK] Tablas del esquema relacional creadas exitosamente.\n";

    // 3. Ejecutar Seeder de datos iniciales
    DatabaseSeeder::run();

    echo "==================================================\n";
    echo "   ¡MIGRACIÓN Y SEEDING COMPLETADOS CON ÉXITO!   \n";
    echo "==================================================\n";

} catch (Throwable $e) {
    echo "\n[ERROR CRÍTICO]: " . $e->getMessage() . "\n";
    exit(1);
}