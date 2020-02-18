<?php

Route::get('/', function () {
    return view('welcome');
});

# Blog Posts Routes
Route::group(['middleware' => ['auth']], function () {
//
});

Route::resource('blog-posts','BlogPostController');

Route::resource('subscribers','SubscriberController')->only(['store']);

Route::get('mail-confirmation','SubscriberController@mail_confirmation');

Route::post('mail-confirmation','SubscriberController@confirmation')->name('mail.confirmation');

Auth::routes();

Route::get('/home', 'BlogPostController@index')->name('home');



// Route::get('ajax',function() {
//    return view('message');
// });
// Route::post('/getmsg','AjaxController@index');
