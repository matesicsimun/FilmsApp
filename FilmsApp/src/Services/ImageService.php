<?php

class ImageService
{
    private static $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (null === self::$instance){
            self::$instance = new ImageService();
        }

        return self::$instance;
    }

    /**
     * Displays the image associated
     * with the film.
     * @param string $imageType The image type (png, jpg, etc.)
     * @param string $imageData The image data in a string.
     */
    public function showImage(string $imageType, string $imageData){
        $format = "Content-Type: image/".$imageType;
        header($format);

        $image = imagecreatefromstring($imageData);

        switch($imageType){
            case 'jpg':
            case 'jpeg':
                imagejpeg($image);
                break;
            case 'png':
                imagepng($image);
                break;
            case 'gif':
                imagegif($image);
                break;
            default:
                imagepng($image);
        }

        imagedestroy($image);
    }

    /**
     * Returns the image type (png, jpeg, etc.)
     * by examining the image data.
     * @param string $imageData The image data as a string
     * @return string The image type
     */
    public function getImageType(string $imageData){
        $code = exif_imagetype($imageData);

        switch($code) {
            case 1:
                $imageType = 'gif';
                break;
            case 2:
                $imageType = 'jpeg';
                break;
            case 3:
                $imageType = 'png';
                break;
            default:
                $imageType = 'jpeg';
        }

        return $imageType;
    }
}