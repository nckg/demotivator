<?php


namespace Nckg\Demotivator;


use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Nckg\Demotivator\Collection as FontCollection;

class QuoteToImage
{
    /**
     * @var ImageManager
     */
    protected $imageManager;
    /**
     * @var Collection
     */
    protected $fonts;
    /**
     * @var string
     */
    protected $fontPath;

    /**
     * TextGenerator constructor.
     * @param ImageManager $imageManager
     * @param Collection $fonts
     */
    public function __construct(ImageManager $imageManager, FontCollection $fonts)
    {
        $this->imageManager = $imageManager;
        $this->fonts = $fonts;
        $this->fontPath = __DIR__ . '/../../../vendor/google/fonts/';
    }

    /**
     * Creates a new image with our quote on it
     *
     * @param $quote
     * @return \Intervention\Image\Image
     */
    public function make($quote)
    {
        $parts = explode("\n", $quote);
        $posY = 0;
        $image = $this->imageManager->canvas(800, 5000);
        $useableFonts = $this->getFont($parts);

        foreach ($parts as $key => $part) {
            $posY = $this->applyLineToImage($image, $useableFonts, $key, $part, $posY);
        }

        $image->resizeCanvas(null, $posY, 'top');

        $image->resize(null, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        return $image;
    }

    /**
     * @param $parts
     * @return mixed
     */
    protected function getFont($parts)
    {
        return $this->fonts
            ->filter(function ($pairs) use ($parts) {
                return count($pairs) == count($parts);
            })
            ->first($this->fonts->first());
    }

    /**
     * @param $useableFonts
     * @param $key
     * @param $image
     * @param $part
     * @param $posY
     * @return mixed
     */
    protected function applyLineToImage(Image $image, $useableFonts, $key, $part, $posY)
    {
        $startingFontSize = 1000;

        $fontFile = (isset($useableFonts[$key])) ? $useableFonts[$key] : $useableFonts[0];
        $image->text(strtoupper($part), 400, $posY, function (\Intervention\Image\Gd\Font $font) use ($fontFile, $startingFontSize, &$posY) {
            $font->file($this->fontPath . $fontFile);
            $font->size($startingFontSize);
            $font->align("center");
            $font->color('#ffffff');
            $font->valign('top');

            $fontMetrics = $font->getBoxSize();
            while ($fontMetrics['width'] >= 800) {
                $startingFontSize--;
                $font->size($startingFontSize);
                $fontMetrics = $font->getBoxSize();
            }

            $posY += $fontMetrics['height'] + 10;
        });

        return $posY;
    }

    /**
     * @param string $fontPath
     */
    public function setFontPath($fontPath)
    {
        $this->fontPath = $fontPath;
    }
}