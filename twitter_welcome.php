<?php
  require_once('config.php');
  require_once('getvar.php');

  if ($user === false) redirect_url($website);
  if (!$user['twitter_user']) redirect_url($website);


  if (strlen(getvar('go')) > 0) {
    $follow = false;
    $tweet  = false;

    if (strlen(getvar('follow')) > 0) $follow = true;
    if (strlen(getvar('tweet' )) > 0) $tweet  = true;

    if (($follow || $tweet)  &&  $user['oauth_token']  &&  $user['oauth_secret']) {
      require_once('oauth/twitteroauth.php');

      $connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET,
                                     $user['oauth_token'], $user['oauth_secret']);

      if ($follow) {
        $connection->post('friendships/create/RepostMe');
      }

      if ($tweet) {
        $parameters = array('status' => 'I just signed up at http://Repost.Me/ which allows me to shorten URLs and bookmark my favorite web pages!');
        $connection->post('statuses/update', $parameters);
      }
    }
    redirect_url($website);
  }


  $url_short  = 'http://repost.me/';
  $page_title = '- Twitter';
  require_once('header.php');

?>


<form action="twitter_welcome.php" method="get">
<div class="content">


<span style="font-size:2em">
Welcome to <span style="color:red">Repost.Me</span>!<br />
</span>

<span style="font-size:1.5em">
You are currently logged in as: 
<a href="http://twitter.com/<?php echo htmlspecialchars($user['twitter_user']); ?>" target="_blank">
<img src="http://repostme.info/16/twitter.png" alt="Twitter" style="width:16px;height:16px" />
<?php echo htmlspecialchars($user['twitter_user']); ?></a>
</span>

<br />
<br />
<br />

<label><input type="checkbox" checked="checked" name="follow" value="1" /> Follow @RepostMe on Twitter</label><br />
<label><input type="checkbox" checked="checked" name="tweet" value="1" /> Send a Tweet letting my friends know that I've signed up for <span style="color:red">Repost.Me</span></label><br />

<div style="text-align:center; padding:2em 0 2em 0">
<span class="btn3d whitered"><input type="submit" name="go" value="Continue" style="font-size: 2em;padding: 0.2em 1em" /></span>
</div>


</div>
</form>


<?php
  require_once('footer.php');
?>