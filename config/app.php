<?php
declare(strict_types=1);

use App\Core\Env;

return [
    'name' => Env::get('APP_NAME', 'J.A COLLECTION'),
    'env' => Env::get('APP_ENV', 'local'),
    'debug' => (bool) Env::get('APP_DEBUG', true),
    'url' => Env::get('APP_URL', 'http://localhost:8000'),
    'timezone' => Env::get('APP_TIMEZONE', 'America/Lima'),
    'currency' => 'PEN',
    'currency_symbol' => 'S/ ',
];