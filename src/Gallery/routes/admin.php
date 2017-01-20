<?php

Route::group([
    'prefix' => 'admin',
    'middleware' => ['web', 'auth'],
    'namespace' => 'Taggers\Gallery\Controllers'
    ], function() {
        Route::post('photos/upload', 'GalleriesController@uploadPhotos')->name('photos.store');
        Route::get('photos/{gallery}', 'GalleriesController@getPhotos')->name('photos');
        Route::post('photos/delete', 'GalleriesController@deletePhoto')->name('photos.delete');

        Route::get('galleries/updatestatus/{id}', 'GalleriesController@updateStatus')->name('galleries.updatestatus');
        Route::resource('galleries', 'GalleriesController', ['except' => ['show']]);
});