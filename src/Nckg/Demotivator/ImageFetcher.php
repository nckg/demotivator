<?php


namespace Nckg\Demotivator;


use Intervention\Image\ImageManager;


class ImageFetcher
{
    /**
     * @var string
     */
    public $url = "https://source.unsplash.com/category/nature/1080x1080";
    /**
     * @var ImageManager
     */
    private $imageManager;

    /**
     *
     * @param ImageManager $imageManager
     */
    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * Fetch an image from url (or file)
     *
     * @return \Intervention\Image\Image
     */
    public function fetch()
    {
        return $this->imageManager->make($this->url);
    }
}