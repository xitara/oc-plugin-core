<?php namespace Xitara\Toolbox\Classes;

use Xitara\Toolbox\Classes\EroWrapper;

/**
 * summary
 */
class AppWrapper
{
    /**
     * summary
     */
    public function __construct($key, $filename, $hash = null)
    {
        /**
         * check permission to media
         */
        $ecms = new EroWrapper();
        $filePath = storage_path() . '/app/' . str_replace('-', '/', $key) . '/' . $filename;

        header('Content-Type: application/octet-stream; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);

        // header('Content-Type: text/html; charset=utf-8');
        // var_dump(storage_path());
        // var_dump($filePath);
        // var_dump($filename);
        // var_dump(filesize($filePath));

        return '';
    }
}
