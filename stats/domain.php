<?php

  $result = mysql_query("SELECT * FROM `domain` WHERE `domain_id`='$domain_id' AND `domain_banned`='0' LIMIT 1");
  $domain = mysql_fetch_assoc($result);
  mysql_free_result($result);

  if ($domain === false) {
    echo '<h1 class="error">Invalid Domain ID</h1>';
    return;
  }

  echo '<div class="content">';

  echo '<h1 class="stats_header">' . htmlspecialchars($domain['domain_name']) . '</h1>';


  $query = array();
  $query['clause'] = "domain_id=$domain_id";
  $query['order']  = 'link_id DESC';
  $query['union']  = 'SELECT link_id, 0 AS count FROM `links` WHERE ' . $query['clause'];

  $result = mysql_query(build_score_query($query));
  if (mysql_num_rows($result) > 0) {
    echo '<div class="repost_feed">';
    require('item_block.php');
    echo '</div>';
  }
  mysql_free_result($result);

  echo '</div>';

?>