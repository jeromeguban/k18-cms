<?php

$PATH = "Address\\";


Route::get('countries', $PATH . 'CountryController@index');
Route::get('regions', $PATH . 'RegionController@index');
Route::get('provinces', $PATH . 'ProvinceController@index');
Route::get('regions/{region}/provinces', $PATH . 'RegionProvincesController@index');
Route::get('provinces/{province}/municipalities', $PATH . 'ProvinceMunicipalitiesController@index');
Route::get('municipalities/{municipality}/barangays', $PATH . 'MunicipalityBarangaysController@index');
Route::get('zipcodes', $PATH . 'ZipcodeController@index');
