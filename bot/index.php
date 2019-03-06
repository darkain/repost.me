<pre>
<?php

  function repostme($url, $style=false) {
    $url  = 'http://make.repost.me/?url=' . rawurlencode($url);
    if ($style) $url .= '&style=' . rawurlencode($style);
    $file = false;
    $file = @fopen($url, 'rb');
    if (!$file) return false;
    $data = '';
    while (!feof($file)) $data .= fread($file, 1024);
    fclose($file);
    if (substr($data, 0, 17) !== 'http://repost.me/') return false;
    return $data;
  }


  require_once('twitter.php');

  $twit = new summize;
  $twit->username = 'CosplayLink';
  $twit->password = 'password';
//  $twit->user_agent = 'A Worthless Useragent';

  $result = $twit->search('filter:links cosplay -myloc -bit.ly -repost.me');

  if (is_array($result->results)) {
    $array = $result->results;
    $count = count($array);
    $item  = $array[ rand(0, $count) ];
    $text  = $item->text;
    $user  = $item->from_user;

    $twit->followUser($item->from_user_id);

    $begin = stripos($text, 'http://');
    if ($begin !== false) {
      $end = stripos($text.' ', ' ', $begin);
      if ($end !== false) {
        $url = trim(substr($text, $begin, $end-$begin));
        $repost_data = repostme($url, 1);

        if ($repost_data !== false) {
          $repost_part = split("\n", $repost_data);

          $words = array('Cosplay', 'Photo', 'Costume', 'Naruto', 'Bleach',
                         'Japan', 'Anime', 'Manga', 'Game');

          foreach ($words as $word) { $repost_part[1] = str_ireplace($word, '#'.$word, $repost_part[1]); }

          $post = 'RP @' . $user . ' ' . $repost_part[0] . ' ' . $repost_part[1];
          $post = substr($post, 0, 140);
          $twit->update($post);
        }
      }
    }
  }







