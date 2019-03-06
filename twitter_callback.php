<?php

$session_delay = true;
require_once('config.php');
require_once('oauth/twitteroauth.php');


/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  //FAILJOIR!!
  echo 'Old OAuth Object. You need to refresh, n00b!';
  return;
}
 
/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
 
/* Request access tokens from twitter */
$token   = $connection->getAccessToken($_REQUEST['oauth_verifier']);
$details = $connection->get('account/verify_credentials');

//run account/verify_credentials
//it returns username stuff!

 
/* Save the access tokens. Normally these would be saved in a database for future use. */
//$_SESSION['access_token'] = $access_token;
 
/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);


//Should we display a WELCOME screen, or just the homepage?
$welcome = '';

 
/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code  &&
    isset($details->screen_name)   &&
    isset($token['oauth_token'])   &&
    isset($token['oauth_token_secret'])) {

  $token['oauth_token']        = mysql_safe($token['oauth_token']);
  $token['oauth_token_secret'] = mysql_safe($token['oauth_token_secret']);
  $screen_name = mysql_safe($details->screen_name);
  $screen_id   = mysql_safe($details->id);

  //FIND IT TOKEN ALREADY EXISTS
  $token_result = mysql_query("SELECT * FROM `user_twitter` WHERE `oauth_token`='$token[oauth_token]' AND `oauth_secret`='$token[oauth_token_secret]' LIMIT 1");

  //TOKEN DOES NOT EXIST
  if (mysql_num_rows($token_result) < 1) {

    //FIND IF USER ALREADY EXISTS
    $user_result = mysql_query("SELECT * FROM `users` WHERE `user_session`='$session' LIMIT 1");

    //USER DOES NOT EXIST, SO CREATE
    if (mysql_num_rows($user_result) < 1) {
      mysql_query("INSERT INTO `users` (`user_session`, `user_sestime`) VALUES ('$session', '$time')");
      $user_id = mysql_insert_id();

    //USER EXISTS
    } else {
      $user = mysql_fetch_assoc($user_result);
      $user_id = $user['user_id'];
    }
    mysql_free_result($user_result);

    
    //CREATE LINK BETWEEN USER AND TWITTER TOKEN
    mysql_query("UPDATE `user_twitter` SET `twitter_active`='0' WHERE `user_id`='$user_id'");
    mysql_query("INSERT INTO `user_twitter` (`twitter_id`, `user_id`, `oauth_token`, `oauth_secret`, `twitter_user`) VALUES ('$screen_id', '$user_id', '$token[oauth_token]', '$token[oauth_token_secret]', '$screen_name')");

    $welcome = 'twitter_welcome.php';


  //TOKEN ALREADY EXISTS, UPDATE USER TO NEW SESSION
  } else {
    $twitter = mysql_fetch_assoc($token_result);
    $user_id = $twitter['user_id'];
    mysql_query("UPDATE `users` SET `user_session`='$session', `user_sestime`='$time' WHERE `user_id`='$user_id' LIMIT 1");
    mysql_query("UPDATE `user_twitter` SET `twitter_active`='0' WHERE `user_id`='$user_id'");
    mysql_query("UPDATE `user_twitter` SET `twitter_active`='1' WHERE `oauth_token`='$token[oauth_token]' AND `oauth_secret`='$token[oauth_token_secret]' LIMIT 1");
  }


  mysql_free_result($token_result);


  if ($_SERVER['SERVER_NAME'] === 'web') {
    redirect_url('http://web/repostme/' . $welcome);
  } else {
    redirect_url('http://repost.me/' . $welcome);
  }

} else {
  /* Save HTTP status for error dialog on connnect page.*/
  //header('Location: ./clearsessions.php');
  echo "Bad Status Code: " . $connection->http_code;
  return;
}

?>