<?php
  if (!headers_sent()) {
    header('Vary: Accept');
    header('Content-Type: text/html; charset=utf-8');
  }

  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <title>Repost.Me <?php if (isset($page_title)) echo $page_title; ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=8" />
    <meta http-equiv="Content-Language" content="en-us" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8 (Unicode, Worldwide)" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="author" content="Darkain Multimedia - http://www.darkain.com/" />
    <meta name="classification" content="Bookmark" />
    <meta name="copyright" content="Copyright &copy; 2008-2010 Darkain Multimedia, All Rights Reserved" />
    <meta name="doc-class" content="Completed" />
    <meta name="generator" content="php5" />
    <meta name="rating" content="general" />
    <meta name="abstract" content="Repost.Me! Quickly shorten a long URL into a tiny web site address and easily generate QR Codes that can be scanned into your cell phone." />
    <meta name="description" content="Repost.Me! Quickly shorten a long URL into a tiny web site address and easily generate QR Codes that can be scanned into your cell phone and easily share them with your friends on your favorite social networking web sites such as Facebook, MySpace, and Twitter." />
    <meta name="keywords" content="repost, post, share, copy, url, uri, short, shorten, small, tiny, long, web site, address, web address, qr, qrcode, qr code, qr-code, barcode, cell, cell phone, link, bookmark, darkain, twitter, facebook, myspace, digg, livejournal, wordpress, bebo, delicious, feedburner, friendfeed, google, google buzz, buzz, linkedin, reddit, stumbleopon, technotari, yahoo, rss, social network, social networking, social, network, networking, link bar, icon bar, icon" />
<?php if ($testing) { ?>
    <link rel="search" type="application/opensearchdescription+xml" href="opensearch.xml" title="Repost.Me Search" />
    <link rel="stylesheet" href="includes/default/default.css" type="text/css" />
    <link rel="shortcut icon" href="favicon.ico" />
    <script type="text/javascript" src="function.js"></script>
    <script type="text/javascript" src="api/testing_clean.js"></script>
<?php } else { ?>
    <link rel="search" type="application/opensearchdescription+xml" href="http://repostme.net/opensearch.xml" title="Repost.Me Search" />
    <link rel="stylesheet" href="http://repostme.net/includes/default/default.css" type="text/css" />
    <link rel="shortcut icon" href="http://repostme.net/favicon.ico" />
    <script type="text/javascript" src="http://repostme.net/function.js"></script>
    <script type="text/javascript" src="http://api.repost.me/testing.js"></script>
<?php } ?>
  </head>


  <body>

  <div id="pagebody">
    <div id="divider">&nbsp;</div>

    <a href="<?php echo $website; ?>" id="logo_link"><span>Repost.Me - Shorten URLs, Generate QR Bar Codes, Bookmark Web Sites</span></a>
    <div id="logo"><span class="text">Repost.Me! Quickly shorten a long URL into a tiny web site address and easily generate QR Codes that can be scanned into your cell phone and easily share them with your friends on your favorite social networking web sites such as <a href="http://www.facebook.com/">Facebook</a>, <a href="http://www.myspace.com/">MySpace</a>, and <a href="http://www.twitter.com/">Twitter</a>.  Help improve and optimize your <a href="http://www.yahoo.com/">Yahoo!</a> and <a href="http://www.google.com/">Google</a> search engine page rank using the Repost.Me icon link social networking button bar.</span></div>

    <div id="topmenu">
      <?php if ($user === false) { ?>
        <a href="twitter_login.php"><img src="http://repostme.info/sign-in-with-twitter.png" alt="Sign in with Twitter" title="Login to Repost.Me using your existing Twitter account" /></a>
      <?php } else { ?>
        <b>Logged in as:</b>
        <a href="http://twitter.com/<?php echo htmlspecialchars($user['twitter_user']); ?>" target="_blank">
        <img src="http://repostme.info/16/twitter.png" alt="Twitter" style="width:16px;height:16px" />
        <?php echo htmlspecialchars($user['twitter_user']); ?></a><br />
        <a href="logout.php" class="logout btn3d whitered small"><span>Logout of Repost.Me</span></a>
      <?php } ?>
    </div>

    <div class="clear">&nbsp;</div>


    <div id="mainbody">

      <form action="" method="post" onsubmit="javascript: return repost();"><div id="shorturl">
        Enter a Web Page Address to Shorten<br />
        <input type="text" name="url" id="repost_url" value="http://" />
        <input type="submit" value="Shrink it!" class="button" />
        <div id="repost_output" class="repost_feed"></div>
      </div></form>


