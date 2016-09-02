<?php

require __DIR__ . "/../vendor/autoload.php";

function getJson($file) {
    return json_decode(file_get_contents(realpath(__DIR__ . "/../data/$file")));
}

use Intervention\Image\ImageManager;

// create an image manager instance with favored driver
$manager = new ImageManager;
$quote = (new \Nckg\Demotivator\Collection(getJson("quotes.json")))->random();
$fontCollection = new \Nckg\Demotivator\Collection(getJson("font-pairs.json"));
$imageFetcher = new \Nckg\Demotivator\ImageFetcher($manager);
$textGenerator = new \Nckg\Demotivator\QuoteToImage($manager, $fontCollection);

$image = $imageFetcher->fetch();

while ($image->width() < 800 or $image->height() < 800) {
    $image = $imageFetcher->fetch();
}

if ($image->width() != $image->height()) {
    $size = ($image->width() > $image->height()) ? $image->height() : $image->width();
    $image->crop($size, $size);
}

if ($image->width() > 1080) {
    $image->resize(1080, 1080);
}
$image->insert($textGenerator->fetch($quote), 'center');
echo $image->response('jpg');