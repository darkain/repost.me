<?php

  $path = '';
  if (isset($_SERVER['REQUEST_URI'])) $path = $_SERVER['REQUEST_URI'];
  if (substr($path, 0, 1) == '/') $path = substr($path, 1);

  if ($path === 'edge.js') {
  } else if ($path === 'stable.js') {
  } else if ($path === 'testing.js') {
  } else if ($path === '0.1.js') {
  } else if ($path === '0.2.js') {
  } else if ($path === '0.3.js') {
  } else {
    header('Location: http://repostme.darkain.com/');
    exit;
  }

  header('Content-Type: text/javascript');
  header('Expires: access plus 216000 seconds');
  header('Cache-Control: max-age=216000, private');

  readfile($path);


  require('../public_html/config.php');


  $id = 0;
  $domain_id = 0;


  ///////////////////////////////////////////////////////
  // LOG IP ADDRESS INFORMATION
  ///////////////////////////////////////////////////////
  $ip_address = ip_to_int();
  mysql_query("INSERT INTO `ip_address` (`ip_address`, `ip_link_view`) VALUES ('$ip_address', '1') ON DUPLICATE KEY UPDATE `ip_link_view`=`ip_link_view`+1");



  ///////////////////////////////////////////////////////
  // LOG USER AGENT INFORMATION
  ///////////////////////////////////////////////////////
  $agent    = '';
  $agent_id = 0;
  if (isset($_SERVER['HTTP_USER_AGENT'])) $agent = $_SERVER['HTTP_USER_AGENT'];
  if ($agent !== '') {
    $agent_safe = mysql_safe( $agent );
    $agent_hash = mysql_safe( md5( $agent, true ) );  //return as BINARY string, SQL safe

    mysql_query("INSERT INTO `agents` (`agent_link_view`, `agent_hash`, `agent_text`) VALUES ('1', '$agent_hash', '$agent_safe') ON DUPLICATE KEY UPDATE `agent_id`=LAST_INSERT_ID(`agent_id`), `agent_link_view`=`agent_link_view`+1");
    $agent_id = mysql_insert_id();

    //Tie this user's USER AGENT to this user's IP address
    mysql_query("INSERT INTO `ip_agent` (`ip_address`, `agent_id`, `ip_agent_link_view`) VALUES ('$ip_address', '$agent_id', '1') ON DUPLICATE KEY UPDATE `ip_agent_link_view`=`ip_agent_link_view`+1");
  }



  ///////////////////////////////////////////////////////
  // LOG REFERER URL INFORMATION
  ///////////////////////////////////////////////////////
  $referer    = '';
  $referer_id = 0;
  if (isset($_SERVER['HTTP_REFERER'])) $referer = $_SERVER['HTTP_REFERER'];
  if ($referer !== '') {
    $referer_safe = mysql_safe( $referer );
    $referer_hash = mysql_safe( md5( $referer, true ) );  //return as BINARY string, SQL safe

    mysql_query("INSERT INTO `referer` (`referer_url`, `referer_hash`, `referer_count`) VALUES ('$referer_safe', '$referer_hash', '1') ON DUPLICATE KEY UPDATE `referer_id`=LAST_INSERT_ID(`referer_id`), `referer_count`=`referer_count`+1");
    $referer_id = mysql_insert_id();

    //Tie this referer to this user's IP address
    mysql_query("INSERT INTO `referer_ip` (`referer_id`, `ip_address`, `referer_ip_count`) VALUES ('$referer_id', '$ip_address', '1') ON DUPLICATE KEY UPDATE `referer_ip_count`=`referer_ip_count`+1");

    $result = mysql_query("SELECT `link_id`, `domain_id` FROM `links` WHERE `link_hash`='$referer_hash' LIMIT 1");
    $data   = mysql_fetch_assoc($result);
    mysql_free_result($result);

    if ($data !== false) {
      $id        = $data['link_id'];
      $domain_id = $data['domain_id'];
    }

    if ($id > 0) {
      //Tie this referer to this particular link
      mysql_query("INSERT INTO `referer_link` (`referer_id`, `link_id`, `referer_link_count`) VALUES ('$referer_id', '$id', '1') ON DUPLICATE KEY UPDATE `referer_link_count`=`referer_link_count`+1");
    }
  }



  ///////////////////////////////////////////////////////
  // TIE THE USER AGENT TO THE REFERER URL
  ///////////////////////////////////////////////////////
  if ( ($agent_id > 0)  &&  ($referer_id > 0) ) {
    mysql_query("INSERT INTO `referer_agent` (`referer_id`, `agent_id`, `referer_agent_count`) VALUES ('$referer_id', '$agent_id', '1') ON DUPLICATE KEY UPDATE `referer_agent_count`=`referer_agent_count`+1");
  }


  ///////////////////////////////////////////////////////
  // UPDATE LINK VIEW COUNT AND DOMAIN VIEW COUNT
  ///////////////////////////////////////////////////////
  $hour = floor($time / 3600);
  mysql_query("INSERT INTO `stat_per_hour` (`hour`, `hour_view`) VALUES ('$hour', '1') ON DUPLICATE KEY UPDATE `hour_view`=`hour_view`+1");

  if ($id > 0) {
    mysql_query("UPDATE `domain` SET `domain_view`=`domain_view`+1, `domain_view_time`='$time' WHERE `domain_id`='$domain_id' LIMIT 1");
    mysql_query("UPDATE `links` SET `link_view`=`link_view`+1, `link_view_time`='$time' WHERE `link_id`='$id' LIMIT 1");
  }



  ///////////////////////////////////////////////////////
  // TRACK THIS VIEWING
  ///////////////////////////////////////////////////////
  if ($id > 0) {
    $query  = 'INSERT INTO `view` (`ip_address`, `view_time`, `link_id`, `referer_id`, `agent_id`, `user_id`) VALUES (';
    $query .= "'$ip_address',";
    $query .= "'$time',";
    $query .= "'$id',";
    $query .= ($referer_id > 0) ? "'$referer_id',"    : 'NULL,';
    $query .= ($agent_id   > 0) ? "'$agent_id',"      : 'NULL,';
    $query .= $user !== false    ? "'$user[user_id]'" : 'NULL';
    $query .= ')';
    mysql_query($query);
  }

?>