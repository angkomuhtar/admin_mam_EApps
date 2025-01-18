<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClockController;
use App\Http\Controllers\Api\HazardController;
use App\Http\Controllers\Api\SleepController;
use App\Http\Controllers\Api\LeaveApiController;
use App\Http\Controllers\Api\MasterController;
use App\Http\Controllers\Api\OldClockController;

Route::prefix('v1')->group(function(){
    Route::group([
        'prefix'=>'auth',
        'controller'=>AuthController::class
    ],function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
    });
    
    Route::middleware('jwt')->group(function () {
        Route::get('/', function(){
            return 'losee';
        });

        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/team', [AuthController::class, 'team']);
        Route::get('/version', [AuthController::class, 'version']);
        Route::POST('/change_password', [AuthController::class, 'change_password']);
        Route::POST('/change_avatar', [AuthController::class, 'change_avatar']);
        Route::get('/home', [OldClockController::class, 'home']);

        Route::group([
            'prefix' => 'clock',
            'controller'=> OldClockController::class
        ], function(){
            Route::get('/', 'index');
            Route::get('/{month}/history', 'history');
            Route::get('/shift', 'shift');
            Route::post('/', 'clock');
            Route::get('/today', 'today');
            Route::get('/location', 'location');
            Route::get('/rekap', 'rekap');
        });

        
        // Route::apiResource('leave', LeaveApiController::class);
        Route::group([
            'prefix' => 'leave',
            'controller'=> LeaveApiController::class
        ], function(){
            Route::GET('/', 'index');
            Route::POST('/', 'store');
            Route::GET('leave_type', 'getleavetype');
            Route::POST('change_status', 'change_status');
            Route::GET('/leave_request/{type}', 'leave_request');
        });

        Route::group([
            'prefix' => 'sleep',
            'controller'=> SleepController::class
        ], function(){
            // Route::GET('/', 'index');
            Route::GET('/', 'index');
            Route::POST('/', 'store');
        });
    });
});

Route::prefix('v2')->group(function(){
    Route::group([
        'prefix'=>'auth',
        'controller'=>AuthController::class
    ],function () {
        Route::post('/login', 'login');
        Route::post('/register', 'register');
        Route::post('/logout', 'logout');
        Route::post('/refresh', 'refresh');
    });
    
    Route::middleware('jwt')->group(function () {
        Route::get('/', function(){
            return 'losee';
        });

        Route::get('/me', [AuthController::class, 'me']);
        Route::get('/team', [AuthController::class, 'team']);
        Route::get('/version', [AuthController::class, 'version']);
        Route::POST('/change_password', [AuthController::class, 'change_password']);
        Route::POST('/change_avatar', [AuthController::class, 'change_avatar']);
        Route::get('/home', [ClockController::class, 'home']);
        Route::get('/pic', [MasterController::class, 'pic']);
        Route::get('/request/count', [MasterController::class, 'request_count']);


        Route::group([
            'prefix' => 'clock',
            'controller'=> ClockController::class
        ], function(){
            Route::get('/', 'index');
            Route::get('/{month}/history', 'history');
            Route::get('/shift', 'shift');
            Route::post('/', 'clock');
            Route::get('/today', 'today');
            Route::get('/location', 'location');
            Route::get('/rekap', 'rekap');
        });

        Route::group([
            'prefix' => 'leave',
            'controller'=> LeaveApiController::class
        ], function(){
            Route::GET('/', 'index');
            Route::POST('/', 'store');
            Route::GET('leave_type', 'getleavetype');
            Route::POST('change_status', 'change_status');
            Route::GET('/leave_request/{type}', 'leave_request');
        });

        Route::group([
            'prefix' => 'sleep',
            'controller'=> SleepController::class
        ], function(){
            Route::GET('/', 'index');
            Route::POST('/', 'store');
        });

        Route::group([
            'prefix' => 'master',
            'controller'=> MasterController::class
        ], function(){
            Route::GET('/company', 'company');
            Route::GET('/hazard_location', 'hazard_location');
            Route::GET('/project/{id}', 'project');
            Route::GET('/division/{id}', 'division');
        });

        Route::group([
            'prefix' => 'hazard',
            'controller'=> HazardController::class
        ], function(){
            Route::GET('/', 'index');
            Route::GET('/report', 'list');
            Route::GET('/action', 'list_pekerjaan');
            Route::POST('/action', 'update_action');
            Route::GET('/{id}', 'show');
            Route::POST('/', 'store');
            Route::POST('/{id}/pic', 'set_pic');
        });
    });
});

