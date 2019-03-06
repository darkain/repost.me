/*******************************************/
/* Repost.Me (c) 2010 Vincent E. Milum Jr. */
/*******************************************/

// http://www.daftlogic.com/projects-online-javascript-obfuscator.htm

var repostme_version    = '0.3.20100227';
var repostme_size       = 16;
var repostme_background = 'DCE6E2';
var repostme_color      = '5A5E5C';
var repostme_border     = '5A5E5C';
var repostme_fade       = 'white';
var repostme_text       = 'Click to tell your <b>{site}</b> friends about "<i>{title}</i>"';

var repostme_buttons = [
  'Twitter', 'Facebook', 'MySpace', '', 'Bebo', 'Delicious',
  'Digg', 'FeedBurner', 'FriendFeed', 'Google', 'LinkedIn',
  'LiveJournal', 'Reddit', 'StumbleUpon', 'Technorati', 'Yahoo'
];


if (typeof(repostme_objects) === 'undefined') repostme_objects = [];



if (typeof(repostme_bar) === 'undefined') {


  function repostme_bar_obj(u, t) {
    repostme_objects.push(this);
    this.id   = 'repostme_bar_' + Math.floor(Math.random()*(Math.pow(2,30)));
    this.buttons = [];
    this.fading  = false;
    this.u = u;
    this.t = t;
   
    
    this.get_e = function(e) {
      if (typeof(e) == 'string') return document.getElementById(e);
      return e;
    };
    
    
    //Get the current style of a given element
    this.get_s = function(e) {
      e = this.get_e(e);
      if (e) {
        if (typeof e.currentStyle        !== 'undefined') return e.currentStyle;
        if (typeof e.computedStyle       !== 'undefined') return e.computedStyle;
        if (typeof document.defaultView  !== 'undefined') return document.defaultView.getComputedStyle(e, null);
        if (typeof e.style               !== 'undefined') return e.style;
      }
      return null;
    };
    
    
    this.get_pos = function(e) {
      e = this.get_e(e);
      var t = [0, 0];
      while (e) {
        var s = this.get_s(e);
        if (s) {
          if (s.position === 'absolute') break;
          if (s.position === 'fixed'   ) break;
          if (s.position !== 'relative') {
            t[0] += e.offsetLeft;
            t[1] += e.offsetTop;
          }
        }
        e = e.offsetParent;
      }
      return t;
    };
    
    
    this.get_op = function(e) {
      s = this.get_s(e);
      if (s) {
      
        //CSS 3
        if (typeof s.opacity !== 'undefined') 
          return s.opacity;

        // FireFox (legacy)
        if (typeof s.MozOpacity !== 'undefined') 
          return s.MozOpacity;
        
        // Konqour (legacy)
        if (typeof s.KhtmlOpacity !== 'undefined') 
          return s.KhtmlOpacity ;
        
        // Safari (legacy)
        if (typeof s.WebkitOpacity !== 'undefined') 
          return s.WebkitOpacity ;
        
        // Internet Explorer (legacy)
        if (typeof s.filters !== 'undefined') {
          var filter = repostme_element.style.filter;
          var pos = filter.indexOf('opacity');
          if (pos >= 0) pos = filter.indexOf('=', pos);
          var fade = parseInt(filter.substr(pos));
          if (isNaN(fade)) fade = parseInt(filter.substr(pos + 1));
          if (isNaN(fade)) fade = parseInt(filter.substr(pos + 2));
          if (isNaN(fade)) fade = parseInt(filter.substr(pos + 3));
          if (!isNaN(fade)) return fade / 100;
        }
      }
      return 1;
    };
        
    
    this.set_op = function(e, v) {
      e = this.get_e(e);
      try { e.style.opacity       = v; } catch (err) {}   //Standard CSS 3
      try { e.style.MozOpacity    = v; } catch (err) {}   //Legacy FireFox/Mozilla
      try { e.style.KhtmlOpacity  = v; } catch (err) {}   //Legacy Konqour
      try { e.style.WebkitOpacity = v; } catch (err) {}   //Legacy Safari/Chrome
      try {                                               //Legacy Internet Explorer
        e.style.filter = 'alpha(opacity=' + (v*100) + ')';
      } catch (err) {}
    };
    
    
    this.btn = function(b, o) {
      var r = null;
      for (var i=0; i<this.buttons.length; i++) {
        if (this.buttons[i].id == b) r = this.buttons[i];
        if (o) this.buttons[i].o = o;
      }
      return r;
    };
    
    
    this.focus = function(b) {
      var r   = this.get_e(this.id);
      var p   = this.get_pos(r);
      var btn = this.btn(b, 0.3);

      if (btn) {
        btn.o = 1;
        e = this.get_e(btn.pid);
        if (e) {
          e.style.width   = Math.max(300, r.offsetWidth - 6) + 'px';
          e.style.left    = (p[0]) + 'px';
          e.style.top     = (p[1]  + btn.h) + 'px';
          e.style.display = 'block';
        }
      }
      
      if (!this.fading) {
        var x = this;
        x.fading = true;
        setTimeout(function(){x.fade(x);}, 100);
      }
    };
    
  
    this.blur = function(b) {
      var btn = this.btn(b, 1);
      if (btn) {
        e = this.get_e(btn.pid);
        if (e) e.style.display = 'none';
      }
      
      if (!this.fading) {
        var x = this;
        x.fading = true;
        setTimeout(function(){x.fade(x);}, 100);
      }
    };
    
    
    this.fade = function(x) {
      var ok = true;
      for (var i=0; i<x.buttons.length; i++) {
        var b = x.buttons[i];
        var o = parseFloat(x.get_op(b.iid));
        if (b.o > o) {
          x.set_op(b.iid, b.o);
//          if (b.o - o < 0.1) {
//            x.set_op(b.iid, o);
//          } else {
//            x.set_op(b.iid, o+0.1);
//            ok = false;
//          }
        } else if (b.o < o) {
          if (o - b.o < 0.1) {
            x.set_op(b.iid, o);
          } else {
            x.set_op(b.iid, o-0.1);
            ok = false;
          }
        }
      }
      
      if (ok) {
        x.fading = false;
      } else {
        setTimeout(function(){x.fade(x);}, 30);
      }
    };
  
  
    this.render = function() {
      if (!this.u) this.u = document.location.toString();
      var n_u = this.u;
      var str = '';
      
      if (n_u.indexOf('http://repost.me/') < 0) {
        n_u = 'http://repost.me/?url=' + escape(n_u);
      } else {
        n_u += '?go=1';
      }
      
      if (!this.t) this.t = document.title;
      this.t = this.t.replace(/^\s+/,   '').replace(/\s+$/,    '');  //Trim the string
      this.t = this.t.replace('&', '&amp;').replace('"', '&quot;');  //Remove & and "
      this.t = this.t.replace('>',  '&gt;').replace('<',   '&lt;');  //Remove < and >
      if (this.t == '') this.t = 'this';
      
      
      if (repostme_size == 32  ||  repostme_size === 'large') {
        repostme_size = 32;
      } else {
        repostme_size = 16;
      }
      

      //Create the main bar element
      var bar         = new repostme_stylesheet();
      bar.display     = 'inline-block';
      bar.height      = repostme_size + 'px';
      bar.white_space = 'norap';
      bar.overflow    = 'hidden';
      bar.font        = '1px';
      str += '<span class="snap_noshots" id="' + this.id + '" style="' + bar.render() + '">';
      
      var btn = new repostme_btn_obj(n_u, (repostme_size==16?56:101), repostme_size, 'repostme', 'Create a short URL on Repost.Me for '+this.t, this);
      str += btn.render();
      this.buttons.push(btn);
      
      //Add a spacer
      var sp     = new repostme_stylesheet();
      sp.font    = '1px';
      sp.padding = '4px';
      sp.display = 'inline';
      str += '<span style="' + sp.render() + '">&nbsp;</span>';
      
      for (var i=0; i<repostme_buttons.length; i++) {
        //Add a spacer
        if (repostme_buttons[i] == '') {        
          str += '<span style="' + sp.render() + '">&nbsp;</span>';
          
        //Render Individual Button!  
        } else {                                
          var a = repostme_text;
          a = a.replace(/{title}/g, this.t);
          a = a.replace(/{site}/g,  repostme_buttons[i]);
          btn = new repostme_btn_obj(n_u, repostme_size, repostme_size, repostme_buttons[i], a, this);
          str += btn.render();
          this.buttons.push(btn);
        }
      }

      str += '</span>';
      return str;
      return '';
    };

  }





///////////////////////////////////////////////////////////////////////////////////////////



  function repostme_btn_obj(u, w, h, site, a, r) {
    this.site = site.toLowerCase();
    this.id   = this.site + Math.floor(Math.random()*(Math.pow(2,30)));
    this.pid  = 'repostme_popup_' + this.id;
    this.iid  = 'repostme_image_' + this.id;
    this.f    = repostme_fade.toLowerCase();
    this.w    = w;
    this.h    = h;
    this.a    = a;
    this.u    = u;
    this.r    = r;
    this.o    = 1;


    //Generate HTML for button and popups
    this.render = function() {
      var str   = '';

      //Validate fade colour
      if (this.f !== 'white'  && this.f !== 'grey'    &&
          this.f !== 'black'  && this.f !== 'red'     &&
          this.f !== 'green'  && this.f !== 'blue'    &&
          this.f !== 'cyan'   && this.f !== 'magenta' &&
          this.f !== 'yellow' && this.f !== 'none') {
        this.f = 'white';
      }
              
      //Anchor tag / AKA the LINK!
      var a_s     = new repostme_stylesheet();
      a_s.display = 'inline';
      str += '<a target="_blank" style="' + a_s.render() + '" href="' + this.u;
      if (this.site != 'repostme') str += '&amp;site=' + site;
      str += '" ';
      str += 'onmouseover="javascript:repostme_show_button(\''+this.id+'\', \'' + this.r.id + '\')" ';
      str += 'onmouseout="javascript:repostme_hide_button(\''+this.id+'\', \'' + this.r.id + '\')">';

      if (this.site != 'repostme') {
        var p_s        = new repostme_stylesheet();
        p_s.border     = '2px solid #' + repostme_border;
        p_s.background = '#' + repostme_background + " url('http://img.repost.me/fade-" + this.f + ".png') repeat-x top left";
        p_s.color      = '#' + repostme_color;
        p_s.font       = 'normal 16pt/22pt sans-serif';
        p_s.display    = 'none';
        p_s.overflow   = 'hidden';
        p_s.width      = '400px';
        p_s.position   = 'absolute';
        p_s.padding    = '0 5px 3px 0';
        p_s.z_index    = '999999999';
        p_s.border_radius         = '7px';
        p_s._moz_border_radius    = '7px';
        p_s._khtml_border_radius  = '7px';
        p_s._webkit_border_radius = '7px';
        p_s.box_shadow            = '5px 5px 5px rgba(127, 127, 127, 0.5)';
        p_s._moz_box_shadow       = '5px 5px 5px rgba(127, 127, 127, 0.5)';
        p_s._khtml_box_shadow     = '5px 5px 5px rgba(127, 127, 127, 0.5)';
        p_s._webkit_box_shadow    = '5px 5px 5px rgba(127, 127, 127, 0.5)';
        str += '<span id="' + this.pid + '" style="' + p_s.render() + '">';

        
        //Ensure a minimum height for the popup box
        var x_s = new repostme_stylesheet();
        x_s.cssfloat = 'left';
        x_s.height   = '32px';
        x_s.width    = '1px';
        x_s.margin   = '0 0 0 -1px';
        str += '<span style="' + x_s.render() + '">&nbsp;</span>';
        
        //Display the site's large icon inside of the box
        var i32_s = new repostme_stylesheet();
        i32_s.position = 'absolute';
        i32_s.width    = '32px';
        i32_s.height   = '32px';
        i32_s.margin   = '1px';
        i32_s.display  = 'inline-block';
        str += '<img border="0" style="' + i32_s.render() + '" ';
        str += 'src="http://img.repost.me/32/' + this.site + '.png" />';
        
        //Display a caption inside of the box
        if (this.a) {
          var alt_s = new repostme_stylesheet();
          alt_s.padding  = '0 0 0 38px';
          alt_s.color    = '#' + repostme_color;
          alt_s.overflow = 'hidden';
          alt_s.text_shadow         = '2px 2px 5px rgba(127, 127, 127, 0.5)';
          alt_s._moz_text_shadow    = '2px 2px 5px rgba(127, 127, 127, 0.5)';
          alt_s._khtml_text_shadow  = '2px 2px 5px rgba(127, 127, 127, 0.5)';
          alt_s._webkit_text_shadow = '2px 2px 5px rgba(127, 127, 127, 0.5)';
          str += '<span style="' + alt_s.render() + '">' + this.a + '</span>';
        }
        
        str += '</span>';
      }

      //Render the button in the main bar
      var i16_s     = new repostme_stylesheet();
      i16_s.width   = this.w + 'px';
      i16_s.height  = this.h + 'px';
      i16_s.margin  = '0 1px';
      i16_s.display = 'inline';
      str += '<img border="0" id="' + this.iid + '" style="' + i16_s.render() + '" ';
      str += 'src="http://img.repost.me/' + this.h + '/' + this.site + '.png" />';
      
      str += '</a>';
      return str;
    };
  }


///////////////////////////////////////////////////////////////////////////////////////////


  function repostme_stylesheet() {
    // MAIN DISPLAY 
    this.background      = 'none';
    this.display         = 'block';
    this.overflow        = 'auto';
    this.visibility      = 'visible';
    
    // BORDER
    this.border          = 'none';
    this.border_radius   = '0';
    this.outline         = 'none';
    this.box_shadow      = 'none';
    
    // PRIMARY TEXT EFFECTS
    this.color           = '#000';
    this.font            = 'normal';
    this.direction       = 'ltr';
    this.text_decoration = 'none';
    this.text_transform  = 'none';
    this.text_shadow     = 'none';
    this.text_indent     = '0';
    this.text_align      = 'left';
    this.vertical_align  = 'top';
    this.white_space     = 'normal';
    this.letter_spacing  = 'normal';
    this.word_spacing    = 'normal';
    
    // SIZING
    this.width           = 'auto';
    this.height          = 'auto';
    this.line_height     = 'normal';
    this.margin          = '0';
    this.padding         = '0';
    
    //POSITIONING
    this.position        = 'static';
    this.top             = 'auto';
    this.right           = 'auto';
    this.bottom          = 'auto';
    this.left            = 'auto';
    this.z_index         = 'auto';
    this.cssfloat        = 'none';
    this.clear           = 'none';
    
    //BROWSER SPECIFIC LEGACY CRAP
    this._webkit_box_shadow    = 'none';
    this._khtml_box_shadow     = 'none';
    this._moz_box_shadow       = 'none';
    this._webkit_text_shadow   = 'none';
    this._khtml_text_shadow    = 'none';
    this._moz_text_shadow      = 'none';
    this._webkit_border_radius = '0';
    this._khtml_border_radius  = '0';
    this._moz_border_radius    = '0';
    this.filter                = 'none';
  
    
    this.render = function() {
      var r = '';
      for (p in this) { 
        if (typeof(this[p]) == 'string') {
          var n = p;
          n = n.replace(/_/g,  '-');
          n = n.replace('css', '');
          r += n + ':' + this[p] + ';';
        }
      }
      return r;
    };
  }


///////////////////////////////////////////////////////////////////////////////////////////
  


  function repostme_show_button(btn, bar) {
    for (var i=0; i<repostme_objects.length; i++) {
      if (repostme_objects[i].id == bar) {
        repostme_objects[i].focus(btn);
      }
    }
  }
    

  
  function repostme_hide_button(btn, bar) {
    for (var i=0; i<repostme_objects.length; i++) {
      if (repostme_objects[i].id == bar) {
        repostme_objects[i].blur(btn);
      }
    }
  }
  


  function repostme_bar_replace(url, title, element) {
    if (typeof(element) == 'string') element = document.getElementById(element);
    var bar = new repostme_bar_obj(url, title);
    element.innerHTML = bar.render();
  }


  function repostme_bar(url, title) {
    var bar = new repostme_bar_obj(url, title);
    document.write(bar.render());
  }

}  

