<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Session;
use App\Core\Database;
use PDO;

class SettingsController
{
    public function index(Request $request): void
    {
        $settings = $this->getSettingsMap();

        View::render('admin.settings.index', [
            'title' => 'Configuración de la Boutique | J.A ADMIN',
            'settings' => $settings,
            'success' => Session::get('flash_success')
        ], 'admin');

        Session::remove('flash_success');
    }

    public function save(Request $request): void
    {
        $db = Database::getConnection();
        $fields = [
            'shipping_lima' => (string)$request->body('shipping_lima', '15.00'),
            'shipping_provincia' => (string)$request->body('shipping_provincia', '25.00'),
            'yape_phone' => (string)$request->body('yape_phone', '900000000'),
            'bcp_account' => (string)$request->body('bcp_account', '191-00000000-0-00'),
            'contact_whatsapp' => (string)$request->body('contact_whatsapp', '+51 984 000 000'),
            'contact_email' => (string)$request->body('contact_email', 'contacto@jacollection.com'),
            'store_announcement' => (string)$request->body('store_announcement', 'Ediciones especiales 1:64 con empaque reforzado.')
        ];

        $stmt = $db->prepare("INSERT INTO settings (key_name, value_text) VALUES (:k, :v) ON DUPLICATE KEY UPDATE value_text = :v2");
        foreach ($fields as $key => $val) {
            $stmt->execute(['k' => $key, 'v' => $val, 'v2' => $val]);
        }

        Session::set('flash_success', 'Configuraciones guardadas y aplicadas a toda la tienda.');
        Response::redirect('/admin/settings');
    }

    private function getSettingsMap(): array
    {
        $db = Database::getConnection();
        $stmt = $db->query("SELECT key_name, value_text FROM settings");
        $map = [];
        while ($row = $stmt->fetch()) {
            $map[$row['key_name']] = $row['value_text'];
        }

        return array_merge([
            'shipping_lima' => '15.00',
            'shipping_provincia' => '25.00',
            'yape_phone' => '900 000 000',
            'bcp_account' => '191-89412039-0-45 (J.A COLLECTION SAC)',
            'contact_whatsapp' => '+51 984 000 000',
            'contact_email' => 'contacto@jacollection.com',
            'store_announcement' => 'Boutique especializada en vehículos a escala diecast de alto nivel.'
        ], $map);
    }
}