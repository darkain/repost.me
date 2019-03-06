<?php

  require_once('config.php');

  $time   = time();
  $result = mysql_query("SELECT `ip_address` from `ip_address` WHERE `ip_dns_time`='0' LIMIT 10");
  while ($data = mysql_fetch_assoc($result)) {
    $dns_name = '';
    $dns_name = @gethostbyaddr( int_to_ip($data['ip_address']) );
    $dns_name = mysql_real_escape_string($dns_name);
    mysql_query("UPDATE `ip_address` SET `ip_dns_time`='$time', `ip_dns_name`='$dns_name' WHERE `ip_address`='$data[ip_address]' LIMIT 1");
  }
  mysql_free_result($result);


  url_get_contents('http://tweetalot.coolpage.biz/');

?>