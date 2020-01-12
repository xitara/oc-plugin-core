<?php

use Xitara\Toolbox\Classes\AppWrapper;
use Xitara\Toolbox\Classes\ImageWrapper;
use Xitara\Toolbox\Classes\VideoWrapper;

/**
 * image wrapper
 *
 * parameters:
 * media => media-path, base64 encoded
 * hash (optional) => user_session|user_id
 * size (optional) => width|[height]|[0|1 keep ratio]
 */
Route::get('/image-wrapper/{media}/{hash?}/{size?}', function ($media, $hash = null, $size = null) {
    return new ImageWrapper($media, $hash, $size);
});

Route::get('/video-wrapper/{media}/{hash?}', function ($media, $hash = null) {
    return new VideoWrapper($media, $hash);
});

Route::get('/app-wrapper/{key}/{media}/{hash?}', function ($key, $media, $hash = null) {
    return new AppWrapper($key, $media, $hash);
});
