<?php

  $data = false;
  $result = mysql_query("SELECT * FROM `links` l, `domain` d WHERE l.domain_id=d.domain_id AND l.link_id='$link_id' LIMIT 1");
  if ($result) $data = mysql_fetch_assoc($result);
  mysql_free_result($result);

  if ($data === false) {
    echo '<h1 class="error">Invalid Link ID</h1>';
    return;
  }

  if ($data['link_banned']  ||  $data['domain_banned']) {
    echo '<h1 class="error">Link has been BANNED</h1>';
    return;
  }



  echo '<div class="content">';
  $url = int_to_short_string($data['link_id']);
  $dmn = int_to_short_string($data['domain_id']);
  echo '<h1><a class="stats_header" href="http://repost.me/' . $url . '" target="_blank">http://repost.me/' . $url . '</a></h1>';
  echo '<table class="stats_table">';

  echo '<tr><th>Page Title</th><td>'        . htmlspecialchars($data['link_title']) . '</td></tr>';
  echo '<tr><th>Domain</th><td><a href="stats.php?d=' . $dmn . '">' . htmlspecialchars($data['domain_name']) . '</td></tr>';
  echo '<tr><th>Full URL</th><td><a href="' . htmlspecialchars($data['link_url']) . '" target="_blank">' . htmlspecialchars($data['link_url']) . '</a></td></tr>';
  echo '<tr><th></th><td></td></tr>';

  echo '</table>';
  echo '</div>';
?>




<a name="comments"></a>
<div class="content" style="margin-top:1em;padding-bottom:1em">
<script type="text/javascript">
var text_changing = false;
function text_change(element) {
  if (text_changing) return;
  text_changing = true;

  var text = element.value;
  var repo = 'http://repost.me/<?php echo $url ?> ';
  var len  = text.length;
  var rlen = 140 - repo.length;

  if (len > rlen) {
    text = text.substr(0, rlen);
    element.value = text;
    len = rlen;
  }

  var span = getElement('char_count');
  if (span) span.innerHTML = rlen-len;

  text_changing = false;
}
</script>

<form action="<?php echo $website; ?>" method="post"><div class="stats_other">
<span id="char_count"><?php echo (140 - strlen("http://repost.me/$url ")); ?></span>
<div style="padding-top:1em">Leave your comments for this page</div>
<input type="hidden" name="comment_id" value="<?php echo $url; ?>" />
<textarea name="text" style="font-size:1em;width:95%;height:2.5em"
          <?php if ($user === false) echo 'disabled="disabled"' ?>
          onchange="javascript:text_change(this)" onkeydown="javascript:text_change(this)"
          onkeyup="javascript:text_change(this)" onkeypress="javascript:text_change(this)">
</textarea>
<div style="text-align:right; padding-right:25px">
<?php
  if ($user === false) {
    echo '<input type="button" disabled="disabled" value="You must login before you can post comments" />';
  } else {
    echo '<input type="submit" value="Tweet My Comment" />';
  }
?>
</div>
</div></form>


<?php
  echo '<b class="stats_other">Comments</b><br />';
  $result = mysql_query("SELECT * FROM `comments` LEFT JOIN `user_twitter` USING (`twitter_id`) WHERE `link_id`='$link_id' ORDER BY `comment_id` DESC");
  if (mysql_num_rows($result) > 0) {
    echo '<div style="padding:0 1.5em">';
    while ($comment = mysql_fetch_assoc($result)) {
      echo '<br />';
      echo '<img src="http://repostme.info/16/twitter.png" alt="Twitter" style="width:16px;height:16px" /> ';

      echo '@<a href="http://twitter.com/' . htmlspecialchars($comment['twitter_user']) . '">';
        echo htmlspecialchars($comment['twitter_user']);
      echo '</a> said: ';
      echo '<i>';
        $text = htmlspecialchars($comment['tweet_text']);
        $text = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]", "<a href=\"\\0\">\\0</a>", $text);
        $text = preg_replace('/\@(.[[:alnum:]_]*?)([^[:alnum:]_])/is', '@<a href="http://twitter.com/$1">$1</a> ', $text);
        $text = preg_replace('/\#(.[[:alnum:]_]*?)([^[:alnum:]_])/is', '<a href="http://search.twitter.com/search?q=%23$1">#$1</a> ', $text);
        echo $text;
      echo '</i>';
      echo '<br /> ';
    }
    echo '</div>';
  } else {
    echo '<i class="stats_other">No comments habe been added for this <span style="color:#f00">Repost.Me</span> URL yet</i><br />';
  }
  mysql_free_result($result);
?>

</div>





<?php
  $query = array();
  $query['clause'] = "domain_id=$data[domain_id] AND link_id!='$link_id'";
  $query['order']  = 'RAND()';
  $query['union']  = 'SELECT link_id, 0 AS count FROM `links` WHERE ' . $query['clause'];
  $query['limit']  = '5';

  $result = mysql_query(build_score_query($query));
  if (mysql_num_rows($result) > 0) {
    echo '<div class="content" style="margin-top:1em">';
      echo '<b class="stats_other">Other pages from the domain <a href="stats.php?d=' . $dmn . '">' . htmlspecialchars($data['domain_name']) . '</a></b>';
      echo '<div class="repost_feed">';
        require('item_block.php');
      echo '</div>';
    echo '</div>';
  }
  mysql_free_result($result);


?>