<?php

use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::controller(ImageController::class)->group(function () {
    Route::get('/',  'fileUpload')->name('fileUpload');
    Route::post('/', 'imageStore')->name('image.store');
});
