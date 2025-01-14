<?php

namespace Tsawler\Laravelfilemanager\controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

/**
 * Class ResizeController
 * @package Tsawler\Laravelfilemanager\controllers
 */
class ResizeController extends Controller
{

    /**
     * Dipsplay image for resizing
     *
     * @return mixed
     */
    public function getResize()
    {
        $ratio = 1.0;
        $image = Input::get('img');
        $dir = Input::get('dir');

        $original_width = Image::make(base_path() . "/" . Config::get('lfm.images_dir') . $dir . "/" . $image)->width();
        $original_height = Image::make(
            base_path() . "/" . Config::get('lfm.images_dir') . $dir . "/" . $image
        )->height();

        $scaled = false;

        if ($original_width > 600) {
            $ratio = 600 / $original_width;
            $width = $original_width * $ratio;
            $height = $original_height * $ratio;
            $scaled = true;
        } else {
            $height = $original_height;
            $width = $original_width;
        }

        if ($height > 400) {
            $ratio = 400 / $original_height;
            $width = $original_width * $ratio;
            $height = $original_height * $ratio;
            $scaled = true;
        }

        return View::make('laravel-filemanager::resize')
            ->with('img', Config::get('lfm.images_url') . $dir . "/" . $image)
            ->with('dir', $dir)
            ->with('image', $image)
            ->with('height', number_format($height, 0))
            ->with('width', $width)
            ->with('original_height', $original_height)
            ->with('original_width', $original_width)
            ->with('scaled', $scaled)
            ->with('ratio', $ratio);
    }


    public function performResize()
    {
        $img = Input::get('img');
        $dir = Input::get('dir');
        $dataX = Input::get('dataX');
        $dataY = Input::get('dataY');
        $height = Input::get('dataHeight');
        $width = Input::get('dataWidth');

        try {
            Image::make(public_path() . $img)->resize($width, $height)->save();

            return "OK";
        } catch (Exception $e) {
            return $e;
        }
    }
}
