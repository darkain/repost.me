<?php
  require_once('config.php');
  require_once('getvar.php');
  require_once('sites.php');

  $uri = getvar('url');
  if ($uri == '') $uri = substr($_SERVER['REQUEST_URI'], 1);
  if (strpos($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
  if (strpos($uri, '&')) $uri = substr($uri, 0, strpos($uri, '&'));
  $id = short_string_to_int($uri);


  $result = mysql_query("SELECT * FROM `links` WHERE `link_id`='$id'");
  $data   = mysql_fetch_assoc($result);
  mysql_free_result($result);

  if (!$data) {
    redirect_url('http://repost.me/', 302);
  }




  ///////////////////////////////////////////////////////
  // LOG IP ADDRESS INFORMATION
  ///////////////////////////////////////////////////////
  $ip_address = ip_to_int();
  mysql_query("INSERT INTO `ip_address` (`ip_address`, `ip_link_fetch`) VALUES ('$ip_address', '1') ON DUPLICATE KEY UPDATE `ip_link_fetch`=`ip_link_fetch`+1");



  ///////////////////////////////////////////////////////
  // LOG USER AGENT INFORMATION
  ///////////////////////////////////////////////////////
  $agent    = '';
  $agent_id = 0;
  if (isset($_SERVER['HTTP_USER_AGENT'])) $agent = $_SERVER['HTTP_USER_AGENT'];
  if ($agent !== '') {
    $agent_safe = mysql_safe( $agent );
    $agent_hash = mysql_safe( md5( $agent, true ) );  //return as BINARY string, SQL safe

    mysql_query("INSERT INTO `agents` (`agent_link_fetch`, `agent_hash`, `agent_text`) VALUES ('1', '$agent_hash', '$agent_safe') ON DUPLICATE KEY UPDATE `agent_id`=LAST_INSERT_ID(`agent_id`), `agent_link_fetch`=`agent_link_fetch`+1");
    $agent_id = mysql_insert_id();

    //Tie this user's USER AGENT to this user's IP address
    mysql_query("INSERT INTO `ip_agent` (`ip_address`, `agent_id`, `ip_agent_link_fetch`) VALUES ('$ip_address', '$agent_id', '1') ON DUPLICATE KEY UPDATE `ip_agent_link_fetch`=`ip_agent_link_fetch`+1");
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

    //Tie this referer to this particular link
    mysql_query("INSERT INTO `referer_link` (`referer_id`, `link_id`, `referer_link_count`) VALUES ('$referer_id', '$id', '1') ON DUPLICATE KEY UPDATE `referer_link_count`=`referer_link_count`+1");
  }



  ///////////////////////////////////////////////////////
  // TIE THE USER AGENT TO THE REFERER URL
  ///////////////////////////////////////////////////////
  if ( ($agent_id > 0)  &&  ($referer_id > 0) ) {
    mysql_query("INSERT INTO `referer_agent` (`referer_id`, `agent_id`, `referer_agent_count`) VALUES ('$referer_id', '$agent_id', '1') ON DUPLICATE KEY UPDATE `referer_agent_count`=`referer_agent_count`+1");
  }


  ///////////////////////////////////////////////////////
  // UPDATE LINK FETCH COUNT AND DOMAIN FETCH COUNT
  ///////////////////////////////////////////////////////
  $hour = floor($time / 3600);
  mysql_query("INSERT INTO `stat_per_hour` (`hour`, `hour_fetch`) VALUES ('$hour', '1') ON DUPLICATE KEY UPDATE `hour_fetch`=`hour_fetch`+1");

  mysql_query("UPDATE `domain` SET `domain_fetch`=`domain_fetch`+1, `domain_fetch_time`='$time' WHERE `domain_id`='$data[domain_id]' LIMIT 1");
  mysql_query("UPDATE `links` SET `link_fetch`=`link_fetch`+1, `link_fetch_time`='$time' WHERE `link_id`='$id' LIMIT 1");



  ///////////////////////////////////////////////////////
  // TRACK THIS FETCHING
  ///////////////////////////////////////////////////////
  if ($id > 0) {
    $query  = 'INSERT INTO `fetch` (`ip_address`, `fetch_time`, `link_id`, `referer_id`, `agent_id`, `user_id`) VALUES (';
    $query .= "'$ip_address',";
    $query .= "'$time',";
    $query .= "'$id',";
    $query .= ($referer_id > 0) ? "'$referer_id',"    : 'NULL,';
    $query .= ($agent_id   > 0) ? "'$agent_id',"      : 'NULL,';
    $query .= $user !== false    ? "'$user[user_id]'" : 'NULL';
    $query .= ')';
    mysql_query($query);
  }





  $redirect = false;
  $preview  = false;

  //push headers to browser as fast as possible
  //then continue to work in the background
  if ( (stripos($agent, 'phone') !== false)
   ||  (stripos($agent, 'mobile') !== false)
   ||  (stripos($agent, 'midp') !== false) ) {
    $redirect = true;
  } else if ( (stripos($agent, 'AppleWebKit/') !== false)
           || (stripos($agent, 'Chrome/')      !== false) 
           || (stripos($agent, 'Safari/')      !== false) 
           || (stripos($agent, 'Gecko/')       !== false) 
           || (stripos($agent, 'Konqueror/')   !== false) 
           || (stripos($agent, 'Opera/')       !== false) 
           || (stripos($agent, 'MSIE')         !== false) ) {
    $redirect = false;
  } else if (stripos($agent, 'Facebook') !== false) {
    $redirect = false;
    $preview  = true;
  } else {
    //$redirect = true;
    $redirect = false;
    $preview  = true;
  }

  $redirect = true;  //FORCED TO TRUE FOR THE TIME BEING

  if (isset($_GET['site'])) {
    $data['site'] = $_GET['site'];
    redirect_site($data);
  }


  if ($redirect) redirect_url($data['link_url'], 301, false);


  if (1) {
    $inline_frame = $data['link_url'];
    $inline_frame = 'http://web/blank.php';
    $_POST['r'] = int_to_short_string($id);
    $_POST['t'] = $data['link_title'];
    $_POST['u'] = $data['link_url'];
    require($include.'bar/index.php');
    return;
  }


  echo '<html><head><title>' . htmlspecialchars($data['link_title']) . '</title></head>';

  if (!$redirect  &&  !$preview) {
    //echo '<frameset cols="130,*" border="0" framespacing="0" frameborder="no">';
    echo '<frameset rows="38,*" border="0" framespacing="0" frameborder="no">';
      echo '<frame id="repostme_bar" src="http://bar.repost.me/?r=' . int_to_short_string($id) . '&amp;t=' . rawurlencode($data['link_title']) . '&amp;u=' . rawurlencode($data['link_url']) . '" />';
      echo '<frame id="repostme_site" src="' . htmlspecialchars($data['link_url']) . '" />';
    echo '</frameset>';
    echo '<noframes>';
  }

  echo '<body>';
  echo '<a href="' . htmlspecialchars($data['link_url']) . '">';
  echo htmlspecialchars($data['link_url']);
  echo ' - ';
  echo htmlspecialchars($data['link_title']);
  echo '</a>';
  echo '</body>';

  if (!$redirect  &&  !$preview) echo '</noframes>';
  echo '</html>';
?>