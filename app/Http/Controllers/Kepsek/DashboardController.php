<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Models\Pengaturan;
use Illuminate\View\View;

class DashboardController extends AdminDashboardController
{
    public function index(): View
    {
        $data = parent::index()->getData();

        return view('kepsek.dashboard', array_merge($data, [
            'pengaturan' => Pengaturan::aktif(),
        ]));
    }
}