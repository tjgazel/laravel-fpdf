<?php

namespace TJGazel\LaraFpdf\Facades;

use Illuminate\Support\Facades\Facade;
use TJGazel\LaraFpdf\LaraFpdf as Fpdf;

/**
 * @see \TJGazel\LaraFpdf\LaraFpdf
 * Class LaraFpdf
 * @package TJGazel\LaraFpdf\Facades
 */
class LaraFpdf extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Fpdf::class;
    }
}
