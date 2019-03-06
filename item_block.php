<?php
  if (!isset($nobar)) $nobar = false;

  while ($data = mysql_fetch_assoc($result)) {
    $block = $data;

    echo '<div class="clear spacer">&nbsp;</div>';

    $id = int_to_short_string($data['link_id']);
    $repostme = 'http://repost.me/' . $id;

    echo '<div class="repost_line">';

    echo '<div class="repost_number" onclick="javascript:add_favorite(' . "'$id'" . ', this)">';
    if ($data['total'] > 9999) {
      echo '<span style="font-size:1.5em">' . $data['total'] . '</span>';
    } else if ($data['total'] > 999) {
      echo '<span style="font-size:1.8em">' . $data['total'] . '</span>';
    } else {
      echo '<span>' . $data['total'] . '</span>';
    }
    echo '<br />';
    echo '<img src="includes/default/logo-16.png" alt="Repost.Me" style="width:58px;height:16px" />';
    echo '</div>';

    echo '<div class="repostme_line_text">';


    echo '<a href="' . $repostme . '" target="_blank" class="urltext">' . $repostme . '</a> - ';
    echo '<a href="' . $repostme . '" target="_blank">';
    if ($data['link_title']) {
      echo htmlspecialchars($data['link_title']);
    } else {
      echo htmlspecialchars($data['link_url']);
    }
    echo '</a><br />';

    echo '<a href="stats.php?d=' . int_to_short_string($data['domain_id']) . '">';
    echo htmlspecialchars($data['domain_name']);
    echo '</a>';

    if (isset($data['user_post_time'])) {
      echo ' - Favorited: ' . time_since($data['user_post_time']);
    } else {
      echo ' - Posted: ' . time_since($data['link_post_time']);
    }
    echo ' ago<a href="' . $repostme . '" target="_blank" class="snap_shots">&nbsp;</a>';


    echo '<br />';

    if ($data['comment_total'] == 1) {
      echo '<a href="stats.php?r=' . $id . '">1 Comment</a> ';
    } else {
      echo '<a href="stats.php?r=' . $id . '">' . $data['comment_total'] . ' Comments</a> ';
    }


    if (!$nobar) {
      $bar_url   = htmlspecialchars(str_replace('"', '', str_replace("'", "", $data['link_url'])));
      $bar_title = htmlspecialchars(str_replace('"', '', str_replace("'", "", $data['link_title'])));
      echo '<script type="text/javascript"><!--' . "\r\n";
      echo 'repostme_text="Share this on <b>{site}</b>"' . "\r\n";
      echo 'repostme_norepost=true' . "\r\n";
      echo 'repostme_bar("' . $repostme . '");' . "\r\n";
      echo '//--></script>' . "\r\n";
    } else {
      echo '<span class="repostme_bar_replace">' . $repostme . '</span>';
    }

    echo "</div></div>\n";
    echo '<div class="clear">&nbsp;</div>';
  }

?>