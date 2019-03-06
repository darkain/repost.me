/*******************************************/
/* Repost.Me (c) 2010 Vincent E. Milum Jr. */
/*******************************************/

// http://www.daftlogic.com/projects-online-javascript-obfuscator.htm

var repostme_version    = '0.2.20100218';
var repostme_size       = 16;  
var repostme_background = 'DCE6E2';  
var repostme_color      = '5A5E5C';  
var repostme_border     = '5A5E5C';

if ((typeof repostme_bar) === 'undefined') {
  
  function repostme_get_style(element) {
    if (typeof element.currentStyle  !== 'undefined') return element.currentStyle;
    if (typeof element.computedStyle !== 'undefined') return element.computedStyle;
    if (typeof document.defaultView  !== 'undefined') return document.defaultView.getComputedStyle(element, null);
    if (typeof element.style         !== 'undefined') return element.style;
    return null;    
  }

  function repostme_get_left(element) {
    var total = 0;
    while (element) {
      var style = repostme_get_style(element);
      if (style) {
        if (style.position === 'absolute') break;
        if (style.position === 'fixed'   ) break;
        if (style.position !== 'relative') {
          total += element.offsetLeft;
        }
      }
      element = element.offsetParent;
    }
    return total;
  }

  function repostme_show_button(id) {
    var repostme_element = document.getElementById(id);
    if (repostme_element) {
      repostme_element.style.display = 'block';
      repostme_element.style.left    = repostme_get_left(repostme_element.parentNode.parentNode) + 'px';
    }
  }
  
  function repostme_hide_button(id) {
    var repostme_element = document.getElementById(id);
    if (repostme_element) {
      repostme_element.style.display = 'none';
    }
  }
  
  function repostme_button(url, site, width, height, alt) {
    var id  = 'repostme_' + site + Math.floor(Math.random()*(Math.pow(2,30)));
    var str = '';
    str += '<a target="_blank" style="text-decoration:none;text-align:left" href="';
    str += url;
    if (site.toLowerCase() != 'repostme') {
      str += '&amp;site=' + site;
      str += '" onmouseover="javascript:repostme_show_button('+"'"+id+"'"+')" onmouseout="javascript:repostme_hide_button('+"'"+id+"'"+')">';
    } else {
      str += '">';
    }
    if (site.toLowerCase() != 'repostme') {
      str += '<span id="' + id + '" ';
      str += 'style="border:2px solid #' + repostme_border + ';display:none;overflow-x:hidden;overflow-y:hidden;';
      str += 'width:400px;white-space:normal;position:absolute;z-index:999999999;margin:-1px 0 0 0;';
      str += 'padding:0 5px 0 0;background:#' + repostme_background + ';color:#' + repostme_color + ';';
      str += 'font:normal 16pt/22pt sans-serif ">';
      str += '<span style="float:left;height:34px;width:1px;margin-left:-1px">&nbsp;</span>';
      str += '<img border="0" ';
      str += 'style="position:absolute;width:32px;height:32px;margin:1px;border:0;padding:0;vertical-align:middle" ';
      str += 'src="http://img.repost.me/32/' + site.toLowerCase() + '.png" ';
      if (alt) str += 'alt="' + alt + '" title="' + alt + '" ';
      str += '/>';
      if (alt) str += '<span style="display:block;line-height:1em;padding-left:38px">' + alt + '</span>';
      str += '</span>';
    }
    str += '<img border="0" ';
    str += 'style="width:' + width + 'px;height:' + height + 'px;margin:1px;border:0;padding:0;vertical-align:middle" ';
    str += 'src="http://img.repost.me/' + height + '/' + site.toLowerCase() + '.png" ';
    if (alt) str += 'alt="' + alt + '" title="' + alt + '" onmouseover="javascript:this.alt='+"''"+';this.title='+"''"+'" ';
    str += '/></a> ';
    return str;
  }


  function repostme_bar_make(url, title) {
    if (!url) url = document.location.toString();
    var newurl = url;
    
    if (newurl.indexOf('http://repost.me/') < 0) {
      newurl = 'http://repost.me/?url=' + escape(newurl);
    } else {
      newurl += '?go=1';
    }
    
    if (!title) title = document.title;
    title = title.replace(/^\s+/,   '').replace(/\s+$/,    '');  //Trim the string
    title = title.replace('&', '&amp;').replace('"', '&quot;');  //Remove & and "
    title = title.replace('>',  '&gt;').replace('<',   '&lt;');  //Remove < and >
    if (title == '') title = 'this';
    
    if (typeof(repostme_buttons) == 'undefined')  {
      repostme_buttons = [
        'Twitter', 'Facebook', 'MySpace', '', 'Bebo', 'Delicious',
        'Digg', 'FeedBurner', 'FriendFeed', 'Google', 'LinkedIn',
        'LiveJournal', 'Reddit', 'StumbleUpon', 'Technorati', 'Yahoo'
      ];
    }
    
    if (repostme_size == 32  ||  repostme_size === 'large') {
      repostme_size = 32;
    } else {
      repostme_size = 16;
    }
    
//    var str = '<div style="clear:both;font-size:1px">&nbsp;</div>';
    var str = '';
    str += '<span class="snap_noshots" style="font-size:1px;padding:0;margin:0;border:0;white-space:nowrap;overflow:hidden">';
//    if (url.indexOf('http://repost.me') < 0) {
      str += repostme_button(newurl, 'repostme', (repostme_size==16?56:101), repostme_size, 'Create a short URL for '+title+' on Repost.Me');
      str += '<span style="font-size:1px;padding:4px">&nbsp;</span>';
//    }
    
    for (var i=0; i<repostme_buttons.length; i++) {
      if (repostme_buttons[i] == '') {
        str += '<span style="font-size:1px;padding:4px">&nbsp;</span>';
      } else {
        str += repostme_button(newurl, repostme_buttons[i], repostme_size, repostme_size, 'Share '+title+' on '+repostme_buttons[i]);
      }
    }

    str += '</span>';
//    str += '<div style="clear:both;font-size:1px">&nbsp;</div>';
    return str;
  }
  
  function repostme_bar_replace(url, title, element) {
    if (typeof(element) == 'string') element = document.getElementById(element);
    element.innerHTML = repostme_bar_make(url, title);
  }

  function repostme_bar(url, title) {
    document.write(repostme_bar_make(url, title));
  }


}  
