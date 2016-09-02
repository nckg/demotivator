<?php


class QuoteToImageTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     */
    public function it_can_generate_an_image()
    {
        // Arrange
        $manager = new \Intervention\Image\ImageManager;
        $fontCollection = new \Nckg\Demotivator\Collection($this->getJson("font-pairs.json"));
        $class = new \Nckg\Demotivator\QuoteToImage($manager, $fontCollection);

        // Act
        $image = $class->fetch("Hello\nWorld");

        // Assert
        $this->assertEquals(372, $image->height());
        $this->assertEquals(800, $image->width());
    }

    /**
     * @param $file
     * @return mixed
     */
    protected function getJson($file)
    {
        return json_decode(file_get_contents(realpath(__DIR__ . "/../data/$file")));
    }
}