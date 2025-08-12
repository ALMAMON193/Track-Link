<?php

use App\Http\Controllers\API\Trucker\TrackDeliveryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
require __DIR__.'/api.php';
