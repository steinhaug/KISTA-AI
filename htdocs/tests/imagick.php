<?php

$im = new Imagick('I:\python-htdocs\KISTA-AI\htdocs\tests\Ny mappe\videocapture-20221211-163540.jpg');
$im->transformImageColorspace(Imagick::COLORSPACE_SRGB);
