<?php

use App\Http\Controllers\BlueBookController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeeSlabController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\RenewalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleCategoryController;
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
            Route::get('show/{id}', [VehicleController::class, 'show'])->name('show');
            Route::delete('/renewal/{id}', [VehicleController::class, 'destroy'])->name('destroy');

            Route::prefix('category')->name('category.')->group(function () {
                Route::get('/', [VehicleCategoryController::class, 'index'])->name('index');
                Route::get('add-new', [VehicleCategoryController::class, 'create'])->name('create');
                Route::post('store', [VehicleCategoryController::class, 'store'])->name('store');
                Route::get('edit/{category}', [VehicleCategoryController::class, 'edit'])->name('edit');
                Route::post('update/{category}', [VehicleCategoryController::class, 'update'])->name('update');
                Route::get('show/{category}', [VehicleCategoryController::class, 'show'])->name('show');
                Route::delete('delete/{category}', [VehicleCategoryController::class, 'destroy'])->name('destroy');
                Route::get('status/{category}/{status}', [VehicleCategoryController::class, 'status'])->name('status');
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
        });

        Route::group(['prefix' => 'renewal', 'as' => 'renewal.'], function () {
            Route::get('/', [RenewalController::class, 'index'])->name('index');
            Route::get('add-new', [RenewalController::class, 'create'])->name('create');
            Route::post('store', [RenewalController::class, 'store'])->name('store');
            Route::get('/edit/{renewal}', [RenewalController::class, 'edit'])->name('edit');
            // Route::get('show/{renewal}', [RenewalController::class, 'show'])->name('show');
            Route::get('renewal/show/{vehicle}', [RenewalController::class, 'show'])->name('show');
            Route::post('/update/{renewal}', [RenewalController::class, 'update'])->name('update');
            Route::delete('/delete/{renewal}', [RenewalController::class, 'destroy'])->name('destroy');

            Route::prefix('type')->name('type.')->group(function () {
                Route::get('/', [RenewalController::class, 'create_renewal_type'])->name('index');
                Route::post('store', [RenewalController::class, 'store_renewal_type'])->name('store');
                Route::get('edit/{renewalType}', [RenewalController::class, 'edit_renewal_type'])->name('edit');
                Route::post('update/{renewalType}', [RenewalController::class, 'update_renewal_type'])->name('update');
                Route::get('show/{renewalType}', [RenewalController::class, 'show_renewal_type'])->name('show');
                Route::delete('delete/{renewalType}', [RenewalController::class, 'delete_renewal_type'])->name('destroy');
                Route::get('status/{renewalType}/{status}', [RenewalController::class, 'update_renewal_type_status'])->name('status');
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


        Route::prefix('bluebook')->name('bluebook.')->group(function () {
            Route::get('/', [BlueBookController::class, 'index'])->name('index');
            Route::get('add-new', [BlueBookController::class, 'create'])->name('create');
            Route::post('store', [BlueBookController::class, 'store'])->name('store');
            Route::get('edit/{bluebook}', [BlueBookController::class, 'edit'])->name('edit');
            Route::post('update/{bluebook}', [BlueBookController::class, 'update'])->name('update');
            Route::get('show/{bluebook}', [BlueBookController::class, 'show'])->name('show');
            Route::delete('delete/{bluebook}', [BlueBookController::class, 'destroy'])->name('destroy');
        });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
