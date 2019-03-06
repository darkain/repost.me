/*******************************************/
/* Repost.Me (c) 2010 Vincent E. Milum Jr. */
/*******************************************/

// http://www.daftlogic.com/projects-online-javascript-obfuscator.htm

var repostme_version    = '0.4.20100524';
var repostme_size       = 16;
var repostme_background = 'DCE6E2';
var repostme_color      = '5A5E5C';
var repostme_border     = '5A5E5C';
var repostme_fade       = 'white';
var repostme_norepost   = false;
var repostme_text       = 'Tell your <b>{site}</b> friends about "<i>{title}</i>"';
var repostme_jquery     = (jQuery) ? (jQuery) : (null);


//TODO: *ALL* animations will ONLY be available if and ONLY IF jQuery is available!
//ALSO: test for a specific MINIMUM version of jQuery to ensure compatibility!!


var repostme_buttons = [
  'Twitter', 'Facebook', 'MySpace', '', 'Bebo', 'Delicious',
  'Digg', 'Email', 'FriendFeed', 'Google', 'LinkedIn',
  'LiveJournal', 'Reddit', 'StumbleUpon', 'Technorati', 'Yahoo'
];


if (typeof(repostme_objects) === 'undefined') repostme_objects = [];



if (typeof(repostme_bar) === 'undefined') {


  repostme_bar_obj = function(u, t) {
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
        
    
    this.btn = function(b, h) {
      var r = null;
      for (var i=0; i<this.buttons.length; i++) {
        if (this.buttons[i].id == b) r = this.buttons[i];
        if (h) {
          var e = this.get_e(this.buttons[i].pid);
          if (e) e.style.display = 'none';
        }
      }
      return r;
    };
    
    
    this.focus = function(b) {
      var r   = this.get_e(this.id);
      var p   = this.get_pos(r);
      var btn = this.btn(b, 1);

      if (btn) {
        e = this.get_e(btn.pid);
        if (e) {
          e.style.width   = Math.max(300, r.offsetWidth - 6) + 'px';
          e.style.left    = (p[0]) + 'px';
          e.style.top     = (p[1]  + btn.h) + 'px';
          e.style.display = 'block';
        }
      }
    };
    
  
    this.blur = function(b) {
      var btn = this.btn(b);
      if (btn) {
        e = this.get_e(btn.pid);
        if (e) e.style.display = 'none';
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
      
      //Add a spacer
      var sp     = new repostme_stylesheet();
      sp.font    = '1px';
      sp.padding = '4px';
      sp.display = 'inline';
        
      if (!repostme_norepost) {
        var btn = new repostme_btn_obj(n_u, (repostme_size==16?56:101), repostme_size, 'repostme', 'Create a short URL and QR Code on <b>Repost.Me</b> for "<i>'+this.t+'</i>"', this);
        str += btn.render();
        this.buttons.push(btn);
        
        str += '<span style="' + sp.render() + '">&nbsp;</span>';
      }
      
      for (var i=0; i<repostme_buttons.length; i++) {
        //Add a spacer
        if (repostme_buttons[i] == '') {        
          str += '<span style="' + sp.render() + '">&nbsp;</span>';
          
        //Render Individual Button!  
        } else {                                
          var a = repostme_text;
          a = a.replace(/{title}/g, this.t);
          a = a.replace(/{site}/g,  repostme_buttons[i]);
          var btn = new repostme_btn_obj(n_u, repostme_size, repostme_size, repostme_buttons[i], a, this);
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



  repostme_btn_obj = function(u, w, h, site, a, r) {
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
      a_s.cursor  = 'pointer';
      str += '<a target="_blank" style="' + a_s.render() + '" href="' + this.u;  //TODO: htmlspecialchars for this.u
      if (this.site != 'repostme') str += '&amp;site=' + site;
      str += '" ';
      str += 'onmouseover="javascript:__repostme_show(\''+this.id+'\', \'' + this.r.id + '\')" ';
      str += 'onmouseout="javascript:__repostme_hide(\''+this.id+'\', \'' + this.r.id + '\')">';

      //Render the popup window
      if (this.a !== '') {
        var p_s        = new repostme_stylesheet();
        p_s.border     = '2px solid #' + repostme_border;
        p_s.background = '#' + repostme_background + " url('http://repostme.com/fade-" + this.f + ".png') repeat-x top left";
        p_s.color      = '#' + repostme_color;
        p_s.font       = 'normal 16pt/22pt sans-serif';
        p_s.display    = 'none';
        p_s.cursor     = 'pointer';
        p_s.overflow   = 'hidden';
        p_s.width      = '400px';
        p_s.position   = 'absolute';
        p_s.z_index    = '2147483647';
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
        x_s.height   = '34px';
        x_s.width    = '1px';
        x_s.margin   = '0 0 0 -1px';
        str += '<span style="' + x_s.render() + '">&nbsp;</span>';
        
        //Display the site's large icon inside of the box
        if (this.site != 'repostme') {
          var i32_s = new repostme_stylesheet();
          i32_s.position = 'absolute';
          i32_s.margin   = '1px';
          i32_s.cursor   = 'pointer';
          i32_s.display  = 'inline-block';
          i32_s.height   = '32px';
          i32_s.width    = '32px';
          str += '<img border="0" style="' + i32_s.render() + '" ';
          str += 'src="http://repostme.com/32/' + this.site + '.png" />';
        
          //Display a caption inside of the box
          var alt_s = new repostme_stylesheet();
          alt_s.padding  = '0 4px 0 ' + (this.site != 'repostme' ? '38px' : '4px');
          alt_s.color    = '#' + repostme_color;
          alt_s.overflow = 'hidden';
          alt_s.cursor   = 'pointer';
          alt_s.text_shadow         = '2px 2px 5px rgba(127, 127, 127, 0.5)';
          alt_s._moz_text_shadow    = '2px 2px 5px rgba(127, 127, 127, 0.5)';
          alt_s._khtml_text_shadow  = '2px 2px 5px rgba(127, 127, 127, 0.5)';
          alt_s._webkit_text_shadow = '2px 2px 5px rgba(127, 127, 127, 0.5)';
          str += '<span style="' + alt_s.render() + '">' + this.a + '</span>';
          
        } else {  // Repost.Me button has special display stuffs!
        
          var alt_s = new repostme_stylesheet();
          str += '<div onclick="javascript:return false;" style="' + alt_s.render() + '">';
          str += 'Shorten this URL<br />';
          str += 'Share this URL<br />';
          str += 'Generate QR Code<br />';
          str += '<div style="font-size:7px">&nbsp;</div>';
          str += 'Add Repost.Me To:<br />';
          str += 'WordPress Blog<br />';
          str += 'Personal Web Site<br />';
          str += '</div>';
          
        }
        
        str += '</span>';
      }

      //Render the button in the main bar
      var i16_s     = new repostme_stylesheet();
      i16_s.width   = this.w + 'px';
      i16_s.height  = this.h + 'px';
      i16_s.padding  = '0 1px';
      i16_s.display = 'inline';
      i16_s.cursor  = 'pointer';
      str += '<img border="0" id="' + this.iid + '" style="' + i16_s.render() + '" ';
      if (this.h == 16) {
        str += 'src="http://repostme.info/' + this.h + '/' + this.site + '.png" />';
      } else {
        str += 'src="http://repostme.com/' + this.h + '/' + this.site + '.png" />';
      }
      
      str += '</a>';
      return str;
    };
  }


///////////////////////////////////////////////////////////////////////////////////////////


  repostme_stylesheet = function() {
    // MAIN DISPLAY 
    this.background      = 'none';
    this.display         = 'block';
    this.overflow        = 'auto';
    this.visibility      = 'visible';
    this.cursor          = 'default';
    
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
    
    //PAGING
    this.page_break_after  = 'avoid';
    this.page_break_before = 'avoid';
    this.page_break_inside = 'avoid';
    
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
  
  
  __repostme_show = function(btn, bar) {
    for (var i=0; i<repostme_objects.length; i++) {
      if (repostme_objects[i].id == bar) {
        repostme_objects[i].focus(btn);
      }
    }
  }
    

  
  __repostme_hide = function(btn, bar) {
    for (var i=0; i<repostme_objects.length; i++) {
      if (repostme_objects[i].id == bar) {
        repostme_objects[i].blur(btn);
      }
    }
  }
  


  repostme_bar_replace = function(url, title, element) {
    if (typeof(element) == 'string') element = document.getElementById(element);
    var bar = new repostme_bar_obj(url, title);
    element.innerHTML = bar.render();
  }


  repostme_bar = function(url, title) {
    var bar = new repostme_bar_obj(url, title);
    document.write(bar.render());
  }

}  

