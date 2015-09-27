
// format -  international: 2, USA: 0, EUR: 1
var g_date_format = 0;
var g_date_separator = '/';
var SP_DIV = '<div class="spacer">&nbsp;</div>';
var system_msg_timer;
var system_msg;

// PATH_BASE: /dev/FIT/
// PATH_WEB:  http://localhost/dev/FIT/
// PATH_SELF: patient_reg/1

//LOCAL
//var PATH_BASE = '/';
//LIVE
var PATH_BASE = '/projectportal/';
var PATH_WEB  = window.location.protocol + '//' + window.location.host + PATH_BASE;
var PATH_SELF = window.location.pathname;

// error codes
var E_OK = 0;
var E_ENTRY_REQUIRED = 1;
var E_INVALID_FORMAT = 2;
var E_SELECTION_REQUIRED = 3;
var E_INVALID_DATE = 4;
var E_OUT_OF_RANGE = 5;
var E_VALUE_NOT_UNIQUE = 6;
var E_USER_NO_EMAIL = 7;
var E_USER_NO_USERNAME = 8;
var E_USER_WRONG_PASSWORD = 9;
var E_FILE_NOT_FOUND = 10;





function isset( varname )
{
  return ( typeof(varname) != 'undefined' );
}

function is_int( s )
{
  return (s.toString().search(/^-?[0-9]+$/) == 0);
}


function is_uint( s )
{
  return (s.toString().search(/^[0-9]+$/) == 0);
}

Array.prototype.removeByValue = function(val)
{
  for(var i=0; i<this.length; i++)
  {
    if(this[i] == val)
    {
      this.splice(i, 1);
      break;
    }
  }
}


function getElement( elementId )
{
  alert( 'deprecated getElement' );
  return Dom.get( elementId );
}

function zeroPad( n, totalDigits )
{ 
  n = n.toString();
  for( i = 0; n.length < totalDigits; i++)
    n = '0' + n; 

  return n; 
}


function addslashes( str )
{
  return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
}

