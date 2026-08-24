<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Session;
use App\Services\ExcelImportService;

class ImportController
{
    private ExcelImportService $importService;

    public function __construct()
    {
        $this->importService = new ExcelImportService();
    }

    public function index(Request $request): void
    {
        View::render('admin.import.index', [
            'title' => 'Importación Masiva de Productos | J.A ADMIN',
            'preview' => Session::get('import_preview'),
            'success' => Session::get('flash_success'),
            'error' => Session::get('flash_error')
        ], 'admin');

        Session::remove('flash_success');
        Session::remove('flash_error');
    }

    public function upload(Request $request): void
    {
        if (empty($_FILES['excel_file']['tmp_name']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
            Session::set('flash_error', 'Debe seleccionar un archivo válido (.xlsx o .csv).');
            Response::redirect('/admin/import');
        }

        $tmpFile = $_FILES['excel_file']['tmp_name'];
        $fileName = $_FILES['excel_file']['name'];
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['xlsx', 'csv', 'xls'], true)) {
            Session::set('flash_error', 'Formato no permitido. Solo se aceptan archivos .xlsx o .csv');
            Response::redirect('/admin/import');
        }

        $result = $this->importService->parseUploadedFile($tmpFile);

        Session::set('import_preview', $result);
        Response::redirect('/admin/import');
    }

    public function confirm(Request $request): void
    {
        $preview = Session::get('import_preview');

        if (empty($preview['valid'])) {
            Session::set('flash_error', 'No hay registros válidos para importar.');
            Response::redirect('/admin/import');
        }

        $user = Session::get('user');
        $userId = $user ? (int)$user['id'] : null;

        $res = $this->importService->executeImport($preview['valid'], $userId);

        Session::remove('import_preview');

        if ($res['success']) {
            Session::set('flash_success', "¡Éxito! Se importaron {$res['count']} modelos al catálogo y al inventario.");
        } else {
            Session::set('flash_error', $res['message']);
        }

        Response::redirect('/admin/products');
    }

    public function cancel(Request $request): void
    {
        Session::remove('import_preview');
        Session::set('flash_success', 'Importación cancelada.');
        Response::redirect('/admin/import');
    }

    public function downloadTemplate(Request $request): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="plantilla_importacion_ja_collection.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['sku', 'name', 'brand', 'category', 'scale', 'model', 'description', 'price', 'old_price', 'stock', 'minimum_stock', 'featured', 'new']);
        
        // Filas de ejemplo de alta gama
        fputcsv($output, ['HW-SUPRA-010', 'Toyota GR Supra (A90)', 'Hot Wheels', 'JDM Specials', '1:64', 'Supra A90', 'Edicion especial con neumaticos de goma Real Riders', '45.00', '55.00', '10', '2', '1', '1']);
        fputcsv($output, ['MGT-LBWK-011', 'Nissan GT-R R35 LBWK Silhouette', 'Mini GT', 'Supercars & Hypercars', '1:64', 'GT-R R35', 'Librea oficial Liberty Walk alta fidelidad', '68.00', '', '6', '2', '1', '1']);
        fputcsv($output, ['MBX-FORD-012', '1968 Ford Mustang GT', 'Matchbox', 'American Muscle', '1:64', 'Mustang GT', 'Clasico americano con pintura azul metalizada', '35.00', '', '15', '3', '0', '0']);
        
        fclose($output);
        exit;
    }
}