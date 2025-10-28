<?php

use App\Mail\testMail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PengolahController;
use App\Http\Controllers\DirektoriController;
use App\Http\Controllers\MicroDataController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\PetugasPSTController;
use App\Http\Controllers\PemilikDataController;
use App\Http\Controllers\CategoryDataController;
use App\Http\Controllers\TabelDinamisController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\PermintaanDataController;
use App\Http\Controllers\SiagaTabelDataController;
use App\Http\Controllers\VerifikasiHasilController;
use App\Http\Controllers\Admin\FooterLinkController;
use App\Http\Controllers\PerantaraPermintaanController;
use App\Http\Controllers\PermintaanDataRutinController;
// ==================== Public Routes ====================
Route::get('/welcome', function () {
    return view('welcome');
});

Route::put('/user/password', [PasswordController::class, 'update'])->name('password.update');

Route::get('/', [PemilikDataController::class, 'index'])->name('kunjungan.index');
Route::post('/kunjungan/cari', [PemilikDataController::class, 'cari'])->name('kunjungan.cari');
Route::get('/tabel-dinamis/portal', [TabelDinamisController::class, 'portalIndex'])->name('tabel-dinamis.portal');
Route::get('/tabel-dinamis/portal/data', [TabelDinamisController::class, 'getPortalData'])->name('tabel-dinamis.portal.data');
Route::get('/portal/siaga-data', [SiagaTabelDataController::class, 'portalData'])->name('siaga.portal.data');
Route::get('/data-mikro-publik', [MicroDataController::class, 'publicIndex'])->name('data-mikro.public.index');
Route::get('/data-mikro-publik/{id}', [MicroDataController::class, 'publicShow'])->name('data-mikro.public.show');

// ==================== Authenticated User Routes ====================
Route::middleware('auth')->group(function () {
    // Notifications
    Route::post('/notifikasi/baca-semua', function () {
        auth::user()->unreadNotifications->markAsRead();
        return response()->json(['status' => 'ok']);
    });

    Route::get('/profileh', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/name', [ProfileController::class, 'updateName'])->name('profile.update.name');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
});

// ==================== Admin Routes ====================
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard & Analytics
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/chart-user-role', [AdminController::class, 'userRoleChart'])->name('chart.user.role');
    Route::get('/admin/log-aktivitas', [AdminController::class, 'logActivity'])->name('admin.log-aktivitas');

    // User Management
    Route::get('/admin/user-management', [AdminController::class, 'userManagement'])->name('show.admin.user');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'edit'])->name('edit.user');
    Route::put('/admin/users/{id}', [AdminController::class, 'update'])->name('update.user');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy'])->name('delete.user');
    Route::get('/admin/user', [AdminController::class, 'userManagement'])->name('show.admin.user');
    Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [AdminController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [AdminController::class, 'destroy'])->name('users.destroy');
    Route::get('/tambah-user', [AdminController::class, 'create'])->name('tambah.user');
    Route::post('/tambah-user', [AdminController::class, 'store']);

    //sidebar content 
    Route::prefix('admin/links')->name('admin.links.')->group(function () {
        // Link CRUD
        Route::prefix('link')->name('link.')->group(function () {
            Route::post('/store', [LinkController::class, 'store'])->name('store');
            Route::post('/{link}/update', [LinkController::class, 'update'])->name('update');
            Route::delete('/{link}/delete', [LinkController::class, 'destroy'])->name('destroy');
            Route::post('/{link}/up', [LinkController::class, 'moveLinkUp'])->name('up');
            Route::post('/{link}/down', [LinkController::class, 'moveLinkDown'])->name('down');
        });

        // Group CRUD
        Route::prefix('group')->name('group.')->group(function () {
            Route::post('/store', [LinkController::class, 'storeGroup'])->name('store');
            Route::post('/{group}/update', [LinkController::class, 'updateGroup'])->name('update');
            Route::delete('/{group}/delete', [LinkController::class, 'destroyGroup'])->name('destroy');
            Route::post('/{group}/up', [LinkController::class, 'moveGroupUp'])->name('up');
            Route::post('/{group}/down', [LinkController::class, 'moveGroupDown'])->name('down');
        });

        // Banner CRUD
        Route::prefix('banner')->name('banner.')->group(function () {
            Route::post('/store', [LinkController::class, 'storeBanner'])->name('store');
            Route::post('/{banner}/update', [LinkController::class, 'updateBanner'])->name('update');
            Route::delete('/{banner}/delete', [LinkController::class, 'destroyBanner'])->name('destroy');
            Route::post('/{banner}/up', [LinkController::class, 'moveBannerUp'])->name('up');
            Route::post('/{banner}/down', [LinkController::class, 'moveBannerDown'])->name('down');
        });

        // Index page
        Route::get('/', [LinkController::class, 'index'])->name('index');
    });
    // footer content
    Route::prefix('admin/footer-links')->name('admin.footer_links.')->group(function () {
        // Footer Link CRUD
        Route::prefix('link')->name('link.')->group(function () {
            Route::post('/store', [FooterLinkController::class, 'store'])->name('store');
            Route::post('/{link}/update', [FooterLinkController::class, 'update'])->name('update');
            Route::delete('/{link}/delete', [FooterLinkController::class, 'destroy'])->name('destroy');
            Route::post('/{link}/up', [FooterLinkController::class, 'moveLinkUp'])->name('up');
            Route::post('/{link}/down', [FooterLinkController::class, 'moveLinkDown'])->name('down');
        });

        // Footer Link Group CRUD
        Route::prefix('group')->name('group.')->group(function () {
            Route::post('/store', [FooterLinkController::class, 'storeGroup'])->name('store');
            Route::post('/{group}/update', [FooterLinkController::class, 'updateGroup'])->name('update');
            Route::delete('/{group}/delete', [FooterLinkController::class, 'destroyGroup'])->name('destroy');
            Route::post('/{group}/up', [FooterLinkController::class, 'moveGroupUp'])->name('up');
            Route::post('/{group}/down', [FooterLinkController::class, 'moveGroupDown'])->name('down');
        });

        // Index page
        Route::get('/', [FooterLinkController::class, 'index'])->name('index');
    });
    // Category & Subject Management
    Route::resource('/categories', CategoryDataController::class)->except(['show']);
    Route::post('/categories/{category}/subject', [CategoryDataController::class, 'storeSubject'])->name('subject.store');
    Route::delete('/categories/subject/{id}', [CategoryDataController::class, 'destroySubject'])->name('subject.destroy');
    Route::put('/categories/subject/{id}', [CategoryDataController::class, 'updateSubject'])->name('subject.update');

    // Perantara Management
    Route::get('/perantara', [PerantaraPermintaanController::class, 'index'])->name('perantara.index');
    Route::post('/perantara', [PerantaraPermintaanController::class, 'store'])->name('perantara.store');
    Route::put('/perantara/{id}', [PerantaraPermintaanController::class, 'update'])->name('perantara.update');
    Route::delete('/perantara/{id}', [PerantaraPermintaanController::class, 'destroy'])->name('perantara.destroy');
});

