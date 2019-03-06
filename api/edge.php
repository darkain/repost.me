<?php echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <title>Repost.Me - Stable</title>
    <style type="text/css">
      body, html, * {
        border:0;
        padding:0;
        margin:0;
      }
    </style>
  </head>
  <body>
    <script type="text/javascript" src="http://api.repost.me/edge.js"></script>
    <script type="text/javascript"><!--
      repostme_bar(<?php echo "'" . str_replace("'", "", str_replace('"', '', $_GET['url'])) . "','" . str_replace("'", "", str_replace('"', '', $_GET['title'])) . "'" ?>);
    //--></script>
</body>
</html>
