<?php


Route::get('/codes', 'NewLicenseCodeController@index')->name('code.index');
Route::get('/codes/create', 'NewLicenseCodeController@create')->name('code.create');
Route::get('/codes/getApi', 'NewLicenseCodeController@getApi')->name('code.getApi');
Route::post('/codes', 'NewLicenseCodeController@save')->name('code.save');
Route::get('/codes/{id}/edit', 'NewLicenseCodeController@edit')->name('code.edit');
Route::put('/codes/remark', 'NewLicenseCodeController@remark')->name('code.remark');
Route::put('/codes/{id}', 'NewLicenseCodeController@update')->name('code.update');
Route::get('/codes/{id}/info', 'NewLicenseCodeController@info')->name('code.info');
Route::delete('/codes/{id}', 'NewLicenseCodeController@delete')->name('code.delete');
Route::get('/codes/export', 'NewLicenseCodeController@export')->name('code.export');
