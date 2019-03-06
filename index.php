<?php
  $nobar  = false;
  $noecho = true;
  $button = 'urls';

  require_once('config.php');
  require_once('getvar.php');

  $query = getvar('q');
  if ($query !== '') {
    require_once('search.php');
    return;
  }


  if ($user !== false) {
    $comment = getvar('comment_id');
    if ($comment !== '') {
      require_once('process_comment.php');
      return;
    }
  }



  $url_short = '';
  $url_long  = 'Enter URL To Shorten';

  $url = getvar('url');
  if ($url !== '') {
    $url_short = require_once('make.php');
    $url_long  = $url;
  }

  if (isset($title)) {
    $page_title = ' - ' . $title;
  } else {
    $page_title = '- Shorten URLs and Make QR Codes';
  }

  require_once('header.php');
?>


<div class="content">


<div id="tabs">
  <span class="btn3d whitered large selected" onclick="javascript: display_section('pop')" id="button_pop"><span>Popular Pages</span></span>
  <span class="btn3d whitered large" onclick="javascript: display_section('new')" id="button_new"><span>Newest Pages</span></span>
  <span class="btn3d whitered large" onclick="javascript: display_section('mine')" id="button_mine"><span>Pages I Own</span></span>
  <span class="btn3d whitered large" onclick="javascript: display_section('favs')" id="button_favs"><span>My Favorite Pages</span></span>
</div>

<div id="repostme_content">
<?php require('display.php'); ?>
</div>

</div>


<?php
  require_once('footer.php');
?>