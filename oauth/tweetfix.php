<?php

  require_once('config.php');
  require_once('oauth/twitteroauth.php');

  $result = mysql_query("SELECT * FROM `users` u LEFT JOIN `user_twitter` t ON (u.`user_id`=t.`user_id` AND `twitter_active`='1') WHERE `twitter_id`='0' ");
  while ($user = mysql_fetch_assoc($result)) {
    $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET,
                                   $user['oauth_token'], $user['oauth_secret']);
    $data = $connection->get('account/verify_credentials');

    $id = $data->id;
    mysql_query("UPDATE `user_twitter` SET `twitter_id`='$id' WHERE `user_id`='$user[user_id]' LIMIT 1");
  }
  mysql_free_result($result);

?>