// ==================== Petugas PST Routes ====================
Route::middleware(['auth', 'role:petugas_pst'])->group(function () {
    // Dashboard
    Route::get('/petugas-pst', [PetugasPSTController::class, 'index'])->name('dashboard2');

    // Permintaan Olah Data Management
    Route::get('/permintaan-olah-data', [PermintaanDataController::class, 'create'])->name('permintaanolahdata.form');
    Route::post('/permintaan-olah-data', [PermintaanDataController::class, 'store'])->name('permintaanolahdata.store');
    Route::get('/Penugasan-permintaan-olah-data', [PermintaanDataController::class, 'index'])->name('permintaanolahdata.tugas');
    Route::post('/permintaan-data/{id}/batal-penugasan', [PermintaanDataController::class, 'batalPenugasan'])->name('permintaan.batalPenugasan');
    Route::get('/permintaanolahdata/{id}', [PermintaanDataController::class, 'show'])->name('permintaanolahdata.show');
    Route::get('/status-permintaan-olah-data', [PermintaanDataController::class, 'statusData'])->name('permintaanolahdata.status');
    Route::get('/data/status-permintaan', [PermintaanDataController::class, 'getStatusData'])->name('permintaan.getStatusData');
    Route::get('/permintaan/data', [PermintaanDataController::class, 'getData'])->name('permintaan.getData');
    Route::get('/permintaan/{id}', [PermintaanDataController::class, 'show'])->name('permintaan.show');
    Route::post('/permintaan/penugasan', [PermintaanDataController::class, 'simpanPenugasan'])->name('permintaan.penugasan');
    Route::get('/permintaan/{id}/edit', [PermintaanDataController::class, 'edit'])->name('permintaan.edit');
    Route::put('/permintaan/{id}', [PermintaanDataController::class, 'update'])->name('permintaan.update');

    // Verifikasi Hasil
    Route::get('/verifikasi/{id}', [VerifikasiHasilController::class, 'form'])->name('verifikasi.form');
    Route::post('/verifikasi/{id}', [VerifikasiHasilController::class, 'simpan'])->name('verifikasi.store');

    // Pengolah Assignment
    Route::post('/tugaskan-pengolah', [PetugasPSTController::class, 'tugaskanPengolah'])->name('tugaskan.pengolah');
    Route::get('/get-permintaan/{id}', [PetugasPSTController::class, 'getPermintaan']);

    // Tabel Dinamis/tabel statistik Management
    Route::get('/tabel-dinamis/create', [TabelDinamisController::class, 'create'])->name('tabel-dinamis.create');
    Route::post('/tabel-dinamis/store', [TabelDinamisController::class, 'store'])->name('tabel-dinamis.store');
    Route::get('/penugasan-tabel-dinamis', [TabelDinamisController::class, 'penugasanIndex'])->name('tabel-dinamis.penugasan');
    Route::post('/get-subjects', [TabelDinamisController::class, 'getSubjectsByKategori'])->name('ajax.get-subjects');
    Route::get('/tabel-dinamis/{id}/edit', [TabelDinamisController::class, 'edit'])->name('tabel-dinamis.edit');
    Route::put('/tabel-dinamis/{id}', [TabelDinamisController::class, 'update'])->name('tabel-dinamis.update');
    Route::get('/penugasan-tabel-dinamis/data', [TabelDinamisController::class, 'getPenugasanData'])->name('tabel-dinamis.penugasan.data');
    Route::post('/penugasan-tabel-dinamis/assign/{id}', [TabelDinamisController::class, 'assignPengolah'])->name('tabel-dinamis.assign');
    Route::get('/status-tabel-dinamis', [TabelDinamisController::class, 'halamanStatus'])->name('tabel-dinamis.status');
    Route::get('/status-tabel-dinamis/data', [TabelDinamisController::class, 'getStatusData'])->name('tabel-dinamis.status.data');
    Route::post('/tabel-dinamis/publish/{id}', [TabelDinamisController::class, 'publish'])->name('tabel-dinamis.publish');
    Route::post('/tabel-dinamis/{id}/edit-link-publish', [TabelDinamisController::class, 'updateLinkPublish'])->name('tabel-dinamis.update-link-publish');
    Route::post('/tabel-dinamis/{id}/batalkan', [TabelDinamisController::class, 'batalkanPenugasan'])->name('tabel-dinamis.batalkan');
    Route::get('/tabel-dinamis/{id}', [TabelDinamisController::class, 'show'])->where('id', '[0-9]+')->name('tabel-dinamis.show');

    // Tabel Dinamis/tabel statistik Verifikasi
    Route::get('/verifikasi-hasil/{id}', [TabelDinamisController::class, 'formVerifikasi'])->name('tabel-dinamis.verifikasi.form');
    Route::post('/verifikasi-hasil/{id}', [TabelDinamisController::class, 'simpanVerifikasi'])->name('tabel-dinamis.verifikasi.simpan');

    // Siaga Tabel/Tabel Publikasi Management
    Route::get('/siaga/pst/penugasan', [SiagaTabelDataController::class, 'halamanPst'])->name('siaga.pst.penugasan');
    Route::get('/siaga/pst/data', [SiagaTabelDataController::class, 'dataUntukPst'])->name('siaga.pst.data');
    Route::post('/siaga-tabel/upload-link', [SiagaTabelDataController::class, 'uploadLink'])->name('siaga.pst.upload');
    Route::get('/siaga-tabel/detail/{judul}', [SiagaTabelDataController::class, 'getDetail']);


    // --- Data Mikro ---
    Route::get('/data-mikro', [MicroDataController::class, 'index'])->name('data-mikro.index');
    Route::get('/data-mikro/create', [MicroDataController::class, 'create'])->name('data-mikro.create');
    Route::post('/data-mikro', [MicroDataController::class, 'store'])->name('data-mikro.store');
    Route::get('/data-mikro/{micro_data}/edit', [MicroDataController::class, 'edit'])->name('data-mikro.edit');
    Route::put('/data-mikro/{micro_data}', [MicroDataController::class, 'update'])->name('data-mikro.update');
    Route::delete('/data-mikro/{micro_data}', [MicroDataController::class, 'destroy'])->name('data-mikro.destroy');
    Route::get('/data-mikro/{id}', [MicroDataController::class, 'show'])->name('data-mikro.show');

    // --- Dataset (Items) ---
    Route::get('/data-mikro/dataset', [MicroDataController::class, 'items'])->name('data-mikro.dataset.index');
    Route::get('/data-mikro/{id}/dataset/create', [MicroDataController::class, 'createItem'])->name('data-mikro.dataset.create');
    Route::post('/data-mikro/{id}/dataset', [MicroDataController::class, 'storeItem'])->name('data-mikro.dataset.store');
    Route::get('/data-mikro/{id}/dataset/{itemId}/edit', [MicroDataController::class, 'editItem'])->name('data-mikro.dataset.edit');
    Route::put('/data-mikro/{id}/dataset/{itemId}', [MicroDataController::class, 'updateItem'])->name('data-mikro.dataset.update');
    Route::delete('/data-mikro/{id}/dataset/{itemId}', [MicroDataController::class, 'destroyItem'])->name('data-mikro.dataset.destroy');
});

