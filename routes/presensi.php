<?php

use App\Http\Middleware\UserSession;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('page.dashboard.index');
//});

Route::namespace('App\Http\Controllers\Presensi')->group(function () {
    Route::prefix('presensi')->name('presensi.')->group(function () {


     Route::get('/', 'LoginController@index')->name('login');
        Route::post('/validasi', 'LoginController@validasi')->name('login.validasi');


        Route::get('/', 'LoginController@index');
        Route::get('/login', 'LoginController@index')->name('login');
        Route::post('/validasi', 'LoginController@validasi')->name('login.validasi');
        Route::post('/google-login', 'DashboardController@googleLogin')->name('google.login');
        Route::get('/logout', 'LoginController@logout')->name('logout');
        Route::get('/redirect', 'GoogleAuthController@redirect')->name('auth.google.redirect');
        Route::get('/callback', 'GoogleAuthController@callback')->name('auth.google.callback');
        Route::get('/imagenull.png', 'DashboardController@imagenull');
        Route::get("/sso", 'LoginController@sso');
        Route::post("/token", 'LoginController@token')->withoutMiddleware([VerifyCsrfToken::class]);
        Route::get("/userinfo", 'LoginController@userinfo');
        Route::middleware(UserSession::class)->group(function () {

            Route::get('/dashboard', function(){
                return redirect("https://presensi.sertcoquality.com/sso/signin");
            })->name('dashboard');


            Route::post("/token", 'LoginController@token')->withoutMiddleware([VerifyCsrfToken::class]);

            Route::get('/dashboard/data', 'DashboardController@getData')->name('dashboard.data');
            Route::prefix('master')->group(function () {
                //            Data Karyawan
                Route::resource('karyawan', 'KaryawanController')->names([
                    'index' => 'master.karyawan.index',
                    'create' => 'master.karyawan.create',
                    'store' => 'master.karyawan.store',
                    'show' => 'master.karyawan.show',
                    'edit' => 'master.karyawan.edit',
                    'update' => 'master.karyawan.update',
                    'destroy' => 'master.karyawan.destroy',
                ]);
                Route::get('karyawan-data', 'KaryawanController@data')->name('master.karyawan.data');
                Route::get('karyawan-jabatan-get', 'KaryawanController@getJabatan')->name('master.karyawan.jabatan.get');
                Route::get('karyawan-departemen-get', 'KaryawanController@getDepartemen')->name('master.karyawan.departemen.get');
                Route::get('karyawan-kantor-get', 'KaryawanController@getKantor')->name('master.karyawan.kantor.get');



                //            Jenis Kerja
                Route::get('jenis-kerja', 'JenisKerjaController@index')->name('master.jenis-kerja');
                Route::get('jenis-kerja-get', 'JenisKerjaController@data')->name('master.jenis-kerja.get');
                Route::post('jenis-kerja-store', 'JenisKerjaController@store')->name('master.jenis-kerja.store');
                Route::delete('jenis-kerja-delete/{id}', 'JenisKerjaController@destroy')->name('master.jenis-kerja.delete');
                Route::get('jenis-kerja-edit/{id}', 'JenisKerjaController@edit')->name('master.jenis-kerja.edit');
                Route::put('jenis-kerja-update/{id}', 'JenisKerjaController@update')->name('master.jenis-kerja.update');

                //            Role
                Route::get('role', 'RoleController@index')->name('master.role');
                Route::get('role-data', 'RoleController@data')->name('master.role.data');
                Route::post('role-store', 'RoleController@store')->name('master.role.store');
                Route::delete('role-delete/{id}', 'RoleController@delete')->name('master.role.delete');
                Route::get('role-edit/{id}', 'RoleController@edit')->name('master.role.edit');
                Route::put('role-update/{id}', 'RoleController@update')->name('master.role.update');
                Route::post('role-update/{id}', 'RoleController@update');

                //            Departemen
                Route::get('departemen', 'DepartemenController@index')->name('master.departemen');
                Route::get('departemen-get', 'DepartemenController@data')->name('master.departemen.get');
                Route::post('departemen-store', 'DepartemenController@store')->name('master.departemen.store');
                Route::delete('departemen-delete/{id}', 'DepartemenController@delete')->name('master.departemen.delete');
                Route::get('departemen-edit/{id}', 'DepartemenController@edit')->name('master.departemen.edit');
                Route::put('departemen-update/{id}', 'DepartemenController@update')->name('master.departemen.update');

                //            Kantor
                Route::get('kantor', 'KantorController@index')->name('master.kantor');
                Route::get('kantor-get', 'KantorController@getData')->name('master.kantor.get');
                Route::post('kantor-store', 'KantorController@store')->name('master.kantor.store');
                Route::delete('kantor-delete/{id}', 'KantorController@destroy')->name('master.kantor.delete');
                Route::get('kantor-edit/{id}', 'KantorController@getEdit')->name('master.kantor.edit');
                Route::put('kantor-update/{id}', 'KantorController@update')->name('master.kantor.update');
                Route::get('kantor-all', 'KantorController@getAllKantorForMap')->name('master.kantor.all');

                //            Shift Kerja
                Route::get('shift-kerja', 'ShiftKerjaController@index')->name('master.shift-kerja');
                Route::get('shift-kerja/get', 'ShiftKerjaController@getShiftKerja')->name('master.shift-kerja.get');
                Route::get('shift-kerja/getlist', 'ShiftKerjaController@shiftKerja')->name('master.shift-kerja.getlist');
                Route::get('shift-kerja/data', 'ShiftKerjaController@getData')->name('master.shift-kerja.data');
                Route::post('shift-kerja/store', 'ShiftKerjaController@store')->name('master.shift-kerja.store');
                Route::get('shift-kerja/edit/{id}', 'ShiftKerjaController@edit')->name('master.shift-kerja.edit');
                Route::put('shift-kerja/update/{id}', 'ShiftKerjaController@update')->name('master.shift-kerja.update');
                Route::delete('shift-kerja/delete/{id}', 'ShiftKerjaController@destroy')->name('master.shift-kerja.delete');


                //            Pengguna
                Route::get('pengguna', 'PenggunaController@index')->name('master.pengguna');
                Route::get('pengguna-get', 'PenggunaController@data')->name('master.pengguna.get');
                Route::post('pengguna-store', 'PenggunaController@store')->name('master.pengguna.store');
                Route::delete('pengguna-delete/{id}', 'PenggunaController@delete')->name('master.pengguna.delete');
                Route::get('pengguna-edit/{id}', 'PenggunaController@edit')->name('master.pengguna.edit');
                Route::put('pengguna-update/{id}', 'PenggunaController@update')->name('master.pengguna.update');


            });

            Route::prefix('jadwal')->group(function () {
                //            Jadwal Kerja
                Route::get('jadwal-kerja', 'JadwalKerjaController@index')->name('master.jadwal-kerja');
                Route::get('jadwal-kerja-get', 'JadwalKerjaController@data')->name('master.jadwal-kerja.get');
                Route::get('jadwal-kerja-shift-counts', 'JadwalKerjaController@getShiftCounts')->name('master.jadwal-kerja.shift-counts');
                Route::delete('jadwal-kerja-delete/{id}', 'JadwalKerjaController@destroy')->name('master.jadwal-kerja.delete');
                Route::post('jadwal-kerja-sync', 'JadwalKerjaController@syncSchedules')->name('master.jadwal-kerja.sync');
                Route::post('jadwal-kerja-store', 'JadwalKerjaController@store')->name('master.jadwal-kerja.store');
                Route::put('jadwal-kerja-update/{id}', 'JadwalKerjaController@update')->name('master.jadwal-kerja.update');
                Route::get('get-karyawan','JadwalKerjaController@getKaryawanForSelect')->name('master.karyawan.select2');
                Route::get('get-karyawan-without-jadwal','JadwalKerjaController@getKaryawanWithoutJadwal')->name('master.karyawan-without-jadwal.select2');
                Route::get('jadwal-kerja/{id}/edit','JadwalKerjaController@edit')->name('master.jadwal-kerja.edit');


                // Jadwal Karyawan
                Route::get('jadwal-karyawan', 'JadwalKaryawanController@index')->name('master.jadwal-karyawan');
                Route::get('jadwal-karyawan-get', 'JadwalKaryawanController@getData')->name('jadwal.jadwal-karyawan.get');
                Route::post('jadwal-karyawan-generate', 'JadwalKaryawanController@generateJadwal')->name('jadwal.jadwal-karyawan.generate');;
                Route::get('jadwal-karyawan-shift/{id}', 'JadwalKaryawanController@shiftKerja')->name('jadwal.jadwal-karyawan.shift');
                Route::post('jadwal-karyawan-shift/{id}', 'JadwalKaryawanController@simpanShift')->name('jadwal.jadwal-karyawan.shift');

                Route::get('kalender-kerja', 'KalenderKerjaController@index')->name('master.kalender-kerja');
                Route::get('kalender-kerja/events', 'KalenderKerjaController@events')->name('master.kalender-kerja.events');
                Route::post('kalender-kerja', 'KalenderKerjaController@store')->name('master.kalender-kerja.store');
                Route::put('kalender-kerja/{id}', 'KalenderKerjaController@update')->name('master.kalender-kerja.update');
                Route::delete('kalender-kerja/{id}', 'KalenderKerjaController@destroy')->name('master.kalender-kerja.delete');
                Route::post('kalender-kerja/import-api', 'KalenderKerjaController@importHolidays')->name('master.kalender-kerja.import-api');
            });

            Route::prefix('presensi')->group(function () {


                Route::get('monitoring','PresensiController@index')->name('presensi.monitoring');
                Route::get('data', 'PresensiController@data')->name('presensi.data');
                Route::post('sync', 'PresensiController@sync')->name('presensi.sync');
                Route::get('summary', 'PresensiController@summary')->name('presensi.summary');

                Route::get('resume','ResumePresensiController@index')->name('resume-presensi.index');
                Route::get('resume/data', 'ResumePresensiController@data')->name('resume-presensi.data');
                Route::get('resume/export', 'ResumePresensiController@export')->name('resume-presensi.export');
                Route::post('resume/sinkron', 'ResumePresensiController@sync')->name('resume-presensi.sync');
                Route::get('resume/detail/{karyawan_id}', 'ResumePresensiController@detail')->name('resume-presensi.detail');
                Route::get('resume/print/{karyawan_id}', 'ResumePresensiController@printKaryawan')->name('resume-presensi.print');

                Route::get('izin-cuti','PresensiIzinController@index')->name('presensi-izin.index');
                Route::get('izin-cuti/data', 'PresensiIzinController@izinData')->name('presensi-izin.data');
                Route::post('izin-cuti/approve/{id}', 'PresensiIzinController@approve')->name('presensi-izin.approve');
                Route::post('izin-cuti/reject/{id}', 'PresensiIzinController@reject')->name('presensi-izin.reject');
                Route::get('izin-cuti/detail/{id}', 'PresensiIzinController@detail')->name('presensi-izin.detail');


                Route::get('manual','PresensiManualController@index')->name('presensi-manual.index');
                Route::get('manual/data', 'PresensiManualController@data')->name('presensi-manual.data');
                Route::get('manual/jadwal', 'PresensiManualController@getJadwal')->name('presensi-manual.jadwal');
                Route::get('manual/detail/{id}','PresensiManualController@detail')->name('presensi-manual.detail');
                Route::post('manual/store','PresensiManualController@store')->name('presensi-manual.store');
                Route::put('manual/update/{id}', 'PresensiManualController@update')->name('presensi-manual.update');
                Route::delete('manual/destroy/{id}', 'PresensiManualController@destroy')->name('presensi-manual.destroy');


            });
            Route::prefix('device')->group(function () {
                Route::get('manajemen', 'UserDeviceController@index')->name('device.index');
                Route::get('manajemen/data', 'UserDeviceController@data')->name('device.data');
                Route::post('manajemen/block/{id}', 'UserDeviceController@block')->name('device.block');
                Route::post('manajemen/unblock/{id}', 'UserDeviceController@unblock')->name('device.unblock');

                // Approval Device
                Route::get('approval', 'UserDeviceController@approval')->name('device.approval');
                Route::get('approval/data', 'UserDeviceController@approvalData')->name('device.approval.data');
                Route::post('approval/approve/{id}', 'UserDeviceController@approve')->name('device.approve');
                Route::post('approval/reject/{id}', 'UserDeviceController@reject')->name('device.reject');

            });
            Route::prefix('informasi')->group(function () {
                Route::get('/','InformationController@index')->name('informasi.index');
                Route::get('/data','InformationController@data')->name('informasi.data');
                Route::post('/store','InformationController@store')->name('informasi.store');
                Route::put('/update/{id}','InformationController@update')->name('informasi.update');
                Route::delete('/destroy/{id}','InformationController@destroy')->name('informasi.destroy');
                Route::get('/show/{id}','InformationController@show')->name('informasi.show');


            });



        });

    });
});
