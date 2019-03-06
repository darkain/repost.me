<?php

 //TODO: when making a new repost, auto-add it to favs!
 //TODO: if repost already exists, ADD TO FAVS

 //TODO: when making a new repost from the external toolbar (like from Darkain.com)
 //      it is no longer displaying the new shortened URL.  This MUST be fixed ASAP!

  require_once('config.php');
  require_once('getvar.php');


  function repost_response($text) {
    global $title, $noecho, $repost_data;

    if (stripos($text, 'error') !== false) {
      if (!headers_sent()) {
        header('HTTP/1.0 400 Bad Request');
      }
    }

    $repost_data['site'] = getvar('site');
    if ($repost_data['site'] !== '') {
      if (substr($text, 0, 17) === 'http://repost.me/') {
        if ($repost_data['site'] === 'repost.me') {
          require_once('make_info.php');
          exit;
        } else {
          require_once('sites.php');
          redirect_site($repost_data);
        }
      }
    }

    if (isset($noecho)) {
      return $text;
    } else {
      echo $text;

      if (getint('style') === 1) {
        echo "\n";
        if (isset($title)) echo $title;
      }
    }
    return false;
  }


  $url = getvar('url', GET_BASIC);

  if (strtolower(substr($url, 0, 3) === 'ref')  ||  $url === '') {
    if (isset($_SERVER['HTTP_REFERER'])) {
      $url =  $_SERVER['HTTP_REFERER'];
    }
  }


  ///////////////////////////////////////////////////////
  // IF NOTHING TO MAKE, REDIRECT TO THE HOMEPAGE
  ///////////////////////////////////////////////////////
  if ($url == ''  ||  $url === false) {
    redirect_url('http://repost.me/', 301);
  }

  



  ///////////////////////////////////////////////////////
  // ADD SCHEME IF IT DOES NOT ALREADY EXIST
  ///////////////////////////////////////////////////////
  if (substr($url, 0, 7) !== 'http://'   && 
      substr($url, 0, 8) !== 'https://') {
    $url = 'http://' . $url;
  }


  ///////////////////////////////////////////////////////
  // ALLOW FOR PROPER SPACES WITHIN THE URL
  ///////////////////////////////////////////////////////
  $url = str_replace(' ', '+', $url);


  ///////////////////////////////////////////////////////
  // MAKE SURE WE HAVE A VALID URL
  ///////////////////////////////////////////////////////
  $parts = false;
  $parts = @parse_url($url);
  if ($parts === false) {
    return repost_response('ERROR: Unable to parse URL');
  }


  ///////////////////////////////////////////////////////
  // VALIDATE FULLY QUALIFIED DOMAIN NAME TOO
  ///////////////////////////////////////////////////////
  if (!validate_url($url)) {
    return repost_response('ERROR: Unable to validate URL');
  }



  ///////////////////////////////////////////////////////
  // OH LOL, YOU CANNOT SHORTEN A REPOST.ME URL
  ///////////////////////////////////////////////////////
  if (stripos($parts['host'], 'repost.me') !== false) {
    $pos = strpos($url, '.me/');
    if ($pos !== false) {
      $id = short_string_to_int(substr($url, $pos+4));
      if ($id > 0) {
        $repost_data['link_id'] = $id;
        return repost_response('http://repost.me/' . int_to_short_string($id));
      }
    }
    return repost_response('ERROR: Invalid Repost.Me Link ID');
  }
  //TODO: move blacklisting into database
  if (stripos($parts['host'], '2chan') !== false) {
    return repost_response('ERROR: Invalid Domain');
  }
  if (stripos($parts['host'], '4chan') !== false) {
    return repost_response('ERROR: Invalid Domain');
  }
  if (stripos($parts['host'], '7chan') !== false) {
    return repost_response('ERROR: Invalid Domain');
  }



  ///////////////////////////////////////////////////////
  // LOG IP ADDRESS AND USER AGENT INFORMATION
  ///////////////////////////////////////////////////////
  $ip_address = ip_to_int();
  mysql_query("INSERT INTO `ip_address` (`ip_address`, `ip_link_post`) VALUES ('$ip_address', '1') ON DUPLICATE KEY UPDATE `ip_link_post`=`ip_link_post`+1");


  $agent = '';
  if (isset($_SERVER['HTTP_USER_AGENT'])) $agent = $_SERVER['HTTP_USER_AGENT'];
  if ($agent !== '') {
    $agent_safe = mysql_safe( $agent );
    $agent_hash = mysql_safe( md5( $agent, true ) );  //return as BINARY string, SQL safe

    mysql_query("INSERT INTO `agents` (`agent_link_post`, `agent_hash`, `agent_text`) VALUES ('1', '$agent_hash', '$agent_safe') ON DUPLICATE KEY UPDATE `agent_id`=LAST_INSERT_ID(`agent_id`), `agent_link_post`=`agent_link_post`+1");
    $agent_id = mysql_insert_id();

    mysql_query("INSERT INTO `ip_agent` (`ip_address`, `agent_id`, `ip_agent_link_post`) VALUES ('$ip_address', '$agent_id', '1') ON DUPLICATE KEY UPDATE `ip_agent_link_post`=`ip_agent_link_post`+1");
  }



  ///////////////////////////////////////////////////////
  // GET A QUICKLY SEARCHABLE HASH OF THE URL
  ///////////////////////////////////////////////////////
  $hash = mysql_safe( md5( $url, true ) );  //return as BINARY string, SQL safe

  $result = mysql_query("SELECT * FROM `links` WHERE `link_hash`='$hash' LIMIT 1");
  $hash_link = mysql_fetch_array($result);
  mysql_free_result($result);


  ///////////////////////////////////////////////////////
  // IF HASH ALREADY EXISTS...
  // RETURN THE EXISTING SHORTENED URL
  ///////////////////////////////////////////////////////
  if ($hash_link) {
    $title = $hash_link['link_title'];
    $repost_data = $hash_link;

    $hour = floor($time / 3600);
    mysql_query("INSERT INTO `stat_per_hour` (`hour`, `hour_post`) VALUES ('$hour', '1') ON DUPLICATE KEY UPDATE `hour_post`=`hour_post`+1");

    mysql_query("UPDATE `domain` SET `domain_post`=`domain_post`+1 WHERE `domain_id`='$hash_link[domain_id]'");
    mysql_query("UPDATE `links` SET `link_post`=`link_post`+1 WHERE `link_id`='$hash_link[link_id]'");

    $query  = 'INSERT INTO `post` (`ip_address`, `post_time`, `link_id`, `referer_id`, `agent_id`) VALUES (';
    $query .= "'$ip_address',";
    $query .= "'$time',";
    $query .= "'$hash_link[link_id]',";
    $query .= isset($referer_id) ? "'$referer_id'," : 'NULL,';
    $query .= isset($agent_id)   ? "'$agent_id'"    : 'NULL';
    $query .= ')';
    mysql_query($query);

    return repost_response( 'http://repost.me/' . int_to_short_string($hash_link['link_id']) );
  }




  //TODO: create a blacklist of domains... example: tinyurl.com, repost.me, 4chan.org, rafb.net, bit.ly, fav.me
  //TODO: parse subdomains out of domains!
  //TODO: follow HTTP redirects and use the final URL! - this eliminates the need to filter some URLs



  ///////////////////////////////////////////////////////
  // OPEN THE WEB SITE, FOLLOWING REDIRECTS AS NEEDED
  ///////////////////////////////////////////////////////
  ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; http://repost.me/) RepostMe/1.0 (KHTML, like Gecko)');
  $urldata = url_get_contents($url);
  if ( (((int)$urldata['errno']) !== 0)  ||  (((int)$urldata['http_code']) !== 200) ) {
    return repost_response('URL Error: ' . $urldata['error']);
  }

  $url_safe = mysql_safe($urldata['url']);
  $hash     = mysql_safe( md5( $url, true ) );  //return as BINARY string, SQL safe



  ///////////////////////////////////////////////////////
  // PULL THE TITLE OF THE WEB SITE
  ///////////////////////////////////////////////////////
  $title = quick_xml($urldata['content'], 'title');
  if ($title === false) {
    return repost_response('Error parsing contents of URL');
  }

  $title = htmlspecialchars_decode($title);      //Convert things like &lt; and &gt; to < and >
  $title = html_entity_decode($title);           //Convert things like &#20; to [space]
  $title = str_replace(chr(0xA0), ' ', $title);  //Convert &nbsp; to [space]
  $title = replace_whitespace($title);           //Remove consecutive white space characters
  $title_safe = mysql_safe($title);              //Make the string safe for MySQL insertion


	
  ///////////////////////////////////////////////////////
  // INSERT DOMAIN INFORMATION INTO DATABASE
  ///////////////////////////////////////////////////////
  mysql_query("INSERT INTO `domain` (`domain_name`, `domain_post_time`) VALUES ('$parts[host]', '$time') ON DUPLICATE KEY UPDATE `domain_id`=LAST_INSERT_ID(`domain_id`), `domain_post`=`domain_post`+1");
  $domain_id = mysql_insert_id();


  ///////////////////////////////////////////////////////
  // INSERT URL INFORMATION INTO DATABASE
  ///////////////////////////////////////////////////////
  mysql_query("INSERT INTO `links` (`link_hash`, `link_url`, `link_title`, `link_post_time`, `domain_id`) VALUES ('$hash', '$url_safe', '$title_safe', '$time', '$domain_id') ON DUPLICATE KEY UPDATE `link_id`=LAST_INSERT_ID(`link_id`), `link_post`=`link_post`+1");



  ///////////////////////////////////////////////////////
  // CONVERT DATABASE ID INTO REPOST.ME ID
  ///////////////////////////////////////////////////////
  $repost_data['link_id']    = mysql_insert_id();
  $repost_data['link_url']   = $url;
  $repost_data['link_title'] = $title;
  $url_short = 'http://repost.me/' . int_to_short_string($repost_data['link_id']);
