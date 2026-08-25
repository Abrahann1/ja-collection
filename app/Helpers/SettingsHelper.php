<?php
declare(strict_types=1);

namespace App\Helpers;

use App\Core\Database;
use PDO;

class SettingsHelper
{
    private static mixed $settings = null;

    public static function all(): array
    {
        if (self::$settings === null) {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT key_name, value_text FROM settings");
            $map = [];
            while ($row = $stmt->fetch()) {
                $map[$row['key_name']] = $row['value_text'];
            }

            self::$settings = array_merge([
                'shipping_lima' => '15.00',
                'shipping_provincia' => '25.00',
                'yape_phone' => '900 000 000',
                'bcp_account' => '191-89412039-0-45 (J.A COLLECTION SAC)',
                'contact_whatsapp' => '+51 984 000 000',
                'contact_email' => 'contacto@jacollection.com',
                'store_announcement' => 'Boutique especializada en vehículos a escala diecast de alto nivel.'
            ], $map);
        }

        return self::$settings;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::all();
        return $all[$key] ?? $default;
    }
}