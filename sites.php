<?php

//  function redirect_site($site, $url, $title) {
  function redirect_site($data) {
//    $url      = urlencode($url);
//    $title    = urlencode($title);
    $url_base = 'http://repost.me/' . int_to_short_string($data['link_id']);
    $url      = urlencode($url_base);
    $title    = urlencode($data['link_title']);
    $redirect = '';

//    switch (strtolower($site)) {
    switch (strtolower($data['site'])) {
      case 'bebo':
        $redirect = 'http://www.bebo.com/c/share?Url=' . $url . '&Title=' . $title;
        break;

      case 'delicious':
      case 'del.icio.us':
        $redirect = 'http://del.icio.us/post?url=' . $url;
        break;

      case 'digg':
        $redirect = 'http://www.digg.com/submit?phase=2&url=' . $url_base;
        break;

      case 'email':
      case 'feedburner':
        $redirect = 'http://www.feedburner.com/fb/a/emailFlare?itemTitle=' . $title . '&uri=' . $url;
        break;

      case 'fb':
      case 'facebook':
        $redirect = 'http://www.facebook.com/sharer.php?u=' . $url . '&t=' . $title;
        break;

      case 'friendfeed':
        $redirect = 'http://friendfeed.com/share?url=' . $url . '&title=' . $title;
        break;

      case 'google':
        $redirect = 'http://www.google.com/reader/link?url=' . $url . '&title=' . $title . '&srcURL=http%3A%2F%2Frepost.me%2F&srcTitle=Repost%20Me%21%20You%20know%20you%20want%20to...';
        break;

      case 'linkedin':
        $redirect = 'http://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title . '&summary=&source=';
        break;

      case 'lj':
      case 'livejournal':
        $redirect = 'http://www.livejournal.com/update.bml?subject=' . $title . '&event=' . $url . '%20-%20' . $title;
        break;

      case 'ms':
      case 'myspace':
        $redirect = 'http://www.myspace.com/Modules/PostTo/Pages/?c=' . $url . '&t=' . $title;
        break;

      case 'reddit':
        $redirect = 'http://reddit.com/submit?url=' . $url;
        break;

      case 'stumbleupon':
        $redirect = 'http://www.stumbleupon.com/submit/?url=' . $url;
        break;

      case 'technorati':
        $redirect = 'http://www.technorati.com/faves?add=' . $url;
        break;

      case 'tumblr':
        $redirect = 'http://www.tumblr.com/share?v=3&u=' . $url . '&t=' . $title;
        break;

      case 'yahoo':
        $redirect = 'http://bookmarks.yahoo.com/toolbar/savebm?opener=tb&u=' . $url . '&t=' . $title;
        break;

      case 'twitter':
        //TODO: make the twitter username DYNAMIC, so that it supports PREMIUM accounts! <3
        //TODO: passing along non-properly-encoded UTF-8 strings in $title results in Twitter throwing us an error!
        $twitter = 'RepostMe';
        if (stripos($data['link_url'], 'darkain.com')    !== false) $twitter = 'DarkainCOM';
        if (stripos($data['link_url'], 'acparadise.com') !== false) $twitter = 'ACParadise';
        //$redirect = 'http://twitter.com/home?status=' . 'RP%20@' . $twitter . '%20' . $url . '%20-%20' . $title;
        $redirect = 'http://twitter.com/intent/tweet?text=' . $title . '&url=' . $url;
        break;
    }


    if ($redirect !== '') {
      redirect_url($redirect, 301);
      exit;
    }
  }

?>