<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Core\Session;
use App\Repositories\CategoryRepository;

class CategoryController
{
    private CategoryRepository $categoryRepo;

    public function __construct()
    {
        $this->categoryRepo = new CategoryRepository();
    }

    public function index(Request $request): void
    {
        $categories = $this->categoryRepo->getAllAdmin();

        View::render('admin.categories.index', [
            'title' => 'Gestión de Categorías | J.A ADMIN',
            'categories' => $categories,
            'success' => Session::get('flash_success')
        ], 'admin');

        Session::remove('flash_success');
    }

    public function store(Request $request): void
    {
        $name = (string)$request->body('name');
        $desc = (string)$request->body('description', '');

        if (!empty(trim($name))) {
            $this->categoryRepo->create($name, $desc);
            Session::set('flash_success', "Categoría '{$name}' creada exitosamente.");
        }

        Response::redirect('/admin/categories');
    }

    public function delete(Request $request, string $id): void
    {
        $this->categoryRepo->delete((int)$id);
        Session::set('flash_success', 'Categoría archivada del sistema.');
        Response::redirect('/admin/categories');
    }
}