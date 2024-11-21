<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\AjaxController;
use App\Http\Controllers\Admin\SleepController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DivisionsController;
use App\Http\Controllers\Admin\PositionsController;
use App\Http\Controllers\Admin\WorkhoursController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\ClocklocationsController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RolesController;
use Illuminate\Routing\RouteGroup;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware('auth')->get('/',[DashboardController::class, 'index']);
Route::controller(LoginController::class)->group(function() {
    Route::get('/login', 'login')->name('login');
    Route::post('/login', 'authenticate')->name('authenticate');
    Route::post('/logout', 'logout')->name('logout');
});

Route::middleware('auth')->prefix('admin')->group(function()
{
    Route::get('/',[DashboardController::class, 'index'])->name('dashboard');
    Route::get('/update_jam',[DashboardController::class, 'update_jam'])->middleware('role:developer');
    Route::get('/rekap_hadir',[DashboardController::class, 'rekap_hadir'])->name('dashboard.rekap_hadir');
    Route::get('/import_data',[DashboardController::class, 'import_data'])->name('dashboard.import_data');
    Route::controller(EmployeeController::class)->prefix('employee')->group(function()
    {
        Route::middleware('role_or_permission:developer|employee_create')->group(function () {
            Route::get('/create','create')->name('employee.create');
            Route::post('/','store')->name('employee.store');
        });
        Route::get('/','index')->name('employee')->middleware('role_or_permission:developer|employee_view');
        Route::middleware('role_or_permission:developer|employee_update')->group(function () {
            Route::get('/{id}/profile','edit_profile')->name('employee.edit_profile');
            Route::post('/{id}/profile','update_profile')->name('employee.update_profile');
            Route::get('/{id}/employee','edit_employee')->name('employee.edit_employee');
            Route::post('/{id}/employee','update_employee')->name('employee.update_employee');
            Route::DELETE('/delete/{id}','pass_reset')->name('employee.reset_pass');
            Route::GET('/update_category/{id}','update_category')->name('employee.update_category');
            Route::GET('/update_shift/{id}','update_shift')->name('employee.update_shift');
        });

        Route::get('/test','destroy')->name('employee.test')->middleware('role:developer');
    });

    Route::controller(AjaxController::class)->prefix('ajax')->group(function()
    {
        Route::get('/division/{id}','getDivision')->name('ajax.division');
        Route::get('/position/{id}','getPosition')->name('ajax.position');
        Route::post('/userValidate','userValidate')->name('ajax.uservalidate');
        Route::post('/profilevalidate','profilevalidate')->name('ajax.profilevalidate');
        Route::post('/ajaxBarchart','ajaxBarchart')->name('ajax.ajaxBarchart');
    });


    Route::middleware('role_or_permission:developer|sleep_view')->group(function () {
        Route::controller(SleepController::class)->prefix('sleep')->group(function()
        {
            Route::get('/','index')->name('sleep');
            Route::middleware('permission:sleep_update')->group(function () {
                Route::get('/{id}','edit')->name('sleep.edit');
                Route::post('/{id}','update')->name('sleep.update');
                Route::post('/{id}/accept','accept')->name('sleep.accept');
                Route::get('/export','export')->name('sleep.export');
                Route::post('/{id}/accept','accept')->name('sleep.accept');
                Route::get('/{id}/watchdist','update_watchdist')->name('sleep.watchdist');
            });
        });
    });
            

    Route::middleware('role_or_permission:developer|master_option')->group(function () {
        
        Route::controller(CompanyController::class)->group(function(){
            Route::prefix('company')->group(function () {
                Route::get('/','index')->name('masters.company');
                Route::post('/','store')->name('masters.company.store');
                Route::delete('/{id}','destroy')->name('masters.company.destroy');
                Route::get('/{id}','edit')->name('masters.company.edit');
                Route::post('/{id}','update')->name('masters.company.update');
            });
            Route::prefix('project')->group(function () {
                Route::get('/','index_proj')->name('masters.project');
                Route::post('/','store_proj')->name('masters.project.store');
                Route::delete('/{id}','destroy_proj')->name('masters.project.destroy');
                Route::get('/{id}','edit_proj')->name('masters.project.edit');
                Route::post('/{id}','update_proj')->name('masters.project.update');
            });
        });


        Route::controller(DivisionsController::class)->prefix('division')->group(function()
        {
            Route::get('/','index')->name('masters.division');
            Route::get('/create','create')->name('masters.division.create');
            Route::post('/','store')->name('masters.division.store');
            Route::delete('/{id}','destroy')->name('masters.division.destroy');
            Route::get('/{id}','edit')->name('masters.division.edit');
            Route::post('/{id}','update')->name('masters.division.update');
        });
    
        Route::controller(PositionsController::class)->prefix('position')->group(function()
        {
            Route::get('/','index')->name('masters.position');
            Route::get('/create','create')->name('masters.position.create');
            Route::post('/','store')->name('masters.position.store');
            Route::delete('/{id}','destroy')->name('masters.position.destroy');
            Route::get('/{id}','edit')->name('masters.position.edit');
            Route::post('/{id}','update')->name('masters.position.update');
        });

        Route::controller(WorkhoursController::class)->group(function()
        {
            Route::prefix('work_schedule')->group(function () {
                Route::get('/','index')->name('absensi.workhours');
                Route::get('/create','create')->name('absensi.workhours.create');
                Route::post('/','store')->name('absensi.workhours.store');
                Route::delete('/{id}','destroy')->name('absensi.workhours.destroy');
                Route::get('/{id}','edit')->name('absensi.workhours.edit');
                Route::post('/{id}','update')->name('absensi.workhours.update');
            });
            Route::prefix('shift')->group(function()
            {
                Route::get('/','index_shift')->name('absensi.shift');
            });
        });
    
        Route::controller(ClocklocationsController::class)->prefix('clocklocations')->group(function()
        {
            Route::get('/','index')->name('absensi.clocklocations');
            Route::get('/create','create')->name('absensi.clocklocations.create');
            Route::post('/','store')->name('absensi.clocklocations.store');
            Route::delete('/{id}','destroy')->name('absensi.clocklocations.destroy');
            Route::get('/{id}','edit')->name('absensi.clocklocations.edit');
            Route::post('/{id}','update')->name('absensi.clocklocations.update');
        });
    });
        

    Route::middleware('role_or_permission:developer|attd_view')->controller(AttendanceController::class)->prefix('attendance')->group(function()
    {
        Route::get('/','index')->name('absensi.attendance');
        Route::get('/{id}/details','details')->name('absensi.attendance.details');
        Route::get('/export','export')->name('absensi.attendance.export');
        Route::get('/export_dte','export_dte')->name('absensi.attendance.export_dte');
        Route::get('/{id}/export','export_details')->name('absensi.attendance.export_details');
        Route::get('/{id}','edit')->name('absensi.attendance.edit');
        Route::post('/{id}','update')->name('absensi.attendance.update');
    });

    Route::middleware('role_or_permission:developer|role_permission')->group(function () {
        Route::controller(RolesController::class)->prefix('roles')->group(function()
            {
                Route::get('/','index')->name('masters.roles');
                Route::post('/','store')->name('masters.roles.store');
                Route::get('/{id}','edit')->name('masters.roles.edit');
                Route::post('/{id}','update')->name('masters.roles.update');
                Route::delete('/{id}','destroy')->name('masters.roles.destroy');
                Route::get('/{id}/permission','get_permission')->name('masters.roles.get_permission');
                Route::post('/{id}/permission','set_permission')->name('masters.roles.set_permission');
            });

            Route::controller(PermissionController::class)->prefix('permission')->group(function()
            {
                Route::get('/','index')->name('masters.permission');
                Route::post('/','store')->name('masters.permission.store');
                Route::get('/{id}','edit')->name('masters.permission.edit');
                Route::post('/{id}','update')->name('masters.permission.update');
                Route::delete('/{id}','destroy')->name('masters.permission.destroy');
            });
    });

    Route::controller(usersController::class)->prefix('users')->group(function()
    {
        Route::get('/','index')->name('masters.users')->middleware('role_or_permission:developer|user_view');
        Route::group(['middleware'=> 'role_or_permission:developer|user_update'], function (){
            Route::post('/{id}/status','status')->name('masters.users.status');
            Route::patch('/{id}/reset_phone','reset_phone')->name('masters.users.reset_phone');
            Route::post('/update_location/{id}','update_location')->name('masters.users.update_location');
        });
        
        Route::group(['middleware'=> 'role_or_permission:developer|role_permission'], function (){
            Route::get('/permission','permission')->name('masters.users.permission');
            Route::get('/permission/{id}','permission_edit')->name('masters.users.permission_edit');
            Route::post('/permission/{id}','permission_update')->name('masters.users.permission_update');
        });
    });


}
);
