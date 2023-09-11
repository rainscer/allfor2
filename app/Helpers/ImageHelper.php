<?php

use App\Models\CatalogProductImage;
use Intervention\Image\Exception\NotReadableException;
use Intervention\Image\Facades\Image;

if (!function_exists('image_asset')) {

    function image_asset($img, $type = null)
    {
        /*
         * $local - путь к файлу
         * может быть таким:
         * + -- пустая строка --
         * + ../image/data/product/2004607-1.jpg
         * + data/product/2002487-1.jpg
         * + img.korovo.com/2002601.jpg
         * + ../image/fotonew/9699342-1.jpg
         * + ../image/foto2602/9699754-1.jpg
         *
         *
         * $remote - ссылка на удаленный сервер
         * например
         * http://img01.taobaocdn.com/bao/uploaded/i2/T19v_xXctdXXbDzBE1_042215.jpg


            $patterns = [
                '/^img\.korovo\.com/i'            => 'http://img.korovo.com',
                '/\.\.\/image\/data\/product0/i' => 'http://korovo.com/image/data/product0',
                '/\.\.\/image\/data\/product/i'  => 'http://img.korovo.com',
                '/\.\.\//i'                      => 'http://korovo.com/',
                '/^data\/product/i'              => 'http://img.korovo.com'
            ];

        $img = preg_replace(
                array_keys($patterns),
                array_values($patterns),
            $img
            );
        */
        $tmp = explode('/', $img);

        if (str_contains(head($tmp), 'http:')) {
            if (is_null($type)) {
                $type = '';
            } else {
                $type = $type . '/';
            }

            $value = end($tmp);
            $img = 'http://img1.korovo.com/' . $type . $value;

            return asset($img);

        } else {

            if (!is_null($type)) {

                return asset(preg_replace('/\/(\d+)\//i', '/$1/200x200/', $img));
                //return asset(str_replace(CatalogProductImage::PATH_200, CatalogProductImage::PATH_800, $img));
            } else {

                return asset($img);
            }
        }
    }
}


    if(!function_exists('resizeAndSaveImage')){

        function resizeAndSaveImage($image_path, $path_save, $crop = false, $width = 300, $height = 300)
        {
            try
            {
                $img = Image::make($image_path);
            }
            catch(NotReadableException $e)
            {
                return false;
            }

            if($crop) {

                // create new image with transparent background color
                $background = Image::canvas($width, $height);

                $img->resize($width, $height, function ($c) {
                    $c->aspectRatio();
                    $c->upsize();
                });

                $background->insert($img, 'center');

                $background->encode()
                    ->save($path_save);

                return true;

                /*$img->backup();

                $img->widen($width);
                if($img->height() < $height){
                    $img->reset();
                    $img->heighten($height);
                }

                $img->crop($width, $height);*/
            }else {

                $img->resize(2048, 2048, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }

            $img->encode()
                ->save($path_save);

            return true;
        }
    }