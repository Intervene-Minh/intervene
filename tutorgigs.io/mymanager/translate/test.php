<?php

require_once ('vendor/autoload.php');
use \Statickidz\GoogleTranslate;

$source = 'en';
$target = 'es';
$text = '<p>hello</p>';

$trans = new GoogleTranslate();
$result = $trans->translate($source, $target, $text);

echo $result;