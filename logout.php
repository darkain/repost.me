<?php

  require_once('config.php');

  if ($user !== false) {
    mysql_query("UPDATE `users` SET `user_session`=NULL WHERE `user_session`='$user[user_session]'");
  }

  if ($_SERVER['SERVER_NAME'] === 'web') {
    redirect_url('http://web/repostme/');
  } else {
    redirect_url('http://repost.me/');
  }

?>