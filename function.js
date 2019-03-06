/*******************************************/
/* Repost.Me (c) 2010 Vincent E. Milum Jr. */
/*******************************************/

// http://www.daftlogic.com/projects-online-javascript-obfuscator.htm

var ajax = null;
uriescape = (encodeURIComponent) ? (encodeURIComponent) : (escape);


if ((typeof String.prototype.trim) == "undefined") {
  String.prototype.trim = function() {
    return this.replace(/^\s+/,'').replace(/\s+$/,'');
  }
}

if ((typeof Array.prototype.indexOf) == "undefined") {
  Array.prototype.indexOf = function(item, start) {
    for (var i = (start || 0); i < this.length; i++) {
      if (this[i] == item) {
        return i;
      }
    }
    return -1;
  }
}

function repost(ev) {
  if (!ev) ev = window.event;
  preventDefaults(ev);
  var url = 'make.php?url=' + uriescape(getElementValue('repost_url')) + '&site=repost.me';
  return !ajax_request(url, 'repost_output');
}


function update_repostme_bars() {
  var elements = document.getElementsByTagName('span')
  for (var i=0; i<elements.length; i++) {
    var element = elements[i];
    if (hasClass(element, 'repostme_bar_replace')) {
      removeClass(element, 'repostme_bar_replace');
      repostme_bar_replace(element.innerHTML, '', element);
    }
  }  
}


function getElement(thing) {
  if (typeof(thing) == 'object') return thing;
  if (typeof(thing) == 'string') return document.getElementById(thing);
  return null;
}


function getElementValue(thing) {
  var element = getElement(thing);
  return ( (element) ? (element.value) : ('') );
}


function setElementValue(thing, value) {
  var element = getElement(thing);
  if (element) element.value = value;
}


function displayBlock(name) {
  var element = getElement(name);
  if (element) element.style.display = 'block';
}


function displayNone(name) {
  var element = getElement(name);
  if (element) element.style.display = 'none';
}


function display_section(name) {
  var element = getElement('repostme_content');
  
  ajax = null;
  if (window.XMLHttpRequest) {
    ajax = new XMLHttpRequest();
  } else {
    ajax = new ActiveXObject('Microsoft.XMLHTTP');
  }
  if (!ajax) return false;
  
  ajax.onreadystatechange = function() {
    if (ajax.readyState==4) {
      if (ajax.status == 200) {
        element.innerHTML = ajax.responseText;
        update_repostme_bars();
      }
      ajax = null;
    }
  }

  removeClass('button_new', 'selected');
  removeClass('button_pop', 'selected');
  removeClass('button_mine', 'selected');
  removeClass('button_favs', 'selected');
  addClass('button_' + name, 'selected');

  ajax.open('GET', 'display.php?page='+name, true);
  ajax.send(null);
  return true;
}


function preventDefaults(ev) {
  if (!ev) return;
  if (ev.stopPropagation) ev.stopPropagation();
  if (ev.preventDefault)  ev.preventDefault();
  if (!document.all) return;
  ev.cancelBubble = true;
  ev.returnValue  = false;
}


function prevent_enter(ev){
  if (!ev) ev = window.event;
  if (ev.keyCode == 13  ||  ev.charCode == 13) {
    preventDefaults();
    repost(ev);
    return false;
  }
  return true;
}


function getClasses(element) {
  return element.className.trim().split(/\s+/);
}


function hasClass(thing, c) {
  var element = getElement(thing);
  if (!element) return false;
  return getClasses(element).indexOf(c) != -1;
}


function addClass(thing, c) {
  var element = getElement(thing);
  if (!element) return;

  var classes = getClasses(element);
  if (classes.indexOf(c) == -1) {
    classes.push(c);
    element.className = classes.join(' ');
  }
}


function removeClass(thing, c) {
  var element = getElement(thing);
  if (!element) return;

  var classes = getClasses(element);
  var idx = classes.indexOf(c);
  if (idx != -1) {
    classes.splice(idx, 1);
    element.className = classes.join(' ');
  }
}


function ajax_request(url, out) {
  ajax = null;
  if (window.XMLHttpRequest) {
    ajax = new XMLHttpRequest();
  } else {
    ajax = new ActiveXObject('Microsoft.XMLHTTP');
  }
  if (!ajax) return false;
  
  ajax.onreadystatechange = function() {
    if (ajax.readyState==4) {
      if (ajax.status == 200) {
        var parts = ajax.responseText.split("\n\n");
        getElement(out).innerHTML = parts[1];
//        repostme_bar_replace(parts[0], '', 'repostme_inline_bar');
        update_repostme_bars();
      } else {
        getElement(out).innerHTML = '<span class="error">' + ajax.responseText + '</span>';
      }
      ajax = null;
    }
  }
  
  getElement(out).innerHTML = '<i>Loading!</i>';
  
  ajax.open('GET', url, true);
  ajax.send(null);
  return true;
}


function add_favorite(id, element) {
  ajax = null;
  if (window.XMLHttpRequest) {
    ajax = new XMLHttpRequest();
  } else {
    ajax = new ActiveXObject('Microsoft.XMLHTTP');
  }
  if (!ajax) return false;

  ajax.onreadystatechange = function() {
    if (ajax.readyState==4) {
      if (ajax.status == 200) {
        for (var i=0; i<element.childNodes.length; i++) {
          if (element.childNodes[i].nodeType == 1  &&  element.childNodes[i].nodeName.toLowerCase() == 'span') {
            element.childNodes[i].innerHTML = 'FAV';
          }
        }
      }
      ajax = null;
    }
  }

  for (var i=0; i<element.childNodes.length; i++) {
    if (element.childNodes[i].nodeType == 1  &&  element.childNodes[i].nodeName.toLowerCase() == 'span') {
      element.childNodes[i].innerHTML = '---';
    }
  }

  ajax.open('GET', 'add_favorite.php?id='+id, true);
  ajax.send(null);
  return true;
}


function make_twitter() {
  new TWTR.Widget({
    version: 2,
    type: 'profile',
    rpp: 10,
    interval: 6000,
    width: 'auto',  
    height: 230,
    theme: {
      shell: {
        background: '#E9F0ED',
        color: '#ff0000'
      },
      tweets: {
        background: '#DCE6E2',
        color: '#5a5e5c',
        links: '#ff0000'
      }
    },
    features: {
      scrollbar: true,
      loop: true,
      live: true,
      hashtags: false,
      timestamp: true,
      avatars: true,
      behavior: 'all'
    }
  }).render().setUser('RepostMe').start();
}