<?php

//  require_once('string.php');


  define('GET_BASIC',     0 <<  0);
  define('GET_NOGET',     1 <<  0);
  define('GET_NOPOST',    1 <<  1);
  define('GET_SQLSAFE',   1 <<  2);
  define('GET_HTMLSAFE',  1 <<  3);
  define('GET_URLSAFE',   1 <<  4);
  define('GET_MD5BIN',    1 << 29);
  define('GET_MD5',       1 << 30);
  define('GET_NORMAL',    GET_SQLSAFE);
  define('GET_HTML_SQL',  GET_SQLSAFE | GET_HTMLSAFE);
  
  
  function _getvar_clean($val, $flags) {
    if (is_array($val)) {
      foreach ($val as $key => $item) {
        $val[$key] = _getvar_clean($item, $flags);
      }
      return $val;
    }
  
    //trim, and strip slashes if magic quotes are enabled
    if (get_magic_quotes_gpc()) {
      $val = stripslashes(trim($val));
    } else {
      $val = trim($val);
    }

    //if no value, return
    if (is_null($val)) return $val;

    //fix UTF-8 AJAX bug
//    $val = unescape($val);


    //convert to MD5 checksum (binary)
    if (($flags & GET_MD5BIN) > 0) {
      $val = md5($val, true);
    }
    
    //convert to MD5 checksum
    if (($flags & GET_MD5) > 0) {
      $val = md5($val);
    }
    
    //clean out HTML special characters
    if (($flags & GET_HTMLSAFE) > 0) {
      $val = htmlspecialchars($val, ENT_QUOTES);
    }

    //clean out URL paramater special characters
    if (($flags & GET_URLSAFE) > 0) {
      $val = rawurlencode($val);
    }

    //prevent SQL injection
    if (($flags & GET_SQLSAFE) > 0) {
      $val = mysql_real_escape_string($val);
    }


    return $val;
  }


  function getvar($name, $flags=GET_NORMAL) {
    $val = null;

    //attempt to get the value from POST
    if (($flags & GET_NOPOST) == 0) {
      if (isset($_POST[$name])) $val = $_POST[$name];
    }

    //attempt to get the value from GET
    if (is_null($val)  &&  (($flags & GET_NOGET) == 0)) {
      if (isset($_GET[$name])) $val = $_GET[$name];
    }

    //clean and return value
    return _getvar_clean($val, $flags);
  }
  
  
  function getint($name, $flags=GET_NORMAL) { return  (int) getvar($name, $flags); }
  function getflt($name, $flags=GET_NORMAL) { return (float)getvar($name, $flags); }
  function getid() { return getint('id', GET_BASIC); }
  
  
  function getarr($name, $sep=';', $unique=true, $flags=GET_NORMAL) {
    $passflag = GET_BASIC;
    if ($flags & GET_NOPOST) $passflag |= GET_NOPOST;
    if ($flags & GET_NOGET)  $passflag |= GET_NOGET;
    
    $val = getvar($name, $passflag);
    if ($val === null) return array();
    $arr = explode($sep, $val);
    if ($unique) $arr = array_unique($arr);
    
    for ($i=0; $i<count($arr); $i++) {
      $arr[$i] = trim($arr[$i]);
      
      //remove empty enteries
      if ($arr[$i] == '') {
        unset($arr[$i]);
      } else {
        $arr[$i] = _getvar_clean($arr[$i], $flags);
      }
    }
    
    return $arr;
  }


  function getvar_ex($name, $theid=false, $flags=GET_NORMAL) {
    global $id;
    if ($theid === false) $theid = $id;
    $name .= $theid;
    return getvar($name, $flags);
  }


  function getarr_ex($name, $sep=';', $unique=true, $theid=false, $flags=GET_NORMAL) {
    global $id;
    if ($theid === false) $theid = $id;
    $name .= $theid;
    return getarr($name, $sep, $unique, $flags);
  }
  
  

  if (!isset($id)) $id = getid();
  
?>