<?php

Route::group(['middleware' => ['web']], function () {
    Route::get('sdclient/redirect', 'Curio\SdClient\SdClientController@redirect');
    Route::get('sdclient/callback', 'Curio\SdClient\SdClientController@callback');
    Route::get('sdclient/logout', 'Curio\SdClient\SdClientController@logout');
});
