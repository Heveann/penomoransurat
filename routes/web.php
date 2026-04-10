<?php
// Route dengan nama 'admin.dashboard-admin' untuk dashboard admin


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Models\KodeKearsipan;
use App\Models\SuratKeluar;

Route::get('/', function () {
    return redirect()->route('login');
});


// Route dashboard user
Route::get('/user/dashboard-user', function () {
    $userId = Auth::user()->id;
    $now = now();
    $bulanIni = $now->month;
    $tahunIni = $now->year;
    $bulanLalu = $bulanIni == 1 ? 12 : $bulanIni - 1;
    $tahunLalu = $bulanIni == 1 ? $tahunIni - 1 : $tahunIni;

    // Total Request Nomor
    $totalRequest = \App\Models\SuratKeluar::where('user_id', $userId)->whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();
    $totalRequestLast = \App\Models\SuratKeluar::where('user_id', $userId)->whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();
    $percentRequest = $totalRequestLast > 0 ? (($totalRequest - $totalRequestLast) / $totalRequestLast) * 100 : ($totalRequest > 0 ? 100 : 0);

    // Total Klasifikasi
    $totalKlasifikasi = \App\Models\KodeKearsipan::whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();
    $totalKlasifikasiLast = \App\Models\KodeKearsipan::whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();
    $percentKlasifikasi = $totalKlasifikasiLast > 0 ? (($totalKlasifikasi - $totalKlasifikasiLast) / $totalKlasifikasiLast) * 100 : ($totalKlasifikasi > 0 ? 100 : 0);

    // Surat Keputusan
    $totalKeputusan = \App\Models\SuratKeluar::where('user_id', $userId)->where('status', 'disetujui')->where('jenis_naskah', 'Surat Keputusan')->whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();
    $totalKeputusanLast = \App\Models\SuratKeluar::where('user_id', $userId)->where('status', 'disetujui')->where('jenis_naskah', 'Surat Keputusan')->whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();
    $percentKeputusan = $totalKeputusanLast > 0 ? (($totalKeputusan - $totalKeputusanLast) / $totalKeputusanLast) * 100 : ($totalKeputusan > 0 ? 100 : 0);

    // Surat Keluar
    $totalKeluar = \App\Models\SuratKeluar::where('user_id', $userId)->where('status', 'disetujui')->where('jenis_naskah', 'Surat Keluar')->whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();
    $totalKeluarLast = \App\Models\SuratKeluar::where('user_id', $userId)->where('status', 'disetujui')->where('jenis_naskah', 'Surat Keluar')->whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();
    $percentKeluar = $totalKeluarLast > 0 ? (($totalKeluar - $totalKeluarLast) / $totalKeluarLast) * 100 : ($totalKeluar > 0 ? 100 : 0);

    // Untuk chart dan data lain (filter tahun ini)
    $requests = \App\Models\SuratKeluar::where('user_id', $userId)
        ->where('status', 'disetujui')
        ->whereYear('created_at', $tahunIni)
        ->get();

    return view('user.dashboard-user', compact(
        'requests',
        'totalRequest', 'percentRequest',
        'totalKlasifikasi', 'percentKlasifikasi',
        'totalKeputusan', 'percentKeputusan',
        'totalKeluar', 'percentKeluar'
    ));
})->middleware(['auth:web', 'verified'])->name('user.dashboard-user');

// Route dashboard admin/pimpinan
Route::get('/admin/dashboard-admin', function () {
    return redirect()->route('dashboard');
})->middleware(['auth', 'verified'])->name('admin.dashboard-admin');


// Route untuk user.create-user
Route::get('/user/create-user', function () {
    $kodeKearsipan = \App\Models\KodeKearsipan::all();
    $jenisNaskah = \App\Models\JenisNaskah::orderBy('nama')->get();
    $sifatNaskah = \App\Models\SifatNaskah::orderBy('nama')->get();
    $unitKerjas = \App\Models\UnitKerja::orderBy('nama')->get();
    return view('user.create-user', compact('kodeKearsipan', 'jenisNaskah', 'sifatNaskah', 'unitKerjas'));
})->middleware(['auth', 'verified'])->name('user.create-user');

Route::get('/user/daftar-nomor', [\App\Http\Controllers\UserController::class, 'daftarNomor'])->middleware(['auth:web', 'verified'])->name('user.daftar-nomor');


// Route ekspor Excel & PDF daftar nomor surat user
use App\Http\Controllers\UserController;

// Route ekspor Excel & PDF daftar nomor surat user
Route::get('/user/daftar-nomor/excel', [UserController::class, 'exportExcelDaftarNomor'])->middleware(['auth:web', 'verified'])->name('user.daftar-nomor.excel');
Route::get('/user/daftar-nomor/cetak', [UserController::class, 'cetakPdfDaftarNomor'])->middleware(['auth:web', 'verified'])->name('user.daftar-nomor.cetak');


