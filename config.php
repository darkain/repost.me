<?php
  error_reporting(0xEFFF);

  ini_set('session.use_cookies',      TRUE);
  ini_set('session.use_only_cookies', TRUE);
  ini_set('session.cookie_lifetime',  60*60*24*7);
  ini_set('session.cache_limite',     'private');
  try { @session_start(); } catch (Exception $ex) {}


  //pull time() once, so its synced everywhere within this single instance
  global $time;
  $time = time();

  if (isset($_SERVER['SERVER_NAME'])  &&  $_SERVER['SERVER_NAME'] === 'web') {
    $include = '';
    $website = 'http://web/repostme/';
    $testing = true;
  } else {
    $include = '../';
    $website = 'http://repost.me/';
    $testing = false;
  }

  define('TWITTER_OAUTH_CALLBACK', $website.'twitter_callback.php');
  define('TWITTER_CONSUMER_KEY', 'KoLDIWpOTP2jpwdaOTOoZg');
  define('TWITTER_CONSUMER_SECRET', 'ZZbmCtORhLbWvgCqSv0Icu2Y2ZJ6IMwiMKpCAdnRBdQ');

  $db_serv = 'localhost';
  $db_name = 'database';
  $db_user = 'username';
  $db_pass = 'password';

  $db = mysql_connect($db_serv, $db_user, $db_pass) or die('Database Connection Error: ' . mysql_error());
  mysql_select_db($db_name, $db) or die('Database Selection Error: ' . mysql_error());


  function mysql_safe($str) {
    return mysql_real_escape_string($str);
  }


  $session = mysql_safe(session_id());
  if (!isset($session_delay)) {
    try { @session_write_close(); } catch (Exception $ex) {}
  }


  $user = false;
  if ($session != '') {
    $result = mysql_query("SELECT * FROM `users` u LEFT JOIN `user_twitter` t ON (u.`user_id`=t.`user_id` AND `twitter_active`='1') WHERE `user_session`='$session' LIMIT 1");
    $user   = mysql_fetch_assoc($result);
    mysql_free_result($result);
  }


  function replace_whitespace($string) {
	return preg_replace('/\s+/', ' ', trim($string));
  }


  function quick_xml($str, $name, &$offset=0) {
    $pos1 = strpos($str, "<$name>", $offset);
    if ($pos1 === false) {
      $pos1 = strpos($str, "<$name", $offset);
      if ($pos1 !== false) $pos1 = strpos($str, ">", $pos1);
      if ($pos1 !== false) $pos1 += 1;
    } else {
      $pos1 += strlen($name) + 2;
    }
    if ($pos1 === false) return false;
    $pos2 = strpos($str, "</$name>", $pos1);
    if ($pos2 === false) return false;
    $offset = $pos2 + strlen($name) + 3;
    return trim(substr($str, $pos1, $pos2-$pos1));
  }


  function ip_to_int($ip=false) {
    if (is_array($ip)) {
      $ip0 = $ip[0];
      $ip1 = $ip[1];
      $ip2 = $ip[2];
      $ip3 = $ip[3];
    } else if (is_string($ip)) {
      sscanf($ip, '%d.%d.%d.%d', $ip0, $ip1, $ip2, $ip3);
    } else {
      sscanf($_SERVER['REMOTE_ADDR'], '%d.%d.%d.%d', $ip0, $ip1, $ip2, $ip3);
    }
    return ($ip0 << 24) | ($ip1 << 16) | ($ip2 << 8) | ($ip3);
  }


  function int_to_ip($int=false) {
    if (is_array($int)) {
      $ip0 = $ip[0];
      $ip1 = $ip[1];
      $ip2 = $ip[2];
      $ip3 = $ip[3];
    } else if (is_int($int)  ||  ((int)$int) != 0 ) {
      $ip0 = ($int >> 24) & 0x000000ff;
      $ip1 = ($int >> 16) & 0x000000ff;
      $ip2 = ($int >>  8) & 0x000000ff;
      $ip3 = ($int >>  0) & 0x000000ff;
    } else {
      return $_SERVER['REMOTE_ADDR'];
    }
    return sprintf('%d.%d.%d.%d', $ip0, $ip1, $ip2, $ip3);
  }


  function int_to_short_string($value) {
    $str = '';
    if ($value > 0xffffff) $str .= chr(($value >> 24) & 0xff);
    if ($value > 0x00ffff) $str .= chr(($value >> 16) & 0xff);
    if ($value > 0x0000ff) $str .= chr(($value >>  8) & 0xff);
    if ($value > 0x000000) $str .= chr(($value >>  0) & 0xff);
    $ret = base64_encode($str);
    $ret = str_replace('=', '',  $ret);
    $ret = str_replace('+', '-', $ret);
    $ret = str_replace('/', '_', $ret);
    return $ret;
  }



  function short_string_to_int($value) {
    $value = str_replace('-', '+', $value);
    $value = str_replace('_', '/', $value);
    if ((strlen($value) % 4) == 3) $value .= '=';
    if ((strlen($value) % 4) == 2) $value .= '==';
    $str = @base64_decode($value);
    if ($str !== false) switch (strlen($str)) {
      case 1: return ( (ord($str[0])) );
      case 2: return ( (ord($str[0]) <<  8) + (ord($str[1])) );
      case 3: return ( (ord($str[0]) << 16) + (ord($str[1]) <<  8) + (ord($str[2])) );
      case 4: return ( (ord($str[0]) << 24) + (ord($str[1]) << 16) + (ord($str[2]) << 8) + (ord($str[3])) );
    }
    return 0;
  }



  function redirect_url($url, $type=301, $die=true) {
    header("Location: $url", true, $type);
    if ($die) exit;
  }



  function url_get_contents($url, $followRedirect=true, $failOnError=true) {
    if (strpos($url, '/', 9) === false) $url .= '/';

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_AUTOREFERER,    true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_FAILONERROR,    $failOnError);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $followRedirect);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
    curl_setopt($ch, CURLOPT_TIMEOUT,        20);
    curl_setopt($ch, CURLOPT_USERAGENT,      ini_get('user_agent'));

    $contents        = curl_exec($ch);
    $data            = curl_getinfo($ch);
    $data['error']   = curl_error($ch);
    $data['errno']   = curl_errno($ch);
    $data['content'] = $contents;

    curl_close($ch);

    return $data;
  }



  function validate_url($url) {
//    $regex = "((https?|ftp)\:\/\/)?"; // SCHEME
    $regex = "((https?)\:\/\/)?"; // SCHEME
    $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?"; // User and Pass
    $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})"; // Host or IP
    $regex .= "(\:[0-9]{2,5})?"; // Port
    $regex .= "(\/([a-z0-9+%\$_-]\.?)+)*\/?"; // Path
    $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?"; // GET Query
    $regex .= "(\#[a-z0-9;:@&%=+\/\$_.-]*)?"; // Anchor
    return (preg_match("/^$regex$/i", $url) === 1);
  }



  function time_since($original) {
    global $time;
    if ($original == 0) return "Never";

    // array of time period chunks
    $chunks = array(
      array(60 * 60 * 24 * 365 , 'year'),
      array(60 * 60 * 24 * 30 , 'month'),
      array(60 * 60 * 24 * 7, 'week'),
      array(60 * 60 * 24 , 'day'),
      array(60 * 60 , 'hour'),
      array(60 , 'minute'),
    );

    $today = $time; /* Current unix time in seconds  */
    $since = $today - $original;

    if ($since == 1) return '1 second';
    if ($since < 60) return "$since seconds";

    // $j saves performing the count function each time around the loop
    for ($i = 0, $j = count($chunks); $i < $j; $i++) {
      $seconds = $chunks[$i][0];
      $name = $chunks[$i][1];
      // finding the biggest chunk (if the chunk fits, break)
      if (($count = floor($since / $seconds)) > 1) break;
    }

    $print = ($count == 1) ? '1 '.$name : "$count {$name}s";

    for ($x = $i+1;  $x < $j;  $x++) {
      // now getting the second item
      $seconds2 = $chunks[$x][0];
      $name2 = $chunks[$x][1];

      // add second item if it's count greater than 0
      if (($count2 = floor(($since - ($seconds * $count)) / $seconds2)) != 0) {
        $print .= ($count2 == 1) ? ', 1 '.$name2 : ", $count2 {$name2}s";
        break;
      }
    }

    return $print;
  }



  function build_score_query($opt=false, $how_long=false) {
    global $time;
    if ($how_long === false) $how_long = $time - 60*60*24 * 2;

    //COLUMNS
    $query  = 'SELECT SUM(count) AS total, links.*, domain.* ';
    if (isset($opt['cols'])) $query .= ',' . $opt['cols'];
    $query .= ' FROM (';

    //COUNT RECENT POSTS
    $query .= 'SELECT link_id, COUNT(link_id)*10 AS count FROM (SELECT * FROM `post` WHERE post_time>';
    $query .= $how_long;
    $query .= ' GROUP BY link_id, ip_address) postx GROUP BY link_id ';

    //COUNT RECENT FETCHES
    $query .= 'UNION SELECT link_id, COUNT(link_id)*3 AS count FROM (SELECT * FROM `fetch` WHERE fetch_time>';
    $query .= $how_long;
    $query .= ' GROUP BY link_id, ip_address) fetchx GROUP BY link_id ';

    //COUNT RECENT VIEWS
    $query .= 'UNION SELECT link_id, COUNT(link_id) AS count FROM (SELECT * FROM `view` WHERE view_time>';
    $query .= $how_long;
    $query .= ' GROUP BY link_id, ip_address) viewx GROUP BY link_id ';

    //ADDITIONAL UNIONS
    if (isset($opt['union'])) $query .= 'UNION ' . $opt['union'];

    //JOIN IN OTHER TABLES
    $query .= ') x JOIN links USING (link_id) JOIN domain USING (domain_id) ';
    if (isset($opt['join'])) $query .= 'JOIN ' . $opt['join'];

    //CLAUSES
    $query .= ' WHERE link_banned=0 AND domain_banned=0 ';
    if ((isset($opt['clause']))) $query .= 'AND ' . $opt['clause'];

    $query .= ' GROUP BY link_id ';

    if ((isset($opt['order']))) $query .= 'ORDER BY ' . $opt['order'];

    if ((isset($opt['limit']))) {
      $query .= ' LIMIT ' . $opt['limit'];
    } else {
      $query .= ' LIMIT 20';
    }

    return $query;
  }

?>
