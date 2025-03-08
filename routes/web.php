<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WorkOrderTaskController;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_middleware'),
    'verified'
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Work Order Tasks Routes
Route::post('/work-orders/{id}/tasks', [WorkOrderTaskController::class, 'create']);
Route::post('/work-orders/{id}/tasks/{taskId}', [WorkOrderTaskController::class, 'update']);
Route::delete('/work-orders/{id}/tasks/{taskId}', [WorkOrderTaskController::class, 'delete']);
Route::post('/work-orders/{id}/tasks/{taskId}/complete', [WorkOrderTaskController::class, 'complete']);

// Task Notes Routes
Route::get('/work-orders/{id}/tasks/{taskId}/notes', [WorkOrderTaskController::class, 'getNotes']);
Route::post('/work-orders/{id}/tasks/{taskId}/notes', [WorkOrderTaskController::class, 'addNote']);

// Task Dependencies Routes
Route::get('/work-orders/{id}/tasks/{taskId}/dependencies', [WorkOrderTaskController::class, 'getDependencies']);
Route::post('/work-orders/{id}/tasks/{taskId}/dependencies', [WorkOrderTaskController::class, 'addDependency']);
Route::delete('/work-orders/{id}/tasks/{taskId}/dependencies/{dependentTaskId}', [WorkOrderTaskController::class, 'removeDependency']);

// Task Time Tracking Routes
Route::post('/work-orders/{id}/tasks/{taskId}/time/start', [WorkOrderTaskController::class, 'startTimeTracking']);
Route::post('/work-orders/{id}/tasks/{taskId}/time/stop', [WorkOrderTaskController::class, 'stopTimeTracking']);
Route::get('/work-orders/{id}/tasks/{taskId}/time/history', [WorkOrderTaskController::class, 'getTimeHistory']); 