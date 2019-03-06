<?php

  function repostme($url) {
    $url  = 'http://make.repost.me/?url=' . rawurlencode($url);
    $file = false;
    $file = @fopen($url, 'rb');
    if (!$file) return false;
    $data = '';
    while (!feof($file)) $data .= fread($file, 1024);
    fclose($file);
    if (substr($data, 0, 17) !== 'http://repost.me/') return false;
    return $data;
  }

  include('twitter.php');

  $twitter = new summize();
  $data    = $twitter->trends();
  if (!$data) {
    echo "Error parsing Twitter Trends!\r\n<br />";
    return;
  }

  $trends = $data->trends;
  $count  = count($trends);
  $id     = rand(0, $count);
  $item   = $trends[$id]->url;

  repostme($item);

?>
