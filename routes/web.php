<?php

Route::get('/', 'PagesController@root')->name('root');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

// 登陆用户路由组
Route::group(['middleware' => 'auth'], function() {
    Route::get('/email_verify_notice', 'PagesController@emailVerifyNotice')->name('email_verify_notice');
    Route::get('/email_verify_notice/verify', 'EmailVerificationController@verify')->name('email_verification.verify');
    Route::get('/email_verification/send', 'EmailVerificationController@send')->name('email_verification.send');

    // 需要邮箱验证的路由组
    Route::group(['middleware' => 'email_verified'], function() {

    });

});
