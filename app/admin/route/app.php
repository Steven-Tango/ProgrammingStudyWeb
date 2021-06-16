<?php
    use think\facade\Route;
    Route::get('login','login/index')->ext('html');
    Route::post('login','login/login');

    Route::get('index','Index/index')->ext('html');

    Route::post('resetPwd','login/resetPwd');
    Route::post('loginOut','login/loginOut');
    Route::get('user','index/userController')->ext('html');

    Route::get('course','index/course')->ext('html');
    Route::get('usercontro','index/userContro')->ext('html');
?>