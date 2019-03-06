<?php
  $button = 'search';

  require_once('config.php');
  require_once('getvar.php');

  $url_short = 'http://repost.me/';

  $link   = getvar('r');
  $domain = getvar('d');

  $link_id   = short_string_to_int($link);
  $domain_id = short_string_to_int($domain);

  if ($link_id > 0) {
    $url_short .= int_to_short_string($link_id);
    $page_title = 'Stats For: http://repost.me/' . int_to_short_string($id);
  } else if ($domain_id > 0) {
    $page_title = 'Domain Stats';
  } else {
    $page_title = 'Statistics';
  }

  require_once('header.php');


  if ($link_id > 0) {
    require_once('stats/link.php');
  } else if ($domain_id > 0) {
    require_once('stats/domain.php');
  } else {
    require_once('stats/weekly.php');
  }

  require_once('footer.php');
?>
