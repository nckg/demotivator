<?php


namespace Nckg\Demotivator;

use ColorThief\ColorThief;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class ImageGenerator
{
    /**
     * @var ImageManager
     */
    private $imageManager;
    /**
     * @var Collection
     */
    private $fontCollection;

    /**
     * ImageGenerator constructor.
     *
     * @param Collection $fontCollection
     * @param ImageManager $imageManager
     */
    public function __construct(Collection $fontCollection, ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
        $this->fontCollection = $fontCollection;
    }

    /**
     * Creates a new image with a centered quote
     *
     * @param $quote
     * @return \Intervention\Image\Image
     */
    public function make($quote)
    {
        // Fetch a new image. If the image doesn't match the requirements we will do it again
        do {
            $image = $this->getBackgroundImage();
        } while ($image->width() < 800 or $image->height() < 800);

        // Normalize
        $this->normalizeBackground($image);

        // Insert text in background image
        $textGenerator = new QuoteToImage($this->imageManager, $this->fontCollection);
        $textGenerator->setFontColour($this->getFontColor($image));
        $image->insert($textGenerator->make($quote), 'center');

        return $image;
    }


    /**
     * Fetches a new image from a specific source
     *
     * @return \Intervention\Image\Image
     */
    protected function getBackgroundImage()
    {
        return (new ImageFetcher($this->imageManager))->fetch();
    }

    /**
     * Normalizes the background image to a nice square image
     *
     * @param Image $image
     */
    protected function normalizeBackground(Image $image)
    {
        // If the image isn't a rectangle we will crop it to the best size possible
        if ($image->width() != $image->height()) {
            $size = ($image->width() > $image->height()) ? $image->height() : $image->width();
            $image->crop($size, $size);
        }

        // Resize to 1080 but don't upscale the image
        $image->resize(1080, 1080, function ($constraint) {
            $constraint->upsize();
        });
    }

    /**
     * @param $image
     * @return string
     */
    protected function getFontColor($image)
    {
        // Get the dominant color from the image
        $fontColor = '#ffffff';
        $dominantColor = ColorThief::getColor($image->getCore());

        // Calculate percieved brightness
        $contrast = sqrt(
            $dominantColor[0] * $dominantColor[0] * .241 +
            $dominantColor[1] * $dominantColor[1] * .691 +
            $dominantColor[2] * $dominantColor[2] * .068
        );

        if ($contrast > 130) {
            //bright color, use dark font
            $fontColor = '#111111';
        }

        return $fontColor;
    }
}