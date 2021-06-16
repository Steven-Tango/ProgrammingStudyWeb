<?php
    use think\facade\Route;
    Route::get('login','login/index')->ext('html');
    Route::post('login','login/login');
    
    Route::get('index','Index/index')->ext('html');
?>