// ==================== Pengolah Data Routes ====================
Route::middleware(['auth', 'role:pengolah_data'])->group(function () {
    // Dashboard
    Route::get('/pengolah/dashboard', [PengolahController::class, 'dashboard'])->name('pengolah.dashboard');

    // Permintaan Data Management
    Route::get('/pengolah/permintaan', [PengolahController::class, 'index'])->name('pengolah.index');
    Route::get('/pengolah/permintaan/data', [PengolahController::class, 'getData'])->name('pengolah.permintaan.data');
    Route::post('/pengolah/permintaan/{id}/apply', [PengolahController::class, 'apply'])->name('pengolah.permintaan.apply');
    Route::post('/pengolah/reject/{id}', [PengolahController::class, 'reject'])->name('pengolah.permintaan.reject');

    // Upload Data Management
    Route::get('/pengolah/upload-data', [UploadController::class, 'uploadView'])->name('pengolah.upload.view');
    Route::post('/pengolah/upload/{id}', [UploadController::class, 'upload'])->name('pengolah.permintaan.upload');
    Route::get('/pengolah/upload-data/json', [UploadController::class, 'getUploadData'])->name('pengolah.upload.data');


    // Tabel Dinamis/tabel statistik Management
    Route::get('/tabel-dinamis', [TabelDinamisController::class, 'tabelDinamisIndex'])->name('tabeldinamis');
    Route::get('/tabel-dinamis/data', [TabelDinamisController::class, 'getTabelDinamis'])->name('tabeldinamis.data');
    Route::post('/tabel-dinamis/apply/{id}', [TabelDinamisController::class, 'applyTabel'])->name('tabeldinamis.apply');
    Route::post('/tabel-dinamis/{id}/tolak', [TabelDinamisController::class, 'tolakTabel'])->name('tabel-dinamis.tolak');
    Route::get('/tabel-dinamis/upload', [TabelDinamisController::class, 'uploadPage'])->name('tabeldinamis.upload.page');
    Route::get('/tabel-dinamis/upload/data', [TabelDinamisController::class, 'getUploadData'])->name('tabeldinamis.upload.data');
    Route::post('/tabel-dinamis/upload-link/{id}', [TabelDinamisController::class, 'uploadLink'])->name('tabeldinamis.upload.link');

    // Siaga Tabel/Tabel Publikasi Management
    Route::get('/siaga/import-data', [SiagaTabelDataController::class, 'showImportForm'])->name('siaga.import.form');
    Route::post('/import', [SiagaTabelDataController::class, 'handleImport'])->name('siaga.import.handle');
    Route::post('/import/simpan', [SiagaTabelDataController::class, 'simpanFinal'])->name('siaga.import.simpan');
    Route::get('/siaga/penugasan', [SiagaTabelDataController::class, 'halamanPenugasan'])->name('siaga.penugasan');
    Route::post('/siaga/penugasan/simpan', [SiagaTabelDataController::class, 'prosesPenugasanPublikasi'])->name('siaga.penugasan.publikasi');
    Route::get('/siaga-tabel/detail/{judul}', [SiagaTabelDataController::class, 'getDetail2']);
    Route::post('/penugasan/batalkan', [SiagaTabelDataController::class, 'batalkanPenugasan'])->name('penugasan.batal');
});

