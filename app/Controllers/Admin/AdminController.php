<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Request;
use App\Core\Response;
use App\Core\View;
use App\Services\DashboardService;

class AdminController
{
    private DashboardService $dashService;

    public function __construct()
    {
        $this->dashService = new DashboardService();
    }

    public function dashboard(Request $request): void
    {
        $metrics = $this->dashService->getDashboardMetrics();

        View::render('admin.dashboard', [
            'title' => 'Dashboard Administrativo | J.A COLLECTION',
            'metrics' => $metrics
        ], 'admin');
    }

    public function liveStats(Request $request): void
    {
        $metrics = $this->dashService->getDashboardMetrics();
        Response::json([
            'success' => true,
            'data' => $metrics
        ]);
    }
}