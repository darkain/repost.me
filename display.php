<div class="repost_feed">

<?php
  require_once('config.php');
  require_once('getvar.php');

  $page = getvar('page');
  if (!isset($nobar)) $nobar = true;


  if ($page == 'new') {
    $result = mysql_query(build_score_query(array('order'=>'link_id DESC')));
    require('item_block.php');
    mysql_free_result($result);



  } else if ($page == 'mine') {
    if ($user !== false) {
      $mine = array();
      $mine['union']  = "SELECT link_id, 0 AS count FROM `user_link` WHERE `user_id`='$user[user_id]'";
      $mine['join']   = '`user_link` USING (`link_id`)';
      $mine['clause'] = "`user_id`='$user[user_id]'";
      $mine['order']  = 'link_id DESC';

      $result = mysql_query(build_score_query($mine));
      if (mysql_num_rows($result) > 0) {
        require('item_block.php');
      } else {
        require('no_own.php');
      }
      mysql_free_result($result);
    } else {
      require('no_login.php');
    }



  } else if ($page == 'favs') {
    if ($user !== false) {
      $favs = array();
      $favs['cols']   = 'user_post_time';
      $favs['union']  = "SELECT link_id, 0 AS count FROM `user_post` WHERE `user_id`='$user[user_id]'";
      $favs['join']   = '`user_post` USING (`link_id`)';
      $favs['clause'] = "`user_id`='$user[user_id]'";
      $favs['order']  = 'user_post_time DESC';

      $result = mysql_query(build_score_query($favs));
      if (mysql_num_rows($result) > 0) {
        require('item_block.php');
      } else {
        require('no_fav.php');
      }
      mysql_free_result($result);
    } else {
      require('no_login.php');
    }



  } else {
    $result = mysql_query(build_score_query(array('order'=>'total DESC, link_id DESC')));
    require('item_block.php');
    mysql_free_result($result);
  }

?>

</div>
