<?php

use App\Http\Controllers\AllocationController;
use App\Http\Controllers\AwardsController;
use App\Http\Controllers\ChartsController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\GradeBookController;
use App\Http\Controllers\GradingController;
use App\Http\Controllers\MarkSheetsController;
use App\Http\Controllers\PromoteStudentsController;
use App\Http\Controllers\ReportAnalysisController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SchoolCalendarController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentGradesController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\TermController;
use App\Http\Middleware\CheckUser;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('charts', ChartsController::class);
Route::post('/authenticate', [App\Http\Controllers\ProfileController::class, 'authUser']);
Route::post('/registeruser', [App\Http\Controllers\ProfileController::class, 'regUser']);

Route::group(['prefix'=>'admin','middleware'=>['auth:web','checkUser']],function () {
    Route::resource('allocations', AllocationController::class);
    Route::resource('students', StudentController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('gradebook', GradeBookController::class);
    Route::resource('mark-sheets', MarkSheetsController::class);
    Route::resource('analysis', ReportAnalysisController::class);
    Route::resource('awards', AwardsController::class);
    Route::resource('codes', CodeController::class);
    Route::resource('settings', SettingsController::class);
    Route::resource('subjects', SubjectsController::class);
    Route::resource('classes', ClassesController::class);
    Route::resource('grading', GradingController::class);
    Route::resource('calendar', SchoolCalendarController::class);
    Route::resource('term', TermController::class);
    Route::resource('promote', PromoteStudentsController::class);
    Route::resource('school', SchoolController::class);
    Route::resource('grade-book', StudentGradesController::class);
});

Route::resource('reports', ReportsController::class);
Route::resource('grade-loader', \App\Http\Controllers\GradesLoaderController::class);

Route::resource('data-entry', \App\Http\Controllers\Teachers\GradeBookController::class);

Route::get('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
