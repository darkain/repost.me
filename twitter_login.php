<?php


$session_delay = true;
require_once('config.php');
require_once('oauth/twitteroauth.php');

 
/* Create TwitterOAuth object and get request token */
$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
 
/* Get request token */
$request_token = $connection->getRequestToken(TWITTER_OAUTH_CALLBACK);
 
/* Save request token to session */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
/* If last connection fails don't display authorization link */
switch ($connection->http_code) {
  case 200:
    /* Build authorize URL */
    $url = $connection->getAuthorizeURL($token);
    header('Location: ' . $url); 
    break;
  default:
    echo 'Could not connect to Twitter. Refresh the page or try again later.';
    break;
}


?>