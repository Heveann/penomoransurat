<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\SuratKeluar;

Route::get('/user/grafik', function () {
    $userId = Auth::user()->id;
    $monthlyCounts = SuratKeluar::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->where('user_id', $userId)
        ->whereYear('created_at', date('Y'))
        ->groupBy('month')
        ->orderBy('month')
        ->pluck('total', 'month');

    $data = [];
    for ($i = 1; $i <= 12; $i++) {
        $data[] = $monthlyCounts[$i] ?? 0;
    }
    return view('user.grafik', ['monthlySurat' => $data]);
})->middleware(['auth:web', 'verified'])->name('user.grafik');
