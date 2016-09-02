<?php

use Intervention\Image\ImageManager;
use Nckg\Demotivator\Collection;
use Nckg\Demotivator\Demotivator;

require __DIR__ . "/../vendor/autoload.php";

// Get quotes
$quotes = new Collection(json_decode(file_get_contents(realpath(__DIR__ . "/../data/quotes.json"))));

// get fonts
$fonts = new Collection(json_decode(file_get_contents(realpath(__DIR__ . "/../data/font-pairs.json"))));

$demotivator = new Demotivator($fonts, new ImageManager);

$image = $demotivator->make($quotes->random());

$image->save('example.jpg');
