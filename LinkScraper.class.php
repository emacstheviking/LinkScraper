<?php
/*!

  @file   LinkScraper.class.php
  @author Sean Charles sean at objitsu dot com
  @date   28th March 2011


  LinkScraper
  ===========

  Extract all A links from a page and optionally remove all of those
  that are not 'on-site'. This is achieved by passing in the full URL to
  the constructor, for example:

  <a href="http://foo.bar.com">http://foo.bar.com</a>

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

 
  ## Typical Usage:
 
  $links = new PageSucker("http://google.com");

  echo $links[0];

  $links[0] = "something"; // will die() as you cannot change its contents.
 
  foreach ($links as $l) {
  echo $l . '<br />';
  }

  ## Example.php output


*/
class LinkScraper implements Iterator, ArrayAccess, Countable
{
  private $url;
  private $parsed_url;
  private $links;
  private $index;
  private $maxlen;
 
  /*!
   * @param $url is the URL to scrape. MUST start with http: in order for the
   * offsite link removal to function correctly.
   *
   * @param $offsite = TRUE to keep all links, otherwise the list will be
   * purged of all offsite links meaning this class can be used to build a
   * site-map.
   */
  public function __construct($url, $offsite = TRUE, $rejectRSS = TRUE)
  {
    $this->links = array();

    // Clean and rebuild the URL we've been given.

    $this->parsed_url  = parse_url(trim($url));
    $this->parsed_url += 
      array('scheme' => 'http',
            'port'   => 80,
            'path'   => '/');
 

    //! Rebuild a cleaned URL for internal use ...

    $this->url 
      = $this->parsed_url['scheme'] . '://'
      . $this->parsed_url['host']   . ':'
      . $this->parsed_url['port']
      . $this->parsed_url['path'];

    $this->len = strlen($this->url);
 
    $html    = file_get_contents($this->url);
    $matches = array();
 
    preg_match_all("/a[\s]+[^>]*?href[\s]?=[\s\"\']+(.*?)[\"\']+.*?>([^<]+|.*?)?<\/a>/",
		   $html,
		   &$matches);
     
    $links = array_unique($matches[1]);
 
    if (!$offsite) {
      $links = array_filter($links, array($this, '_purgeOffsite'));
    }

    if ($rejectRSS) {
      $links = array_filter($links, array($this, '_purgeRSS'));
    }
 
    $links        = array_map(array($this, '_normalise'), $links);
    $this->links  = array_values($links);
    $this->maxlen = count($this->links);
    $this->index  = 0;
  }

 
  /*!
   * Make all links absolute.
   */
  private function _normalise($link)
  {
    $link  = trim($link);
    $parts = explode('?', $link);
    $link  = $parts[0];

    if ($link[0] == '/') {
      $link = $this->url . substr($link,1);
    }
    return $link;
  }
 

  /*!
   * Filters out links that do not start with '/' and who's prefix is offsite.
   */
  private function _purgeOffsite($link) {
    if ($link[0] != '/') {
      return 0 == strncasecmp(
			      $this->url,
			      $link, $this->len);
    }
    return TRUE;
  }
 

  /*!
   * Filters out RSS feeds.
   */
  private function _purgeRSS($link) {
    return ".xml" !== strtolower(substr($link,-4));
  }
 

  // --------------------------------------------------------------------
  // Iterator
  // --------------------------------------------------------------------

  public function current() {
    return $this->links[$this->index];
  }

  public function key() {
    return $this->index;
  }

  public function next() {
    $this->index++;
  }

  public function valid() {
    return $this->index < $this->maxlen;
  }

  public function rewind() {
    $this->index = 0;
  }
 

  // --------------------------------------------------------------------
  // ArrayAccess
  // --------------------------------------------------------------------

  public function offsetExists($offset) {
    return isset($this->links[$offset]);
  }

  public function offsetGet($offset) {
    return isset($this->links[$offset])
      ? $this->links[$offset]
      : NULL;
  }

  public function offsetSet($offset, $value) {
    die("I don't want to be mutable"); }

  public function offsetUnset($offset) {
    die("I don't want to be mutable"); }


  // --------------------------------------------------------------------
  // Countable
  // --------------------------------------------------------------------

  public function count() {
    return count($this->links);
  }
}

