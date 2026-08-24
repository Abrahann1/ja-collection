<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Session;
use App\Repositories\BrandRepository;

class BrandController
{
    private BrandRepository $brandRepo;

    public function __construct()
    {
        $this->brandRepo = new BrandRepository();
    }

    public function index(Request $request): void
    {
        $brands = $this->brandRepo->getAllAdmin();

        View::render('admin.brands.index', [
            'title' => 'Gestión de Marcas | J.A ADMIN',
            'brands' => $brands,
            'success' => Session::get('flash_success')
        ], 'admin');

        Session::remove('flash_success');
    }

    public function store(Request $request): void
    {
        $name = (string)$request->body('name');
        $desc = (string)$request->body('description', '');

        if (!empty(trim($name))) {
            $this->brandRepo->create($name, $desc);
            Session::set('flash_success', "Marca '{$name}' registrada exitosamente.");
        }

        Response::redirect('/admin/brands');
    }

    public function delete(Request $request, string $id): void
    {
        $this->brandRepo->delete((int)$id);
        Session::set('flash_success', 'Marca archivada del sistema.');
        Response::redirect('/admin/brands');
    }
}