function CORE_uri_encode( str )
{
  // encode backslashes (!1), slashes (!2), & (!3), ? !4 ! (!0)
  var search_regex  = [ /!/g, /\\/g, /\//g, /&/g, /\?/g, /"/g, /'/g, / /g, /\+/g ];
  var replace_str   = [ '!0', '!1',  '!2',  '!3', '!4',  '!5', '!6', '!7', '!8' ];

  for( var i=0; i < search_regex.length; i++ )
    str = (str + '').replace( search_regex[i], replace_str[i] );

  return encodeURIComponent( str );
}

function CORE_uri_decode( str )
{

  var search_regex  = [ /!1/g, /!2/g, /!3/g, /!4/g, /!5/g, /!6/g, /!7/g, /!8/g, /!0/g ];
  var replace_str   = [ '\\',  '/',   '&',   '?',   '"',   '\'',  ' ',   '+',   '!' ];

  for( var i=0; i < search_regex.length; i++ )
    str = (str + '').replace( search_regex[i], replace_str[i] );

  return str;
}


function escape_regex( str )
{
  // escape regex special characters .^$*+?()[{\
  return (str + '').replace(/[\/+\\\-\]\^$?()[{}.*]/g, '\\$&').replace(/\u0000/g, '\\0');
}



function hide( divName )
{
  if( Dom.get( divName ) )
    Dom.get( divName ).style.display = "none";
}

function show( divName )
{
  if( Dom.get( divName ) )
    Dom.get( divName ).style.display = "block";
}

function hide_sys_msg( )
{
  if( Dom.get( 'sys_msg' ) )
    setTimeout( "hide('sys_msg');", 5000 );
}

function error_border( elementName, errorOn )
{
  myElement = Dom.get( elementName );
  if( myElement )
  {
    if( errorOn )
      myElement.style.borderColor = "#961e1f";
    else
      myElement.style.borderColor = "#8f8f90";
  }
}


function show_popup ( id, reset_form )
{
  var top, left;

  show_popup_iframe( id, document.body, 49 );
  
  // if the frame does not exist, create it
  if( !Dom.get( id + "_popup_frame" ) )
  {
    var popup_frame = document.createElement("div");
  
    popup_frame.innerHTML = '<table class="popup_frame"><tr><td colspan="3" class="popup_frame"></td></tr><tr><td class="popup_frame"></td>'+
              '<td class="popup_content" id="' + id + '_popup_content" >' +
              '</td><td class="popup_frame"></td></tr><tr><td colspan="3" class="popup_frame"></td></tr></table>';
    popup_frame.className = "popup";
    popup_frame.id = id + "_popup_frame";
    
    document.body.appendChild( popup_frame );

    var popup_content = Dom.get( id + "_popup" );
    var content_area = Dom.get( id + "_popup_content" );
    var old_content = popup_content.parentNode.removeChild( popup_content );
    content_area.appendChild( old_content );
    old_content.style.display = "block";

    top = (document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop )+140+'px';
    left = (document.body.offsetWidth-old_content.offsetWidth)/2+'px';
    popup_frame.style.top = top;
    popup_frame.style.left = left;
  }
  
  // if the form is currently invisible, it is unlocked, so show it
  if( Dom.get( id + "_popup_frame" ).style.display == "none" || Dom.get( id ).style.display == "none" )
  {
    if( reset_form )
      eval( 'resetForm_' + id + '()' );
    show_popup_iframe( id, document.body, 49 );
    show( id + "_popup_frame" );
  }
  adjust_popup_iframe( id );
  Dom.get( id ).elements[0].focus();
}

function show_popup_iframe( id, parent, zindex )
{
  if( document.body.insertAdjacentHTML )
  {
    if( !Dom.get( id + "_popup_iframe" ) )
    {
      var ieMat = document.createElement("iframe");

      ieMat.src = "about:blank";
      ieMat.scrolling = "no";
      ieMat.frameborder = "0";
      ieMat.id = id + "_popup_iframe";
      ieMat.className = "popup_iframe";
      ieMat.style.filter = "progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0);";
      ieMat.style.zIndex = zindex;
      parent.appendChild( ieMat );
    }
    else
      show( id + "_popup_iframe" );
  }
}


function hide_popup ( id )
{
  if( Dom.get( id + "_popup_frame" ) )
    hide( id + "_popup_frame" );
  
  if( Dom.get( id + "_popup_iframe" ) )
    hide( id + "_popup_iframe" );
}

function adjust_popup_iframe( id )
{
  var ieMat = Dom.get( id + "_popup_iframe" );
  if( ieMat )
  {
    var popup = Dom.get( id + "_popup_frame" );
  
    ieMat.style.top = popup.style.top;
    ieMat.style.left = popup.style.left;
    ieMat.style.width = popup.offsetWidth + "px";
    ieMat.style.height = popup.offsetHeight + "px";
  }
}


function getRadioValue( radioObj )
{
  for( var i = 0; i < radioObj.length; i++ )
    if( radioObj[i].checked )
      return radioObj[i].value;
  
  return false;
}


function get_system_msg( )
{
  //system_msg = new Xhr( 'system_msg', 'xhr/system_msg', 'show_system_msg' );
  //system_msg.request( 0 );
}

function show_system_msg( obj )
{
  if( !obj )
    return;

  eval( 'obj = '+obj );

  Dom.get( 'page_system_msg' ).innerHTML = '<div id="system_msg" class="system_msg '+obj.type+'">'+obj.text+'</div>';

  system_msg_timer = setTimeout( 'hide("system_msg")', 5000 );
}

function addLoadEvent( func )
{ 
  var oldonload = window.onload; 
  if( typeof window.onload != 'function' )
  { 
    window.onload = function (){ eval( func ); };
  } 
  else
  { 
    window.onload = function()
    { 
      if( oldonload )
        oldonload(); 
      eval( func );
    } 
  } 
}

function addClickEvent( func )
{ 
  var old_func = document.onclick; 
  if( typeof document.onclick != 'function' )
  { 
    document.onclick = function (){ eval( func ); };
  } 
  else
  { 
    document.onclick = function()
    { 
      if( old_func )
        old_func(); 
      eval( func );
    } 
  } 
}

function setOnclick( id, func )
{
alert( 'deprecated: setOnclick' );
  Dom.get( id ).onclick = function(){ eval( func ); };
}

// use to clear event handler and replace
function setEventHandler( evnt, id, func )
{
  eval( 'Dom.get( id ).' + evnt + ' = function(){ eval( func ) };' );
}

// use to append, or when clear not required
function addEventHandler( evnt, id, func )
{ 
  eval( 'var oldfunc = Dom.get( id ).' + evnt + ';' );

  eval( 'var oldtype = typeof Dom.get( id ).' + evnt + ';' );

  if( oldtype != 'function' )
  { 
    eval( 'Dom.get( id ).' + evnt + ' = function(){ eval( func ) };' );
  } 
  else
  { 
    eval( 'Dom.get( id ).' + evnt + ' = function(){ if( oldfunc ) oldfunc(); eval( func ); };' );
  } 
}

function goBack()
{
  window.history.back();
}


function get_label( element )
{
  if( typeof( element ) == 'string' )
    element = Dom.get( element );

  if( element.parentNode )
    if( element.parentNode.tagName=='label' )
      return element.parentNode;

  var labels=document.getElementsByTagName("label");

  for( var i = 0; i < labels.length; i++ )
    if( labels[i].htmlFor == inputElem.id )
      return labels[i];

  return false;
}


function Xhr( me, loc, func )
{
  this.me = me
  this.location = loc;
  this.func = func;

  //------------------------------------------
  this.request = function( q_str )
  {  
    // ======= Send HttpRequest =======

    // branch for native XMLHttpRequest object
    if( window.XMLHttpRequest )
      this.xhr_handle = new XMLHttpRequest();
    // branch for IE/Windows ActiveX version
    else if( window.ActiveXObject )
      this.xhr_handle = new ActiveXObject( "Microsoft.XMLHTTP" );
    else
      return;

    eval( 'this.xhr_handle.onreadystatechange = function() {  '+this.me + '.response(); };' );
    this.xhr_handle.open( "POST", PATH_WEB + this.location, true );
    this.xhr_handle.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" );
    if( !q_str )
      q_str = '';
    this.xhr_handle.send( q_str );
  }

  //-------------------------------------------
  this.response = function ( )
  {
    // only if req shows "complete" and only if "OK"
    if ( this.xhr_handle.readyState == 4 && this.xhr_handle.status == 200 )
    {
      // ======== Call form-specific response handling function
      if( this.xhr_handle.responseText )
        eval( func+'( this.xhr_handle.responseText )' );
    } 

  }

}






var Dom =
{
  get: function( element )
  {
    if (typeof element === 'string')
      return document.getElementById( element );
    else
      return element;
  },
  
  add: function( element, dest )
  {
    var element = this.get( element );
    var dest = this.get( dest );
    dest.appendChild( element );
  },

  insertAfter: function( element, ref )
  {
    ref = Dom.get( ref ); 
    ref.parentNode.insertBefore( element, ref.nextsibling ); 
  },

  insertBefore: function( element, ref )
  {
    ref = Dom.get( ref ); 
    ref.parentNode.insertBefore( element, ref ); 
  },

  remove: function( element )
  {
    var element = this.get( element );
    element.parentNode.removeChild( element );
  },

  create: function( type, id, text )
  {
    var element = document.createElement( type );
    if( id != '' )
      element.setAttribute( 'id', id );
    element.innerHTML = text;
    return element;
  },

  goto: function( path )
  {
    window.document.location = PATH_WEB + path;
  },

  addClass: function( element, clas )
  {
    eval( 'var regex = /\\b'+clas+'\\b/' );

    if( !Dom.get( element ).className.match( regex ) )
      Dom.get( element ).className += " "+clas;
  },

  removeClass: function( element, clas )
  {
    eval( 'var regex = /\\b'+clas+'\\b/' );

    Dom.get( element ).className = Dom.get( element ).className.replace( regex, '' );
  }
};