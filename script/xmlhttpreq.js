

function Xhr_search( me, id, query )
{
  this.id = id;
  this.me = me;

  this.query = query;

  this.clear = 0;

  this.location = "xhr/search";
//this.location = 'xhr_test2';

  this._write_back = null;
  this._show = null;
  this._query = null;
  this.resp_obj = null;

  //----------------------------
  // do constructor tasks
  addLoadEvent( this.me+".create()" );



  //------------------------------------------
  this.create = function( )
  {
    // create frame for results
    popup_frame = Dom.create( 'div', this.id + '_popup', '' );
    popup_frame.className = 'xhr_search_result';
    Dom.insertBefore( popup_frame, this.id );

    // add event handlers
    addClickEvent( "setTimeout( \""+this.me+".hide()\", 20 );" );

    addEventHandler( 'onblur', this.id, "setTimeout( \""+this.me+".hide()\", 20 );" );

    addEventHandler( 'onkeyup', this.id, this.me+".request();" );

    addEventHandler( 'onfocus', this.id, this.me+".request();" );

    addEventHandler( 'onmousedown', this.id+'_popup', this.me+".keep_open();" );

    addEventHandler( 'onclick', this.id, this.me+".keep_open();" );
  }

  //------------------------------------------
  this.write_back = function( value )
  {
    if( this._write_back != null )
      eval( this._write_back );
    else
      Dom.get( this.id ).value = value;

    // execute the 'onchange' function if needed
    if( Dom.get( this.id ).onchange )
      Dom.get( this.id ).onchange();

    setTimeout( this.me+'.clear=1;'+this.me+'.hide()', 60 );
  }

  //------------------------------------------
  this.keep_open = function( )
  {
    this.clear = 0;
    setTimeout( this.me+'.clear=1', 40 );
  }

  //------------------------------------------
  this.hide = function( )
  {
    if( Dom.get( this.id+'_popup' ) )
    {
      if( this.clear && Dom.get( this.id+'_popup' ).innerHTML != '' )
      { 
        Dom.get( this.id+'_popup' ).innerHTML = '';
        hide( this.id+'_popup' );
        this.clear = 0;
      }
    }
  }

  //------------------------------------------
  this.show = function ( resp )
  {
    // print for debugging
    //var obj = Dom.get('tail_debug_text');
    //obj.innerHTML += "<br/>" + resp;

    this.resp_obj = eval('(' + resp + ')');

    // call custom show function
    if( this._show != null )
    {
      eval( this._show );
      this.keep_open( );
      return;
    }

    // print the responses
    Dom.get( this.id+'_popup' ).innerHTML = '';
    hide( this.id+'_popup' );

    if( this.resp_obj.values )
    {
      for( var i = 0; i < this.resp_obj.values.length; i++ )
      {
        var value = this.resp_obj.values[i];
        Dom.get( this.id+'_popup' ).innerHTML += '<a onclick="'+this.me+'.write_back(\''+addslashes( value.val )+'\');">'+value.val+'</a>';
      }
      show( this.id+'_popup' );
    }

    // ---
    this.keep_open( );
  }


  //-------------------------------------------
  this.request = function ( )
  {
    // prepare query string
    var queryString = "query=" + CORE_uri_encode( this.query );

    //queryString += "&object=" + escape( this.id );

    queryString += "&value=" + CORE_uri_encode( Dom.get( this.id ).value );

//Dom.goto( 'xhr_test2/'+queryString );

    // call custom query function
    if( this._query != null )
    {
      eval( 'queryString += ' + this._query );
    }

    this.xhr_handle = new Xhr( this.me+'.xhr_handle', this.location, this.me+'.show' );
    this.xhr_handle.request( queryString );
  
    // ======= Send HttpRequest =======
/*
    // branch for native XMLHttpRequest object
    if( window.XMLHttpRequest )
      this.xhr_handle = new XMLHttpRequest();
    // branch for IE/Windows ActiveX version
    else if( window.ActiveXObject )
      this.xhr_handle = new ActiveXObject( "Microsoft.XMLHTTP" );
    else
      return;

    eval( 'this.xhr_handle.onreadystatechange = function() {  '+this.me + '.response(); };' );
    this.xhr_handle.open( "POST", this.location, true );
    this.xhr_handle.setRequestHeader( "Content-Type", "application/x-www-form-urlencoded" );
    this.xhr_handle.send( queryString );
*/
  }
/*
  //-------------------------------------------
  this.response = function ( )
  {
    // only if req shows "complete" and only if "OK"
    if ( this.xhr_handle.readyState == 4 && this.xhr_handle.status == 200 )
    {
      // ======== Call form-specific response handling function
      if( this.xhr_handle.responseText )
        this.show( this.xhr_handle.responseText );
    } 

  }
*/

}