<?php

require_once 'LinkScraper.class.php';

$links = new LinkScraper("http://google.com");

foreach ($links as $link) {
  var_dump($link);
}


// This will die horribly!

$links[0] = "something different";
