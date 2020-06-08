<?php namespace Xitara\Core\Classes;

use Backend\Skins\Standard;

/**
 * Modified backend skin information file.
 *
 * This is modified to include an additional path to override the default layouts.
 *
 */

class BackendSkin extends Standard
{
    /**
     * {@inheritDoc}
     */
    public function getLayoutPaths()
    {
        return [
            base_path() . '/plugins/xitara/core/backend/layouts',
            $this->skinPath . '/layouts',
        ];
    }
}
