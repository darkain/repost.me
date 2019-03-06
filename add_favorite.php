<?php

  require('config.php');
  require('getvar.php');

  $id = short_string_to_int(getvar('id'));
  if ($user === false  ||  $id < 1) return;

  mysql_query("INSERT INTO `user_post` (`user_id`, `link_id`, `user_post_time`) VALUES ('$user[user_id]', '$id', '$time') ON DUPLICATE KEY UPDATE `user_post_time`='$time'");

?>
