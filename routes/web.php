<?php

Route::get('/', 'PagesController@root')->name('root');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function() {
    Route::get('/email_verify_notice', 'PagesController@emailVerifyNotice')->name('email_verify_notice');

    // star
    Route::group(['middleware' => 'email_verified'], function() {
        Route::get('/test', function() {
          return 'ok!';
        });
    });
    //end

});
