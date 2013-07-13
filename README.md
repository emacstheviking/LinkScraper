LinkScraper - A PHP page scraping utility belt class.
=====================================================

Extract all A links from a page and optionally remove all of those
that are not 'on-site'. This is achieved by passing in the full URL to
the constructor, for example:

    http://foo.bar.com

which enables a simplistic check to know what's local and what isn't.

Licence
=======

LGPL3. Do what you will with it but a link back would be nice and also
knowing what you used it for is also of interest! Thanks. :)


Operation and Use
=================

This class implements the following interfaces: Iterator, ArrayAccess
which means you can treat is just like an array m aking it very easy
to process the links it finds at any given location.
 
I have made it **immutable** once loaded. If you try to change the
contents of it after it has been loaded it will die. You can change
that if you want but I prefer not to change things once they are
fixed, that's my 'functional' bias with PHP these days!

 
Typical Usage:
 
    $links = new PageSucker("http://google.com");

    echo $links[0];

    $links[0] = "something"; // will die() as you cannot change its contents.
 
    foreach ($links as $l) {
        echo $l . '<br />';
    }

-------------------------------------------------------------------------


## Typical Usage:

    $links = new PageSucker("http://google.com");

    echo $links[0];

    $links[0] = "something"; // will die() as you cannot change its contents.

    foreach ($links as $l) {
        echo $l . '<br />';
    }

## Example.php output

If you run the example program as listed you should see this output:

    $ php example.php
    string(29) "http://www.google.co.uk/webhp"
    string(29) "http://www.google.co.uk/imghp"
    string(29) "http://maps.google.co.uk/maps"
    string(24) "https://play.google.com/"
    string(23) "http://www.youtube.com/"
    string(30) "http://news.google.co.uk/nwshp"
    string(29) "https://mail.google.com/mail/"
    string(25) "https://drive.google.com/"
    string(40) "http://www.google.co.uk/intl/en/options/"
    string(31) "https://www.google.com/calendar"
    string(30) "http://translate.google.co.uk/"
    string(31) "http://books.google.co.uk/bkshp"
    string(32) "http://www.google.co.uk/shopping"
    string(23) "http://www.blogger.com/"
    string(31) "http://www.google.co.uk/finance"
    string(30) "https://plus.google.com/photos"
    string(26) "http://video.google.co.uk/"
    string(40) "https://accounts.google.com/ServiceLogin"
    string(35) "http://www.google.co.uk/preferences"
    string(32) "http://google.com:80/preferences"
    string(38) "http://www.google.co.uk/history/optout"
    string(36) "http://google.com:80/advanced_search"
    string(35) "http://google.com:80/language_tools"
    string(33) "http://google.com:80/intl/en/ads/"
    string(30) "http://google.com:80/services/"
    string(45) "https://plus.google.com/103583604759580854844"
    string(39) "http://google.com:80/intl/en/about.html"
    string(37) "http://www.google.co.uk/setprefdomain"
    string(38) "http://google.com:80/intl/en/policies/"
    I don't want to be mutable


Note the message at the end. I deliberatelty chose it to be this way so that my code (or anybody elses) couldn't accidentally add things that weren't there in the first place. Change it if you want.