//  repost_response($url_short);



  ///////////////////////////////////////////////////////
  // TRACK THIS POSTING
  ///////////////////////////////////////////////////////
  $query  = 'INSERT INTO `post` (`ip_address`, `post_time`, `link_id`, `referer_id`, `agent_id`, `user_id`) VALUES (';
  $query .= "'$ip_address',";
  $query .= "'$time',";
  $query .= "'$repost_data[link_id]',";
  $query .= isset($referer_id) ? "'$referer_id',"   : 'NULL,';
  $query .= isset($agent_id)   ? "'$agent_id',"     : 'NULL,';
  $query .= $user !== false    ? "'$user[user_id]'" : 'NULL';
  $query .= ')';
  mysql_query($query);



  ///////////////////////////////////////////////////////
  // Note: echo out more than 256 bytes total, as some
  // browsers REQUIRE this BEFORE they begin to process
  // a response... AND FLUSH THE RESULTS!!!
  ///////////////////////////////////////////////////////
//  for ($i=0; $i<10; $i++) repost_response('                          ');
//  flush();



  ///////////////////////////////////////////////////////
  // SEND RESULTS TO TWITTER!! tweet tweet
  ///////////////////////////////////////////////////////
  /*
  if (getint('style') === 0) {
    require_once('../oauth/oauthconfig.php');

    $status   = substr("$url_short $title", 0, 136) . ' #fb';
    $tweet_id = 0;

    if ($parts['host'] === 'www.darkain.com') {
      $con_darkaincom = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $darkaincom_token['oauth_token'], $darkaincom_token['oauth_token_secret']);
      $tweet_data = $con_darkaincom->post('statuses/update', array('status'=>$status));
      if (is_object($tweet_data)) {
        $tweet_id     = (float)$tweet_data->id;
        $con_repostme = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $repostme_token['oauth_token'], $repostme_token['oauth_token_secret']);
        $con_repostme->post('statuses/retweet/' . $tweet_id);
      }
    } else {
      $con_repostme = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $repostme_token['oauth_token'], $repostme_token['oauth_token_secret']);
      $tweet_data = $con_repostme->post('statuses/update', array('status'=>$status));
      if (is_object($tweet_data)) {
        $tweet_id = (float)$tweet_data->id;
      }
    }

    if ($tweet_id > 0) {
      mysql_query("UPDATE `links` SET `twitter_id`='$tweet_id' WHERE `link_id`='$repost_data[link_id]' LIMIT 1");
    }
  }
  */


  return repost_response($url_short);

?>