// Dashboard utama berdasarkan role
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if ($user->role === 'admin' || $user->role === 'pimpinan') {
        return view('admin.dashboard-admin');
    }

    // Role: user biasa
    $userId = $user->id;
    $now = now();
    $bulanIni = $now->month;
    $tahunIni = $now->year;
    $bulanLalu = $bulanIni == 1 ? 12 : $bulanIni - 1;
    $tahunLalu = $bulanIni == 1 ? $tahunIni - 1 : $tahunIni;

    // Total Request Nomor
    $totalRequest = \App\Models\SuratKeluar::where('user_id', $userId)->whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();
    $totalRequestLast = \App\Models\SuratKeluar::where('user_id', $userId)->whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();
    $percentRequest = $totalRequestLast > 0 ? (($totalRequest - $totalRequestLast) / $totalRequestLast) * 100 : ($totalRequest > 0 ? 100 : 0);

    // Total Klasifikasi
    $totalKlasifikasi = \App\Models\KodeKearsipan::whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();
    $totalKlasifikasiLast = \App\Models\KodeKearsipan::whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();
    $percentKlasifikasi = $totalKlasifikasiLast > 0 ? (($totalKlasifikasi - $totalKlasifikasiLast) / $totalKlasifikasiLast) * 100 : ($totalKlasifikasi > 0 ? 100 : 0);

    // Surat Keputusan
    $totalKeputusan = \App\Models\SuratKeluar::where('user_id', $userId)->where('status', 'disetujui')->where('jenis_naskah', 'Surat Keputusan')->whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();
    $totalKeputusanLast = \App\Models\SuratKeluar::where('user_id', $userId)->where('status', 'disetujui')->where('jenis_naskah', 'Surat Keputusan')->whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();
    $percentKeputusan = $totalKeputusanLast > 0 ? (($totalKeputusan - $totalKeputusanLast) / $totalKeputusanLast) * 100 : ($totalKeputusan > 0 ? 100 : 0);

    // Surat Keluar
    $totalKeluar = \App\Models\SuratKeluar::where('user_id', $userId)->where('status', 'disetujui')->where('jenis_naskah', 'Surat Keluar')->whereMonth('created_at', $bulanIni)->whereYear('created_at', $tahunIni)->count();
    $totalKeluarLast = \App\Models\SuratKeluar::where('user_id', $userId)->where('status', 'disetujui')->where('jenis_naskah', 'Surat Keluar')->whereMonth('created_at', $bulanLalu)->whereYear('created_at', $tahunLalu)->count();
    $percentKeluar = $totalKeluarLast > 0 ? (($totalKeluar - $totalKeluarLast) / $totalKeluarLast) * 100 : ($totalKeluar > 0 ? 100 : 0);

    // Untuk chart dan data lain (filter tahun ini)
    $requests = \App\Models\SuratKeluar::where('user_id', $userId)
        ->where('status', 'disetujui')
        ->whereYear('created_at', $tahunIni)
        ->get();

    return view('user.dashboard-user', compact(
        'requests',
        'totalRequest', 'percentRequest',
        'totalKlasifikasi', 'percentKlasifikasi',
        'totalKeputusan', 'percentKeputusan',
        'totalKeluar', 'percentKeluar'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');


use App\Http\Controllers\SuratKeluarController;

use App\Http\Controllers\KlasifikasiController;
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/avatar', [ProfileController::class, 'avatar'])->name('profile.avatar');

    // SuratKeluar resource routes
    Route::get('/monitoring', [SuratKeluarController::class, 'monitoring'])->name('monitoring.index');
    Route::resource('surat', SuratKeluarController::class);
        // Cetak PDF per nomor surat
        Route::get('surat/{surat}/cetak', [SuratKeluarController::class, 'cetakPdf'])->name('surat.cetak');
        Route::patch('surat/{surat}/approve', [SuratKeluarController::class, 'approve'])->name('surat.approve');
        Route::patch('surat/{surat}/reject', [SuratKeluarController::class, 'reject'])->name('surat.reject');
        Route::patch('surat/{surat}/revisi', [SuratKeluarController::class, 'revisi'])->name('surat.revisi');



    // Klasifikasi resource routes
    Route::resource('klasifikasi', KlasifikasiController::class);
    
    // Jenis Naskah routes
    Route::post('jenis-naskah', [KlasifikasiController::class, 'storeJenis'])->name('jenis-naskah.store');
    Route::put('jenis-naskah/{id}', [KlasifikasiController::class, 'updateJenis'])->name('jenis-naskah.update');
    Route::delete('jenis-naskah/{id}', [KlasifikasiController::class, 'destroyJenis'])->name('jenis-naskah.destroy');

    // Sifat Naskah routes
    Route::post('sifat-naskah', [KlasifikasiController::class, 'storeSifat'])->name('sifat-naskah.store');
    Route::put('sifat-naskah/{id}', [KlasifikasiController::class, 'updateSifat'])->name('sifat-naskah.update');
    Route::delete('sifat-naskah/{id}', [KlasifikasiController::class, 'destroySifat'])->name('sifat-naskah.destroy');
    
    // Unit Kerja routes
    Route::resource('unit-kerja', \App\Http\Controllers\UnitKerjaController::class);

    // User resource routes
    Route::resource('users', UserController::class);
});

// Route edit dan destroy untuk daftar nomor user
Route::get('/user/daftar-nomor/{id}/edit', [UserController::class, 'editDaftarNomor'])->middleware(['auth', 'verified'])->name('user.daftar-nomor.edit');
Route::put('/user/daftar-nomor/{id}', [UserController::class, 'updateDaftarNomor'])->middleware(['auth', 'verified'])->name('user.daftar-nomor.update');
Route::delete('/user/daftar-nomor/{id}', [UserController::class, 'destroyDaftarNomor'])->middleware(['auth', 'verified'])->name('user.daftar-nomor.destroy');

require __DIR__.'/auth.php';
require __DIR__.'/grafik-user.php';