/*
object(stdClass)#2 (10) {
  ["results"]=>
  array(15) {
    [0]=>
    object(stdClass)#3 (10) {
      ["profile_image_url"]=>
      string(67) "http://s.twimg.com/a/1266605807/images/default_profile_1_normal.png"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:51:22 +0000"
      ["from_user"]=>
      string(9) "moviefuse"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(71) "#film Blogs: POTD: Zombie Disney Characters Cosplay http://ow.ly/16DE44"
      ["id"]=>
      float(9502457447)
      ["from_user_id"]=>
      int(93365469)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "nl"
      ["source"]=>
      string(95) "<a href="http://www.hootsuite.com" rel="nofollow">HootSuite</a>"
    }
    [1]=>
    object(stdClass)#4 (10) {
      ["profile_image_url"]=>
      string(64) "http://a3.twimg.com/profile_images/667566765/mOTIOMii_normal.png"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:42:26 +0000"
      ["from_user"]=>
      string(12) "motiomtrends"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(67) "POTD: Zombie Disney Characters Cosplay - http://tinyurl.com/yjc7nqt"
      ["id"]=>
      float(9502086424)
      ["from_user_id"]=>
      int(93933636)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "fr"
      ["source"]=>
      string(92) "<a href="http://apiwiki.twitter.com/" rel="nofollow">API</a>"
    }
    [2]=>
    object(stdClass)#5 (10) {
      ["profile_image_url"]=>
      string(60) "http://a1.twimg.com/profile_images/221374776/wink_normal.jpg"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:42:13 +0000"
      ["from_user"]=>
      string(10) "Wink_LeMan"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(96) "Yowza! Kirsten Dunst in Sailor Moon Cosplay (VIDEO): http://digg.com/d31Jb2X?t (via @kplo) #digg"
      ["id"]=>
      float(9502077065)
      ["from_user_id"]=>
      int(18043758)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "en"
      ["source"]=>
      string(81) "<a href="http://digg.com" rel="nofollow">Digg</a>"
    }
    [3]=>
    object(stdClass)#6 (10) {
      ["profile_image_url"]=>
      string(67) "http://a1.twimg.com/profile_images/58375470/cbz_9bc79f9d_normal.jpg"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:41:00 +0000"
      ["from_user"]=>
      string(11) "iheartchaos"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(117) "Kirsten Dunst in cosplay in Akihabara sets back US-Japan relations a generation [JapanWTF] http://tinyurl.com/ygndvr6"
      ["id"]=>
      float(9502025673)
      ["from_user_id"]=>
      int(581005)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "en"
      ["source"]=>
      string(85) "<a href="http://drupal.org" rel="nofollow">Drupal</a>"
    }
    [4]=>
    object(stdClass)#7 (10) {
      ["profile_image_url"]=>
      string(67) "http://s.twimg.com/a/1265999168/images/default_profile_3_normal.png"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:34:32 +0000"
      ["from_user"]=>
      string(8) "moovimix"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(62) "POTD: Zombie Disney Characters Cosplay http://moovimix.com/roP"
      ["id"]=>
      float(9501755906)
      ["from_user_id"]=>
      int(82725645)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "fr"
      ["source"]=>
      string(107) "<a href="http://www.bravenewcode.com/wordtwit/" rel="nofollow">WordTwit</a>"
    }
    [5]=>
    object(stdClass)#8 (10) {
      ["profile_image_url"]=>
      string(71) "http://a3.twimg.com/profile_images/300282989/HPIM1957_resize_normal.JPG"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:34:19 +0000"
      ["from_user"]=>
      string(13) "pinguinopanda"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(74) "Photo: ZOMBIE DISNEY COSPLAY FTW thedailywhat: http://tumblr.com/xvs6pmqm2"
      ["id"]=>
      float(9501747508)
      ["from_user_id"]=>
      int(12922927)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "en"
      ["source"]=>
      string(90) "<a href="http://www.tumblr.com/" rel="nofollow">Tumblr</a>"
    }
    [6]=>
    object(stdClass)#9 (10) {
      ["profile_image_url"]=>
      string(68) "http://a1.twimg.com/profile_images/611793162/sunrisesmirk_normal.jpg"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:33:02 +0000"
      ["from_user"]=>
      string(9) "scottking"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(87) "Disney Zombie Cosplay... really? http://tinyurl.com/yalh68m People need better hobbies."
      ["id"]=>
      float(9501693677)
      ["from_user_id"]=>
      int(1310347)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "en"
      ["source"]=>
      string(59) "<a href="http://twitter.com/">web</a>"
    }
    [7]=>
    object(stdClass)#10 (10) {
      ["profile_image_url"]=>
      string(85) "http://a3.twimg.com/profile_images/574765171/just_another_id_by_nin_quidam_normal.jpg"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:32:22 +0000"
      ["from_user"]=>
      string(9) "ninquidam"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(115) "Cosplay - House MD...WTF???????? http://uploaddeimagens.com.br/imagem/ver/Copy-of-House_MD_Cosplay_by_Elleth666.jpg"
      ["id"]=>
      float(9501665612)
      ["from_user_id"]=>
      int(9648680)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "en"
      ["source"]=>
      string(88) "<a href="http://echofon.com/" rel="nofollow">Echofon</a>"
    }
    [8]=>
    object(stdClass)#11 (10) {
      ["profile_image_url"]=>
      string(83) "http://a1.twimg.com/profile_images/495576906/twitter_profile_camera_gray_normal.png"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:28:19 +0000"
      ["from_user"]=>
      string(13) "moviejunkie12"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(69) "POTD: Zombie Disney Characters Cosplay http://twlv.net/4m22Tx #movies"
      ["id"]=>
      float(9501493220)
      ["from_user_id"]=>
      int(75223074)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "en"
      ["source"]=>
      string(97) "<a href="http://www.twitterlive.net" rel="nofollow">Tweetlive</a>"
    }
    [9]=>
    object(stdClass)#12 (10) {
      ["profile_image_url"]=>
      string(70) "http://a3.twimg.com/profile_images/671652513/IMG208.JPGgffg_normal.jpg"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:22:11 +0000"
      ["from_user"]=>
      string(13) "hannaheroinee"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(140) "Q:vc esta fazendo cosplay meu? OMGpoakpokspoksaops... A:sim, estou, mimimi HSAOHSAOHASOASHSOA http://formspring.me/hannaheroinee/q/213115399"
      ["id"]=>
      float(9501237466)
      ["from_user_id"]=>
      int(31533592)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "pt"
      ["source"]=>
      string(95) "<a href="http://formspring.me" rel="nofollow">formspring.me</a>"
    }
    [10]=>
    object(stdClass)#13 (11) {
      ["profile_image_url"]=>
      string(71) "http://a1.twimg.com/profile_images/330930078/17875236_edited_normal.jpg"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:18:28 +0000"
      ["from_user"]=>
      string(6) "inezco"
      ["to_user_id"]=>
      int(21176059)
      ["text"]=>
      string(114) "@jennobi http://www.slashfilm.com/2010/02/22/potd-zombie-disney-characters-cosplay/ - I think you'll like this lol"
      ["id"]=>
      float(9501079835)
      ["from_user_id"]=>
      int(10654254)
      ["to_user"]=>
      string(7) "jennobi"
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "en"
      ["source"]=>
      string(59) "<a href="http://twitter.com/">web</a>"
    }
    [11]=>
    object(stdClass)#14 (10) {
      ["profile_image_url"]=>
      string(86) "http://a1.twimg.com/profile_images/325717666/picanom-picture-03-2009-07-222_normal.jpg"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:14:43 +0000"
      ["from_user"]=>
      string(10) "andrewm138"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(44) "Disney/Zombie cosplay FTW http://is.gd/8XR54"
      ["id"]=>
      float(9500922085)
      ["from_user_id"]=>
      int(2062582)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "es"
      ["source"]=>
      string(95) "<a href="http://www.tweetdeck.com" rel="nofollow">TweetDeck</a>"
    }
    [12]=>
    object(stdClass)#15 (10) {
      ["profile_image_url"]=>
      string(65) "http://a3.twimg.com/profile_images/487102863/600images_normal.jpg"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:09:53 +0000"
      ["from_user"]=>
      string(8) "capnasty"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(38) "Cosplay geeky? http://con.ca/news/3264"
      ["id"]=>
      float(9500720804)
      ["from_user_id"]=>
      int(71766709)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "es"
      ["source"]=>
      string(95) "<a href="http://twitterfeed.com" rel="nofollow">twitterfeed</a>"
    }
    [13]=>
    object(stdClass)#16 (10) {
      ["profile_image_url"]=>
      string(71) "http://a3.twimg.com/profile_images/712436077/rock_anime_girl_normal.jpg"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:07:37 +0000"
      ["from_user"]=>
      string(8) "sukisama"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(100) "http://twitpic.com/14t2av - cosplay de Lee y su ardilla en su romántica presentación en el bosque."
      ["id"]=>
      float(9500627434)
      ["from_user_id"]=>
      int(92754895)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "es"
      ["source"]=>
      string(88) "<a href="http://twitpic.com/" rel="nofollow">TwitPic</a>"
    }
    [14]=>
    object(stdClass)#17 (10) {
      ["profile_image_url"]=>
      string(59) "http://a1.twimg.com/profile_images/459468038/ava_normal.png"
      ["created_at"]=>
      string(31) "Tue, 23 Feb 2010 00:05:25 +0000"
      ["from_user"]=>
      string(5) "bryoz"
      ["to_user_id"]=>
      NULL
      ["text"]=>
      string(104) "Updated my blog with my #cosplay progress: http://bryoz.wordpress.com/2010/02/23/more-progress/ #oneaday"
      ["id"]=>
      float(9500536410)
      ["from_user_id"]=>
      int(1217192)
      ["geo"]=>
      NULL
      ["iso_language_code"]=>
      string(2) "en"
      ["source"]=>
      string(59) "<a href="http://twitter.com/">web</a>"
    }
  }
  ["max_id"]=>
  float(9502457447)
  ["since_id"]=>
  float(9160849297)
  ["refresh_url"]=>
  string(71) "?since_id=9502457447&q=filter%3Alinks+cosplay+-myloc+-bit.ly+-repost.me"
  ["next_page"]=>
  string(76) "?page=2&max_id=9502457447&q=filter%3Alinks+cosplay+-myloc+-bit.ly+-repost.me"
  ["results_per_page"]=>
  int(15)
  ["page"]=>
  int(1)
  ["completed_in"]=>
  float(0.039261)
  ["warning"]=>
  string(136) "adjusted since_id to 9160849297 (2010-02-16 00:00:00 UTC), requested since_id was older than allowed -- since_id removed for pagination."
  ["query"]=>
  string(48) "filter%3Alinks+cosplay+-myloc+-bit.ly+-repost.me"
}
*/
?>
</pre>
