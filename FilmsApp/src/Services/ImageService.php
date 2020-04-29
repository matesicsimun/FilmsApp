<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


class ImageService
{
    private $slugger;
    private $imageDirectory;


    public function __construct(string $imageDirectory, SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->imageDirectory = $imageDirectory;
    }

    /**
     * Displays the image associated
     * with the film.
     * @param string $fileName
     * @param string $imageType The image type (png, jpg, etc.)
     */
    public function showImage(string $fileName, string $imageType){
        $format = "Content-Type: image/".$imageType;
        header($format);

        $imageUrl = $this->getImageDirectory()."/".$fileName;
        switch($imageType){
            case 'jpg':
            case 'jpeg':
                $image = imagecreatefromjpeg($imageUrl);
                imagejpeg($image);
                break;
            case 'png':
                $image = imagecreatefrompng($imageUrl);
                imagepng($image);
                break;
            case 'gif':
                $image = imagecreatefromgif($imageUrl);
                imagegif($image);
                break;
            default:
                $image = imagecreatefrompng($imageUrl);
                imagepng($image);
        }

        imagedestroy($image);
    }

    public function saveUploadedImage(UploadedFile $image){
        $extension = $image->guessExtension();
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$extension;

        try {
            $image->move($this->getImageDirectory(), $fileName);
        } catch (FileException $e) {

        }

        return ['filename'=>$fileName, 'type'=>$extension];
    }

    public function getImageDirectory(){
        return $this->imageDirectory;
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