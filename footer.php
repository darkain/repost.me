    </div>

    
    <div id="leftbody">
      <div id="repostme_qrcode">
        <?php
          if (isset($url_short)  &&  stripos($url_short, 'http://repost.me/') !== false) {
            echo '<img src="http://qr.repost.me/?d=' . $url_short . '&amp;e=l" alt="QR Code" />';
            echo ' <br /><a href="http://en.wikipedia.org/wiki/QR_Code" target="_blank">QR Code</a> for <br />' . $url_short;
          } else {
            echo '<img src="http://qr.repost.me/?d=http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '&amp;e=l" alt="QR Code" />';
            echo ' <br /><a href="http://en.wikipedia.org/wiki/QR_Code" target="_blank">QR Code</a> for <br />http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
          }
        ?>
      </div>

      <a href="http://repostme.darkain.com/" class="btn3d whitered small" title="Help improve and optimize your Google and Yahoo! search engine page rank using the Repost.Me icon link social networking button bar"><span>View Repost.Me API</span></a>
      <br /><br />

      <script type="text/javascript"><!--
        google_ad_client = "pub-0556075448585716";
        google_ad_slot = "7750514774";
        google_ad_width = 160;
        google_ad_height = 600;
        //-->
      </script>
      <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
    </div>

    <div class="clear">&nbsp;</div>

    <div style="text-align:center; margin:5px 0 0 150px">
      <a href="http://www.darkain.com/" target="_BLANK">Repost.Me Web Services - Copyright &copy; 2008-2010 Darkain Multimedia, All Rights Reserved</a>
    </div>
  </div>


<?php /*
<script type="text/javascript"><!--
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
//--></script>
<script type="text/javascript"><!--
try {
var pageTracker = _gat._getTracker("UA-6878151-5");
pageTracker._setDomainName(".repost.me");
pageTracker._trackPageview();
} catch(err) {}
//--></script>
<script type="text/javascript" src="http://shots.snap.com/ss/14d9d784cdfcbedc124829bc6c017225/snap_shots.js"></script>
*/ ?>

</body>
</html>




<?php /*

        <div style="text-align:center">
          <a href="http://www.darkain.com/" target="_BLANK">Copyright &copy; 2008-2010 Darkain Multimedia, All Rights Reserved</a>
        </div>

      </div>
< ?php / *

      <div id="lower-body">
        <p>Ever thought a URL was too long?  Now you too can get short URLs for free!  Simply copy the URL you want to where it says "Enter URL" above, and press the "Generate Link" button.  Its that easy!  A new Repost.Me URL will be created from the one you entered.</p>
        <p>Now you can easily have short web addresses for when you sms/text your friends, or call someone.  Shorter URLs also means they are easier to remember.  Go ahead and give it a try, its free!</p>

        <div style="border:3px solid #E9F0ED; margin-bottom:1em; text-align:left">
          <div style="background:#E9F0ED; color:red;font-weight:bold; font-size:1.2em; padding:3px 5px">
            Integrate Repost.Me On Your Web Site
          </div>
          <div style="font-family:monospace; font-size:0.9em; padding:3px; color:black">
            &lt;script type="text/javascript" src="http://api.repost.me/edge.js"&gt;&lt;/script&gt;<br />
            &lt;script type="text/javascript"&gt;&lt;!--<br />
            repostme_bar();<br />
            //--&gt;&lt;/script&gt;<br /><br />
          </div>
          <a href="http://repostme.darkain.com" style="color:red">Click Here for more details!</a>
        </div>
      </div>
* / ? >

    </div>


< ?php / *
<div style="width:100%; position:absolute; top: 195px; left:0; text-align:center">
<div style="width:700px; height:160px; text-align:justify; margin:0 auto;">
<div style="width:180px; height:150px; margin:0 5px">
<script type="text/javascript"><!--
google_ad_client = "pub-0556075448585716";
google_ad_slot = "7591986081";
google_ad_width = 180;
google_ad_height = 150;
//-->
</script>
<script type="text/javascript"src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div></div><div?

  
    <div style="width:100%; position:absolute; top: 360px; left:0; text-align: center"><div style="width:600px; text-align:justify; margin:0 auto">
<p>Ever thought a URL was too long?  Now you too can get short URLs for free!  Simply copy the URL you want to where it says "Enter URL" above, and press the "Generate Link" button.  Its that easy!  A new Repost.Me URL will be created from the one you entered.</p>
<p>Now you can easily have short web addresses for when you sms/text your friends, or call someone.  Shorter URLs also means they are easier to remember.  Go ahead and give it a try, its free!</p>


<button onclick="window.external.AddService('http://repost.me/accelerator.xml')">Add Repost.me! to the shortcut menu in Internet Explorer 8</button> 

<hr />
<div style="overflow:hidden; white-space:pre">
<b>Recently reposted URLs that have been shortened</b><br /><?php
  $result = mysql_query("SELECT * FROM `links` ORDER BY `link_id` DESC LIMIT 10");
  while ($data = mysql_fetch_assoc($result)) {
    if ($data['link_title']) {
      echo ' - <a href="http://repost.me/' . int_to_short_string($data['link_id']) . '">' . $data['link_title'] . '</a><br />';
    } else {
      echo ' - <a href="http://repost.me/' . int_to_short_string($data['link_id']) . '">' . $data['link_url'] . '</a><br />';
    }
  }
  mysql_free_result($result);
?>
</div>
</div></div>
  
  
  
  
  
  
  
  <?php /*
<form action="http://make.repost.me/" method="post" onsubmit="return false">
URL:<br />
<input type="text" name="url" id="url" onkeydown="prevent_enter()" />
<input type="submit" index="-1" value="Create REPOST url" onclick="repost()"/>
</form>

<div style="display:none" id="repost_div">
<b>New Repost URL:</b><br />
<span id="repost_url"></span>
</div>

<hr />

<b>Recent Reposts:</b><br />
<ul>
<?php //>
  $result = mysql_query("SELECT * FROM `links` ORDER BY `link_id` DESC LIMIT 10");
  while ($data = mysql_fetch_assoc($result)) {
    if ( is_string($data['link_title'])  &&  strlen($data['link_title']) > 0 ) {
      echo '<li><a href="http://repost.me/' . int_to_short_string($data['link_id']) . '">' . $data['link_title'] . '</a></li>';
    } else {
      echo '<li><a href="http://repost.me/' . int_to_short_string($data['link_id']) . '">' . $data['link_url'] . '</a></li>';
    }
  }
  mysql_free_result($result);
?>
</ul>

* / ? >


    <script type="text/javascript"><!--
      var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
      document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    //--></script>
    <script type="text/javascript"><!--
      try {
        var pageTracker = _gat._getTracker("UA-6878151-5");
        pageTracker._setDomainName(".repost.me");
        pageTracker._trackPageview();
      } catch(err) {}
    //--></script>

    <script type="text/javascript" src="http://shots.snap.com/ss/14d9d784cdfcbedc124829bc6c017225/snap_shots.js"></script>

  </body>
</html>
*/ ?>
