<?php
  if (!isset($include)) $include = '';
//  require_once($include.'public_html/getvar.php');
//  require_once($include.'getvar.php');

  $title = getvar('t', GET_HTMLSAFE);
  $url   = 'http://repost.me/' . getvar('r', GET_URLSAFE) . '?go=1&site=';
  $url2  = 'http://repost.me/' . getvar('r', GET_URLSAFE);

  echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title>Repost.Me! - <?php echo $title; ?></title>

<style type="text/css">
body, html, * {
  padding: 0;
  margin: 0;
  border: 0;
  overflow: hidden;
  background: #C6D8D1;
}

img {
  vertical-align: text-bottom;
  margin-left: 5px;
}

#commentbar, #repostbar, #infobar {
  position: absolute;
  z-index: 2;
  top: 0;
  right: 0;
  margin: 0 30px;
  padding: 1px 5px 3px 5px;
  display: block;
  background: #C6D8D1 url('http://repostme.info/btn3d.png');
  color: #f00;
  border: 0 solid #f00;
  border-width: 0 1px 1px 1px;
  float: right;
  border-radius: 0 0 5px 5px;
  white-space: nowrap;
}

#repostbar, #infobar {
  left: 0;
  right: auto;  
  float: left;
  z-index: 4;
  padding: 2px;
  opacity: 0.0;
}

#commentbar span, #commentbar img {
  font-size: 14px;
  font-family: sans-serif;
  font-weight: bold;
  background: none;
  cursor: pointer;
}

#commentbar span:hover {
  color: #00f;
  text-decoration: underline;
}

/*
#repostbar div div {
  background: #DCE6E2;
  padding: 2px 0 1px 1px;
  font-family:sans-serif;
  white-space:nowrap;
  border: 1px solid black;
  border-width: 2px 2px 0 2px;
  border-radius: 5px 5px 0 0;
  width: 633px;
  margin: auto;
  text-align: center;
  z-index: 3;
  position: relative;
  font-size: 1px;
}

#repostbar div span {
  text-align: center;
  display: block;
  background: #DCE6E2  ;
  width: 400px;
  margin: 0 auto -2px auto;
  border: 2px solid black;
  border-color: #000 #000 #DCE6E2 #000;
  border-radius: 5px 5px 0 0;
  z-index: 4;
  position: relative;
  font-size: 1.2em;
}
*/

#frame {
  width:100%;
  display: block;
  height:10000px;
  position: absolute;
  z-index: 1;
  top: 0;
  left: 0;
  overflow: auto;
  padding-bottom: 100px;
}
</style>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

</head>
<body>

<div id="infobar">
  TESTING !!!
</div>

<div id="repostbar">
Share this page on any of the following sites:<br />
  <script type="text/javascript" src="http://api.repost.me/testing.js"></script>
  <script type="text/javascript"><!--
    $('#infobar').hide();
    $('#repostbar').hide();
    repostme_text = '';
    repostme_size = 'large';
    repostme_position = 'top';
    repostme_norepost = true;
    repostme_bar("<?php echo str_replace('"', '', str_replace("'", '', $url2)); ?>", "<?php echo str_replace('"', '', str_replace("'", '', $title)); ?>");
  //--></script>
</div>


<div id="commentbar">
  <span>0 Comments</span>
  <img src="http://repostme.info/16/repostme.png" alt="Repost.me" title="Repost.me" />
</div>


<iframe id="frame" frameborder="0" src="<?php echo htmlspecialchars($inline_frame); ?>"></iframe>

<a href="<?php echo htmlspecialchars($data['link_url']); ?>" style="display:none"><?php
  echo htmlspecialchars($data['link_url'] . ' - ' . $data['link_title']);
?></a>





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

<script type="text/javascript"><!--
  if (window != top) top.location.href = location.href;

  function getWindowSize() {
    var size = [0, 0];
    if( typeof( window.innerWidth ) == 'number' ) {
      //Non-IE
      size[0] = window.innerWidth;
      size[1] = window.innerHeight;
    } else if( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) ) {
      //IE 6+ in 'standards compliant mode'
      size[0] = document.documentElement.clientWidth;
      size[1] = document.documentElement.clientHeight;
    } else if( document.body && ( document.body.clientWidth || document.body.clientHeight ) ) {
      //IE 4 compatible
      size[0] = document.body.clientWidth;
      size[1] = document.body.clientHeight;
    }
    return size;
  }

  function resize_frame() {
    var frame = document.getElementById('frame');
    if (!frame) return;
    var size = getWindowSize();
    frame.style.width  = Math.max(size[0]- 0, 100) + 'px';
//    frame.style.height = Math.max(size[1]-39, 100) + 'px';
    frame.style.height = Math.max(size[1]- 0, 100) + 'px';
  }

  if (window.attachEvent) {
    window.attachEvent('onload', resize_frame);
    window.attachEvent('onresize', resize_frame);
  } else if (document.addEventListener) {
    window.addEventListener('onload', resize_frame, true);
    window.addEventListener('resize', resize_frame, true);
  }


  var timeout = 0;
  resize_frame();



$('#commentbar > img').click(function() {
  $('#repostbar').animate({
    opacity: '1.0',
    height: 'toggle',
  }, 200, 'linear');
  $('#infobar').animate({
    opacity: '0.0',
    height: 'hide',
  }, 200, 'linear');
});

$('#commentbar > span').click(function() {
  $('#infobar').animate({
    opacity: '1.0',
    height: 'toggle',
  }, 200, 'linear');
  $('#repostbar').animate({
    opacity: '0.0',
    height: 'hide',
  }, 200, 'linear');
});
//--></script>

</body>
</html>