<?php /*
    <div id="main-frame">
    <div id="logo"><span class="text">Repost.Me! Quickly shorten a long URL into a web site tiny address and easily generate QR Codes that can be scanned into your cell phone and easily share them with your friends on your favorite social networking web sites such as <a href="http://www.facebook.com/">Facebook</a>, <a href="http://www.myspace.com/">MySpace</a>, and <a href="http://www.twitter.com/">Twitter</a>.  Help improve and optimize your <a href="http://www.yahoo.com/">Yahoo!</a> and <a href="http://www.google.com/">Google</a> search engine page rank using the Repost.Me icon link social networking button bar.</span></div>
      <div id="login-frame" class="txtDarkBold">
        <!--[if gt IE 7]>
          <span class="btn3d whitered small" onclick="window.external.AddService('http://repost.me/accelerator.xml')"><span>Add to IE8</span></span>
        <![endif]-->
        <a href="http://repostme.darkain.com/" class="btn3d whitered small" title="Help improve and optimize your Yahoo! and Google search engine page rank using the Repost.Me icon link social networking button bar"><span>Add Repost.Me! To My Web Site</span></a>
      </div>
      <div id="select-list">
        <div id="select-urls<?php echo ($button=='urls'?'-active':'') ?>">
          <a href="http://Repost.Me/" class="select-list-link" title="Make Short URLs"><span class="text">URLs</span></a>
        </div>
        <div id="select-search<?php echo ($button=='search'?'-active':'') ?>">
          <a href="http://Repost.Me/search.php" class="select-list-link" title="Search Short URLs"><span class="text">Search</span></a>
        </div>
      </div>
      <div id="center-divide"></div>
      <div id="left-content">
        <div id="repostme_qrcode">
          <?php
            if (isset($url_short)  &&  stripos($url_short, 'http://repost.me/') !== false) {
              echo '<img src="http://qr.repost.me/?d=' . $url_short . '&amp;e=l" alt="QR Code" />';
              echo ' <br />QR Code for <br />' . $url_short;
            } else {
              echo '<img src="http://qr.repost.me/?d=http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&amp;e=l" alt="QR Code" />';
              echo ' <br />QR Code for <br />http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            }
          ?>
        </div>
        <script type="text/javascript"><!--
          google_ad_client = "pub-0556075448585716";
          google_ad_slot = "7750514774";
          google_ad_width = 160;
          google_ad_height = 600;
          //-->
        </script>
        <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
      </div>
      <div id="right-content">



    <script type="text/javascript"><!--
      google_ad_client = "pub-0556075448585716";
      google_ad_slot = "2752334695";
      google_ad_width = 468;
      google_ad_height = 15;
    //-->
    </script>
    <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>



        <form action="/" method="get">
          <div id="processUrl">
            <div id="header"><span class="text">Enter your long url</span></div>
            <div id="longurl"><input id="txtLongUrl" name="txtLongUrl" type="text" value="<?php echo htmlspecialchars($url_long); ?>" onkeydown="javascript: return prevent_enter()" onfocus="javascript: if (this.value=='Enter URL To Shorten') this.value='';" onblur="javascript: if (this.value=='') this.value='Enter URL To Shorten';" /></div>
            <div id="pubSelect">
              <div id="output">
                <input id="btnGen" index="-1" type="submit" value="Generate Link" onclick="javascript: return repost();" />
                <div id="final">
                  <input id="txtOutput" name="txtOutput" readonly="readonly" value="<?php echo htmlspecialchars($url_short); ?>" /><br />
                </div>
                <div style="clear:both; font-size:1px">&nbsp;</div>

                <div id="repostme_bar_div" style="float:right; padding:0 12px 0 0">
                  <?php
                    echo "\r\n";
                    echo '<script type="text/javascript"><!--' . "\r\n";
                    if (stripos($url_short, 'http://repost.me/') !== false) {
                      echo "repostme_bar('" . htmlspecialchars($url_long, ENT_QUOTES) . "', '" . htmlspecialchars($title, ENT_QUOTES) . "');\r\n";
                    } else {
                      echo 'repostme_bar();' . "\r\n";
                    }
                    echo '//--></script>' . "\r\n";
                  ?>
                </div>
                <div style="clear:both; font-size:1px">&nbsp;</div>
              </div>
            </div>
          </div>
        </form>
*/ ?>
