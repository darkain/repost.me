<?php
  $button = 'search';

  require_once('config.php');
  require_once('getvar.php');

  $query  = getvar('q');
  $page_title = ' Search: ' . htmlspecialchars($query);

  require_once('header.php');
?>


<div class="content">


<form action="<?php echo $website; ?>" method="GET"><div>
<input id="txtSearch" name="q" value="<?php echo htmlspecialchars($query); ?>" />
<input id="btnSearch" index="-1" type="submit" value="Search Links" />
</div></form>


<div style="margin-top:1em; font-size: 1.5em">
Repost.Me Search Results
</div>


<div class="repost_feed">
<?php
  $search = array();
  $search['order']  = 'total DESC, link_id DESC';
  $search['union']  = 'SELECT link_id, 0 AS count FROM `links`';
  $search['clause'] = "((`link_title` COLLATE utf8_general_ci LIKE '%$query%') OR (`link_url` COLLATE utf8_general_ci LIKE '%$query%'))";
  $result = mysql_query(build_score_query($search));
  require('item_block.php');
  mysql_free_result($result);

/*
  $result = mysql_query("SELECT DISTINCT * FROM `links` WHERE (`link_title` COLLATE utf8_general_ci LIKE '%$query%') OR (`link_url` COLLATE utf8_general_ci LIKE '%$query%') ORDER BY `link_fetch` DESC, `link_id` DESC LIMIT 15");
  while ($data = mysql_fetch_assoc($result)) {
    $id = int_to_short_string($data['link_id']);
    $repostme = 'http://repost.me/' . $id;
    echo '<a href="' . $repostme . '" target="_blank" class="urltext">' . $repostme . '</a>';
    if ($data['link_title']) {
       echo ' <a href="' . $repostme . '" target="_blank">' . $data['link_title']. '</a>';
    } else {
       echo ' <a href="' . $repostme . '" target="_blank">' . $data['link_url']. '</a>';
    }
    echo '<div style="font-size:0.8em; line-height:1em; color:#888">';
    echo '<a href="http://repost.me/stats.php?r=' . $id . '">View Stats</a>';
    echo ' - Posted: ';
    echo time_since($data['link_post_time']);
    echo ' ago<a href="' . $repostme . '" target="_blank" class="snap_shots">&nbsp;</a>';
    echo "<br /><br /></div>\n";
  }
  mysql_free_result($result);
*/
?>

</div>

</div>



<?php
  require_once('footer.php');
?>