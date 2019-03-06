<?php
  date_default_timezone_set('GMT');


//SELECT *, COUNT(FLOOR(view_time/3600)) AS count, FLOOR(view_time/3600) AS hour FROM `view` WHERE link_id=3351 GROUP BY hour

//SELECT * FROM `domain` JOIN `links` USING (`domain_id`) WHERE `domain_name`='www.hagensautoparts.com'


//SELECT *, COUNT(FLOOR(`view_time`/3600)) AS `count`, FLOOR(`view_time`/3600) AS `hour` FROM `view` WHERE `link_id` IN (SELECT `link_id` FROM `domain` JOIN `links` USING (`domain_id`) WHERE `domain_name`='www.hagensautoparts.com') GROUP BY `hour` ORDER BY `hour` DESC

  //TODO: figure out why times are 21 hours off from normal!?!?
  //TODO: text this on live server too
  $time -= 60*60*21;

  $hour  = ((floor($time / 86400)) * 24) - 200;
  $hours = array();
  $first = 999999999;
  $last  = 0;
  $result = mysql_query("SELECT * FROM `stat_per_hour` WHERE `hour` > '$hour' ORDER BY `hour` DESC LIMIT 168");

  for ($i=0; $i<300; $i++) {
    $thishour = (int)$hour + $i;
    $first = min($first, $thishour);
    $last  = max($last,  $thishour);
    $hours[$thishour] = array('hour'=>$thishour, 'hour_post'=>0, 'hour_fetch'=>0, 'hour_view'=>0);
  }


  while ($data = mysql_fetch_assoc($result)) {
    $thishour = $data['hour'];
    $first = min($first, $thishour);
    $last  = max($last,  $thishour);
    $hours[$thishour] = $data;
  }

  mysql_free_result($result);

  $day = ((floor($time / 86400)) * 24) - (24*6);
  for ($x=7; $x>0; $x--) {

    $start = $day + ($x * 24);
    $date  = $start * 3600;

    echo '<div class="content" style="padding-bottom:1em; margin-bottom:1em">';
    echo '<h2 style="margin:0;padding:0">' . date('l, F jS', $date) . '</h2>';
    echo '<table cellspacing="0"><tr>';
      $max = array(1, 1, 1);
      for ($i=0; $i<24; $i++) {
        $thishour = (int)$start + $i;
        $max[0] = max($max[0], $hours[$thishour]['hour_post']);
        $max[1] = max($max[1], $hours[$thishour]['hour_fetch']);
        $max[2] = max($max[2], $hours[$thishour]['hour_view']);
      }
      
      for ($i=0; $i<24; $i++) {
        $thishour = (int)$start + $i;
        $post  = ((100/$max[0])*($hours[$thishour]['hour_post']));
        $fetch = ((100/$max[1])*($hours[$thishour]['hour_fetch']));
        $view  = ((100/$max[2])*($hours[$thishour]['hour_view']));
        echo '<td style="height:100px; vertical-align:bottom">';
        echo '<div style="width:5px;height:' . max(1, $post)  . 'px; background:#f66"></div>';
        echo '</td><td style="vertical-align:bottom">';
        echo '<div style="width:5px;height:' . max(1, $fetch) . 'px; background:#6d6"></div>';
        echo '</td><td style="vertical-align:bottom">';
        echo '<div style="width:5px;height:' . max(1, $view)  . 'px; background:#66f"></div>';
        echo '</td>';
        if ($i != 23) echo '<td style="width:7px"></td>';
      }
    echo '</tr><tr>';
      for ($i=0; $i<24; $i++) {
        echo '<td style="font-size:0.7em; text-align:center;" colspan="3">';
        echo str_pad($i, 2, "0", STR_PAD_LEFT);
        echo '</td>';
        if ($i != 23) echo '<td></td>';
      }
    echo '</tr></table></div>';
  }

?>
