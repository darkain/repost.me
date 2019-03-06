<?php

  global $time, $repost_data, $db_query_score;

  $id = $repost_data['link_id'];

  $nobar = true;

  ob_start();

  $result = mysql_query(build_score_query(array('clause'=>"`link_id`='$id'", 'limit'=>'1')));
  require('item_block.php');
  mysql_free_result($result);

  $str = ob_get_clean();

  if (!isset($block)) {
    if (!headers_sent()) header('HTTP/1.0 400 Bad Request');
    echo 'ERROR: Invalid Repost.Me Link ID';
    return;
  }

  $id = int_to_short_string($block['link_id']);
  echo 'http://repost.me/' . $id;
  echo "\n\n";

  echo '<div class="repostme_ajax">';
  echo $str;
  echo '</div>';

?>
