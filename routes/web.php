<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeeSlabController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RenewalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');




Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

        Route::group(['prefix' => 'module', 'as' => 'module.'], function () {
            Route::get('/', [ModuleController::class, 'index'])->name('index');
            Route::get('add-new', [ModuleController::class, 'create'])->name('create');
            Route::post('store', [ModuleController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [ModuleController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [ModuleController::class, 'update'])->name('update');
            Route::delete('/renewal/{id}', [ModuleController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('customer')->name('customer.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('add-new', [CustomerController::class, 'create'])->name('create');
            Route::post('store', [CustomerController::class, 'store'])->name('store');
            Route::get('edit/{customer}', [CustomerController::class, 'edit'])->name('edit');
            Route::post('update/{customer}', [CustomerController::class, 'update'])->name('update');
            Route::get('show/{customer}', [CustomerController::class, 'show'])->name('show');
            Route::delete('delete/{customer}', [CustomerController::class, 'destroy'])->name('destroy');
            Route::get('status/{customer}/{status}', [CustomerController::class, 'status'])->name('status');
        });

        Route::group(['prefix' => 'vehicle', 'as' => 'vehicle.'], function () {
            Route::get('/', [VehicleController::class, 'index'])->name('index');
            Route::get('add-new', [VehicleController::class, 'create'])->name('create');
            Route::post('store', [VehicleController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [VehicleController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [VehicleController::class, 'update'])->name('update');
            Route::delete('/renewal/{id}', [VehicleController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('type')->name('type.')->group(function () {
            Route::get('/', [VehicleTypeController::class, 'index'])->name('index');
            Route::get('add-new', [VehicleTypeController::class, 'create'])->name('create');
            Route::post('store', [VehicleTypeController::class, 'store'])->name('store');
            Route::get('edit/{vehicleType}', [VehicleTypeController::class, 'edit'])->name('edit');
            Route::post('update/{vehicleType}', [VehicleTypeController::class, 'update'])->name('update');
            Route::get('show/{vehicleType}', [VehicleTypeController::class, 'show'])->name('show');
            Route::delete('delete/{vehicleType}', [VehicleTypeController::class, 'destroy'])->name('destroy');
            Route::get('status/{vehicleType}/{status}', [VehicleTypeController::class, 'status'])->name('status');
        });

        Route::group(['prefix' => 'renewal', 'as' => 'renewal.'], function () {
            Route::get('/', [RenewalController::class, 'index'])->name('index');
            Route::get('add-new', [RenewalController::class, 'create'])->name('create');
            Route::post('store', [RenewalController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [RenewalController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [RenewalController::class, 'update'])->name('update');
            Route::delete('/renewal/{id}', [RenewalController::class, 'destroy'])->name('destroy');

            Route::group(['prefix' => 'tax', 'as' => 'tax.'], routes: function () {
                Route::get('/', [RenewalController::class, 'get_bluebooks'])->name('index');
                Route::get('/create', [RenewalController::class, 'create_bluebook'])->name('create');
            });

            Route::group(['prefix' => 'insurance', 'as' => 'insurance.'], routes: function () {
                Route::get('/', [RenewalController::class, 'get_insurances'])->name('index');
                Route::get('/create', [RenewalController::class, 'create_insurance'])->name('create');
            });

            Route::group(['prefix' => 'pollution_check', 'as' => 'pollution_check.'], routes: function () {
                Route::get('/', [RenewalController::class, 'get_pollution_checks'])->name('index');
                Route::get('/create', [RenewalController::class, 'create_pollution_check'])->name('create');
            });

            Route::group(['prefix' => 'road_permit', 'as' => 'road_permit.'], routes: function () {
                Route::get('/', [RenewalController::class, 'get_road_permits'])->name('index');
                Route::get('/create', [RenewalController::class, 'create_road_permit'])->name('create');
            });

            Route::group(['prefix' => 'tax', 'as' => 'tax.'], routes: function () {
                Route::get('/', [RenewalController::class, 'get_tax'])->name('index');
                Route::get('/create', [RenewalController::class, 'create_tax'])->name('create');
            });
        });

        Route::prefix('feeslab')->name('feeslab.')->group(function () {
            Route::get('/', [FeeSlabController::class, 'index'])->name('index');
            Route::get('add-new', [FeeSlabController::class, 'create'])->name('create');
            Route::post('store', [FeeSlabController::class, 'store'])->name('store');
            Route::get('edit/{fee}', [FeeSlabController::class, 'edit'])->name('edit');
            Route::post('update/{fee}', [FeeSlabController::class, 'update'])->name('update');
            Route::get('show/{fee}', [FeeSlabController::class, 'show'])->name('show');
            Route::delete('delete/{fee}', [FeeSlabController::class, 'destroy'])->name('destroy');
            Route::get('status/{fee}/{status}', [FeeSlabController::class, 'status'])->name('status');
        });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
