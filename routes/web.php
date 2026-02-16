<?php

use App\Http\Controllers\BlueBookController;
use App\Http\Controllers\CheckPassController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FeeSlabController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\InsuranceProviderController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PollutionController;
use App\Http\Controllers\RenewalController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RenewalTypeController;
use App\Http\Controllers\RoadPermitController;
use App\Http\Controllers\VehicleCategoryController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleTaxController;
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

        Route::group(['prefix' => 'report', 'as' => 'report.'], function () {
            // Vehicle Report
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('vehicles/export', [ReportController::class, 'exportVehicles'])->name('vehicles.export');
            // Renewal Expiry Report
            Route::get('renewals-expiry', [ReportController::class, 'renewalExpiry'])->name('renewals.expiry');
            Route::get('renewals-expiry/export', [ReportController::class, 'exportRenewalExpiry'])->name('renewals.expiry.export');
        });

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
            Route::get('/edit/{vehicle}', [VehicleController::class, 'edit'])->name('edit');
            Route::put('/update/{vehicle}', [VehicleController::class, 'update'])->name('update');
            Route::get('show/{vehicle}', [VehicleController::class, 'show'])->name('show');
            Route::get('{vehicle}/renewal', [VehicleController::class, 'renewal'])->name('renewal');
            Route::delete('/renewal/{id}', [VehicleController::class, 'destroy'])->name('destroy');
            Route::get('status/{vehicle}/{status}', [VehicleController::class, 'status'])->name('status');
            Route::put('{vehicle}/renewal-update', [VehicleController::class, 'updateRenewal'])->name('update-renewal');
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

            Route::prefix('bluebook')->name('bluebook.')->group(function () {
                Route::get('/', [BlueBookController::class, 'index'])->name('index');
                Route::get('add-new', [BlueBookController::class, 'create'])->name('create');
                Route::post('store', [BlueBookController::class, 'store'])->name('store');
                Route::get('edit/{bluebook}', [BlueBookController::class, 'edit'])->name('edit');
                Route::post('update/{bluebook}', [BlueBookController::class, 'update'])->name('update');
                Route::get('show/{bluebook}', [BlueBookController::class, 'show'])->name('show');
                Route::delete('delete/{bluebook}', [BlueBookController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('checkpass')->name('checkpass.')->group(function () {
                Route::get('/', [CheckPassController::class, 'index'])->name('index');
                Route::get('add-new', [CheckPassController::class, 'create'])->name('create');
                Route::post('store', [CheckPassController::class, 'store'])->name('store');
                Route::get('edit/{checkpass}', [CheckPassController::class, 'edit'])->name('edit');
                Route::post('update/{checkpass}', [CheckPassController::class, 'update'])->name('update');
                Route::get('show/{checkpass}', [CheckPassController::class, 'show'])->name('show');
                Route::delete('delete/{checkpass}', [CheckPassController::class, 'destroy'])->name('destroy');
            });

            // Route::prefix('license')->name('license.')->group(function () {
            //     Route::get('/', [LicenseController::class, 'index'])->name('index');
            //     Route::get('add-new', [LicenseController::class, 'create'])->name('create');
            //     Route::post('store', [LicenseController::class, 'store'])->name('store');
            //     Route::get('edit/{license}', [LicenseController::class, 'edit'])->name('edit');
            //     Route::post('update/{license}', [LicenseController::class, 'update'])->name('update');
            //     Route::get('show/{license}', [LicenseController::class, 'show'])->name('show');
            //     Route::delete('delete/{license}', [LicenseController::class, 'destroy'])->name('destroy');
            // });

            Route::prefix('insurance')->name('insurance.')->group(function () {
                Route::get('/', [InsuranceController::class, 'index'])->name('index');
                Route::get('add-new', [InsuranceController::class, 'create'])->name('create');
                Route::post('store', [InsuranceController::class, 'store'])->name('store');
                Route::get('edit/{insurance}', [InsuranceController::class, 'edit'])->name('edit');
                Route::post('update/{insurance}', [InsuranceController::class, 'update'])->name('update');
                Route::get('show/{insurance}', [InsuranceController::class, 'show'])->name('show');
                Route::delete('delete/{insurance}', [InsuranceController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('pollution')->name('pollution.')->group(function () {
                Route::get('/', [PollutionController::class, 'index'])->name('index');
                Route::get('add-new', [PollutionController::class, 'create'])->name('create');
                Route::post('store', [PollutionController::class, 'store'])->name('store');
                Route::get('edit/{pollution}', [PollutionController::class, 'edit'])->name('edit');
                Route::post('update/{pollution}', [PollutionController::class, 'update'])->name('update');
                Route::get('show/{pollution}', [PollutionController::class, 'show'])->name('show');
                Route::delete('delete/{pollution}', [PollutionController::class, 'destroy'])->name('destroy');
            });
            Route::prefix('road-permit')->name('road-permit.')->group(function () {
                Route::get('/', [RoadPermitController::class, 'index'])->name('index');
                Route::get('add-new', [RoadPermitController::class, 'create'])->name('create');
                Route::post('store', [RoadPermitController::class, 'store'])->name('store');
                Route::get('edit/{roadpermit}', [RoadPermitController::class, 'edit'])->name('edit');
                Route::post('update/{roadpermit}', [RoadPermitController::class, 'update'])->name('update');
                Route::get('show/{roadpermit}', [RoadPermitController::class, 'show'])->name('show');
                Route::delete('delete/{roadpermit}', [RoadPermitController::class, 'destroy'])->name('destroy');
            });

            Route::prefix('vehicle-tax')->name('vehicle-tax.')->group(function () {
                Route::get('/', [VehicleTaxController::class, 'index'])->name('index');
                Route::get('add-new', [VehicleTaxController::class, 'create'])->name('create');
                Route::post('store', [VehicleTaxController::class, 'store'])->name('store');
                Route::get('edit/{vehicletax}', [VehicleTaxController::class, 'edit'])->name('edit');
                Route::post('update/{vehicletax}', [VehicleTaxController::class, 'update'])->name('update');
                Route::get('show/{vehicletax}', [VehicleTaxController::class, 'show'])->name('show');
                Route::delete('delete/{vehicletax}', [VehicleTaxController::class, 'destroy'])->name('destroy');
            });
        });

        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::prefix('feeslab')->name('feeslab.')->group(function () {
                Route::get('/list', [FeeSlabController::class, 'index'])->name('index');
                Route::get('add-new', [FeeSlabController::class, 'create'])->name('create');
                Route::post('store', [FeeSlabController::class, 'store'])->name('store');
                Route::get('edit/{fee}', [FeeSlabController::class, 'edit'])->name('edit');
                Route::post('update/{fee}', [FeeSlabController::class, 'update'])->name('update');
                Route::get('show/{fee}', [FeeSlabController::class, 'show'])->name('show');
                Route::delete('delete/{fee}', [FeeSlabController::class, 'destroy'])->name('destroy');
                Route::get('status/{fee}/{status}', [FeeSlabController::class, 'status'])->name('status');
            });

            Route::prefix('insurance-provider')->name('insurance-provider.')->group(function () {
                Route::get('/list', [InsuranceProviderController::class, 'index'])->name('index');
                Route::get('add-new', [InsuranceProviderController::class, 'create'])->name('create');
                Route::post('store', [InsuranceProviderController::class, 'store'])->name('store');
                Route::get('edit/{insuranceProvider}', [InsuranceProviderController::class, 'edit'])->name('edit');
                Route::post('update/{insuranceProvider}', [InsuranceProviderController::class, 'update'])->name('update');
                Route::get('show/{insuranceProvider}', [InsuranceProviderController::class, 'show'])->name('show');
                Route::delete('delete/{insuranceProvider}', [InsuranceProviderController::class, 'destroy'])->name('destroy');
                Route::get('status/{insuranceProvider}/{status}', [InsuranceProviderController::class, 'status'])->name('status');
            });

            Route::prefix('renewal-type')->name('renewal-type.')->group(function () {
                Route::get('/list', [RenewalTypeController::class, 'index'])->name('index');
                Route::post('store', [RenewalTypeController::class, 'store'])->name(name: 'store');
                Route::get('edit/{renewalType}', [RenewalTypeController::class, 'edit'])->name('edit');
                Route::post('update/{renewalType}', [RenewalTypeController::class, 'update'])->name('update');
                Route::get('show/{renewalType}', [RenewalTypeController::class, 'show'])->name('show');
                Route::delete('delete/{renewalType}', [RenewalTypeController::class, 'destroy'])->name('destroy');
                Route::get('status/{renewalType}/{status}', [RenewalTypeController::class, 'status'])->name('status');
            });

            Route::group(['prefix' => 'vehicle', 'as' => 'vehicle.'], function () {
                Route::prefix('category')->name('category.')->group(function () {
                    Route::get('/list', [VehicleCategoryController::class, 'index'])->name('index');
                    Route::get('add-new', [VehicleCategoryController::class, 'create'])->name('create');
                    Route::post('store', [VehicleCategoryController::class, 'store'])->name('store');
                    Route::get('edit/{category}', [VehicleCategoryController::class, 'edit'])->name('edit');
                    Route::post('update/{category}', [VehicleCategoryController::class, 'update'])->name('update');
                    Route::get('show/{category}', [VehicleCategoryController::class, 'show'])->name('show');
                    Route::delete('delete/{category}', [VehicleCategoryController::class, 'destroy'])->name('destroy');
                    Route::get('status/{category}/{status}', [VehicleCategoryController::class, 'status'])->name('status');
                });

                Route::prefix('type')->name('type.')->group(function () {
                    Route::get('/list', [VehicleTypeController::class, 'index'])->name('index');
                    Route::get('add-new', [VehicleTypeController::class, 'create'])->name('create');
                    Route::post('store', [VehicleTypeController::class, 'store'])->name('store');
                    Route::get('edit/{vehicleType}', [VehicleTypeController::class, 'edit'])->name('edit');
                    Route::post('update/{vehicleType}', [VehicleTypeController::class, 'update'])->name('update');
                    Route::get('show/{vehicleType}', [VehicleTypeController::class, 'show'])->name('show');
                    Route::delete('delete/{vehicleType}', [VehicleTypeController::class, 'destroy'])->name('destroy');
                    Route::get('status/{vehicleType}/{status}', [VehicleTypeController::class, 'status'])->name('status');
                });
            });
        });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
