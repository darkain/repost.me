<?php

  $comment_id = short_string_to_int(getvar('comment_id'));
  $comment    = int_to_short_string($comment_id);
  $text       = getvar('text', GET_BASIC);

  if ($text !== ''  &&  $comment_id > 0) {
    $repost = 'http://repost.me/' . $comment;
    $strlen = strlen($repost) + 1;
    $text   = substr($text, 0, 140 - $strlen);
    $tweet  = "$repost $text";
    $text   = mysql_safe($text);

    require_once('oauth/twitteroauth.php');
    $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET,
                                   $user['oauth_token'], $user['oauth_secret']);

    $parameters = array('status' => $tweet);
    $tweet = $connection->post('statuses/update', $parameters);

    if ($tweet) {
      $tweet_id = $tweet->id;
    } else {
      $tweet_id = 0;
    }


    $result = mysql_query("SELECT `link_id` FROM `links` WHERE `link_id`='$comment_id' LIMIT 1");
    if (mysql_num_rows($result) > 0) {
      $ip_address = ip_to_int();
      mysql_query("INSERT INTO `ip_address` (`ip_address`, `ip_comments`) VALUES ('$ip_address', '1') ON DUPLICATE KEY UPDATE `ip_comments`=`ip_comments`+1");

      $agent = '';
      if (isset($_SERVER['HTTP_USER_AGENT'])) $agent = $_SERVER['HTTP_USER_AGENT'];
      if ($agent !== '') {
        $agent_safe = mysql_safe( $agent );
        $agent_hash = mysql_safe( md5( $agent, true ) );  //return as BINARY string, SQL safe

        mysql_query("INSERT INTO `agents` (`agent_link_post`, `agent_hash`, `agent_text`) VALUES ('1', '$agent_hash', '$agent_safe') ON DUPLICATE KEY UPDATE `agent_id`=LAST_INSERT_ID(`agent_id`), `agent_link_post`=`agent_link_post`+1");
        $agent_id = mysql_insert_id();
      }

      mysql_query("INSERT INTO `comments` (`twitter_id`, `link_id`, `ip_address`, `agent_id`, `comment_time`, `tweet_id`, `tweet_text`) VALUES ('$user[twitter_id]', '$comment_id', '$ip_address', '$agent_id', '$time', '$tweet_id', '$text')");
      mysql_query("UPDATE `links` SET `comment_total`=`comment_total`+1 WHERE `link_id`='$comment_id' LIMIT 1");

      $query  = 'INSERT INTO `post` (`ip_address`, `post_time`, `link_id`, `referer_id`, `agent_id`, `user_id`) VALUES (';
      $query .= "'$ip_address',";
      $query .= "'$time',";
      $query .= "'$comment_id',";
      $query .= isset($referer_id) ? "'$referer_id',"   : 'NULL,';
      $query .= isset($agent_id)   ? "'$agent_id',"     : 'NULL,';
      $query .= $user !== false    ? "'$user[user_id]'" : 'NULL';
      $query .= ')';
      mysql_query($query);
    }
    mysql_free_result($result);

    redirect_url($website . 'stats.php?r=' . $comment . '#comments');
  }

?>
