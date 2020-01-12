<?php namespace Xitara\Toolbox\Classes;

use Xitara\Toolbox\Classes\EroWrapper;

/**
 * summary
 */
class ImageWrapper
{
    /**
     * summary
     */
    public function __construct($media, $user_id = null, $user_session = null)
    {
        $media = base64_decode($media);
        $file = getcwd() . $media;

        /**
         * generate thumbs if possible
         */
        // MediaLibrary::thumb($pathToImage, $width, $height, $mode, $customOutputPath = null)
        // $image = new System\Models\File;
        // $image->fromFile($file);

        /**
         * check permission to image
         */
        $ecms = new EroWrapper();
        // $this->userdata = $ecms->userdataFromSession(null, $this->param('hash'));

        // var_dump(getcwd() . $media);
        $mime = image_type_to_mime_type(exif_imagetype($file));

        header("Content-type: " . $mime);
        readfile($file);

        return '';
    }
}
