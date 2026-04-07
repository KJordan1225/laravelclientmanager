<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'app')->name('app');
Route::view('/{any}', 'app')->where('any', '.*');