//Multi-Role Routes (Admin, Pengolah Data, Petugas PST) 
Route::middleware(['auth', 'role:admin|pengolah_data|petugas_pst'])->group(function () {
    // Direktori Data Management
    Route::get('/direktori-data', [DirektoriController::class, 'direktoriView'])->name('pengolah.direktori.view');
    Route::get('/direktori-data/list', [DirektoriController::class, 'getDirektoriData'])->name('pengolah.direktori.data');
    Route::get('/direktori-data/download/{id}', [DirektoriController::class, 'download'])->name('pengolah.direktori.download');
    // Direktori Backup
    Route::get('/direktori/backup/{year}/{month}', [DirektoriController::class, 'backupByMonth'])->name('direktori.backup.byMonth');

    // Backup Management
    Route::get('/direktori/backup/{year}/{month}', [DirektoriController::class, 'backupByMonth'])->name('direktori.backup.byMonth');
    Route::get('/direktori/backup-months', [DirektoriController::class, 'getAvailableBackupMonths'])->name('direktori.backup.months');
    Route::post('/direktori/hapus-per-bulan', [DirektoriController::class, 'hapusBackupDanOriginalByMonth'])->name('direktori.hapus.bulanan');
});

// ==================== Authentication Routes ====================
require __DIR__ . '/auth.php';
