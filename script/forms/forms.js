
/*
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
*/

// validation function types
var V_REQUIRED = 1;
var V_REGEX = 2;
var V_DATE = 3;
var V_PHONE = 4;
var V_EMAIL = 5;
var V_UNIQUE = 6;

//=============================================
// Validation functions
// -----------------------------------
function validate_required( element )
{
  if( Dom.get( element ).value )
    return E_OK;
  else
    return E_ENTRY_REQUIRED;
}

// -----------------------------------
function validate_regex( element, regex )
{
  var str = Dom.get( element ).value;

  if( str == '' || eval( 'str.match( '+regex+' )' ) )
  {
    return E_OK;
  }
  else
    return E_INVALID_FORMAT;
}

// -----------------------------------
function validate_unique( element, field )
{
  var str = Dom.get( element ).value;

//  var q_str = "&not_in=" + CORE_uri_encode( my_org_list );

    return E_OK;
}

// return codes - 
//   0: E_OK
//   1: E_ENTRY_REQUIRED
//   2: E_INVALID_FORMAT
//   4: E_INVALID_DATE
function validate_date( date_in, required, yearRequired, date_obj )
{
  // TODO: year required not implemented
  if( !date_in )
  {
    if( required )
      return E_ENTRY_REQUIRED;
    else
      return E_OK;
  }
  
  var dateMatch = E_INVALID_FORMAT; // invalid format
  
  var date_regex = [];
  date_regex[0] = /^(0?[1-9]|1[012])[\-|* \/.](0?[1-9]|[12][0-9]|3[01])([\-|* \/.]((19|20)?\d\d))?$/;
  date_regex[1] = /^(0?[1-9]|[12][0-9]|3[01])[\-|* \/.](0?[1-9]|1[012])([\-|* \/.]((19|20)?\d\d))?$/;
  date_regex[2] = /^(((19|20)?\d\d)[\-|* \/.]?)?(0?[1-9]|1[012])[\-|* \/.]?(0?[1-9]|[12][0-9]|3[01])$/;

  var date_get = [];
  date_get[0] = function (bits) { return {"m":(bits[1] - 1), "d":bits[2], "y":bits[4]} };
  date_get[1] = function (bits) { return {"m":(bits[2] - 1), "d":bits[1], "y":bits[4]} };
  date_get[2] = function (bits) { return {"m":(bits[4] - 1), "d":bits[5], "y":bits[2]} };

  var date_handler; 
  date_handler = function (date_obj, date_in) { date_in.setFullYear( date_obj.y, date_obj.m, date_obj.d ) };
//  date_handler[1] = function (date_obj, date_in) { date_in.setFullYear( bits[4], (bits[2] - 1), bits[1] ) };
//  date_handler[2] = function (date_obj, date_in) { date_in.setFullYear( bits[2], (bits[4] - 1), bits[5] ) };

  // format -  international: 2, USA: 0, EUR: 1
  var order = [0,2,1];  
  if( g_date_format == 1 )
    order = [1,2,0];

  // loop through each regex
  for( var i = 0; ((i < 3) && dateMatch != E_OK); i++ )
  {
    // check if the regex matched
    bits = date_regex[ order[i] ].exec( date_in );
    if( bits )
    {
      // check if we have a valid date
      var c_1 = date_get[ order[i] ]( bits );
      var c_2 = new Date ( );
      
      if( !c_1.y )
        c_1.y = c_2.getFullYear();
      else if( c_1.y < 50 )
        c_1.y += 2000;
      else if( c_1.y < 100 )
        c_1.y += 1900;

      date_handler( c_1, c_2 );
      // only possible error at this point is that month has more days than allowed
      if( (c_2.getMonth() == c_1.m) && (c_2.getDate() == c_1.d) )
      {
        dateMatch = E_OK; // success
        //set the date in the object if one was passed in
        if( date_obj != undefined )
          date_handler( c_1, date_obj );
      }
      else
        dateMatch = E_INVALID_DATE; // invalid date
    }
  }

  return dateMatch;
}





function Validate( me, id, json_str )
{
  this.me = me;
  this.id = id;
  this.obj = json_str;

  this.confirm_cancel = true;
  this.back_link = 'home';
  this.cancel_text = 'Are you sure you want to leave this form without saving?';

  //----------------------------
  // do constructor tasks
  addLoadEvent( this.me+".create()" );


  //------------------------------------------
  this.create = function( )
  {
    // add event handlers
    if( !this.obj )
      return;

    for( var i = 0; i < this.obj.length; i++ )
    {
      for( var j = 0; j < this.obj[i].validate.length; j++ )
      {
        addEventHandler( 'onblur', this.obj[i].id, this.me+".check("+i+","+j+");" );
        addEventHandler( 'onchange', this.obj[i].id, this.me+".check("+i+","+j+");" );
        this.check( i, j );
      }
    }
  }

  //------------------------------------------
  this.check = function( i, j, init )
  {    
    var type = this.obj[i].validate[j].type;

    switch( type )
    {
      case( V_REQUIRED ):
        err_code = validate_required( this.obj[i].id )
        break;

      case( V_DATE ):
        err_code = validate_date( Dom.get( this.obj[i].id ).value )
        break;

      case( V_REGEX ):
        err_code = validate_regex( this.obj[i].id, this.obj[i].validate[j].regex );
        break;
    }

    this.handle_error( i, j, err_code, init );

    //return error code only if not a warning
    if( this.obj[i].validate[j].warn != 1 )
      return err_code;
    else
      return E_OK;
  }

  //------------------------------------------
  this.handle_error = function( i, j, err_code, init )
  {
    var warn = this.obj[i].validate[j].warn;

    if( err_code )
    {
      if( init || warn )
        this.show_error( i, err_code, warn ); 
    }
    else
      this.hide_error( i, warn );
  }

  //------------------------------------------
  this.show_error = function( i, err_code, warning )
  {
    var clas = 'error';
    if( warning == 1 )
      clas = 'warning';

    Dom.addClass( this.obj[i].id+'_div', clas );
    Dom.get( this.obj[i].id+'_error_popup' ).innerHTML = this.obj[i].msgs[ err_code ];
  }

  //------------------------------------------
  this.hide_error = function( i, warning )
  {
    var clas = 'error';
    if( warning )
      clas = 'warning';

    Dom.removeClass( this.obj[i].id+'_div', clas );
  }

  //------------------------------------------
  this.validate = function( )
  {
    if( !this.obj )
      Dom.get( this.id ).submit();

    var err_count = 0;
    var first_err;
    var err_found;


    for( var i = 0; i < this.obj.length; i++ )
    {
      for( var j = 0; j < this.obj[i].validate.length; j++ )
      {
        err_found = this.check( i, j, true );
        if( err_found )
        {
          if( !err_count )
            first_err = i;
          err_count++;
          break;
        }
      }
    }

    if( err_count )
    {
      Dom.get( this.obj[first_err].id ).focus();

      var err_text = 'was one error';
      if( err_count > 1 )
        err_text = 'were '+err_count+' errors';

      Dom.get( 'page_system_msg' ).innerHTML = '<div id="system_msg" class="system_msg failure">There was a problem saving the form. There '+err_text+' found on the page.</div>';
      clearTimeout( system_msg_timer );
      system_msg_timer =setTimeout( 'hide("system_msg")', 5000 );
    }

    if( !err_count )
      Dom.get( this.id ).submit();
  }

  //-------------------------------------------
  this.cancel = function( )
  {
    if( !this.confirm_cancel )
      Dom.goto( this.back_link );

    if( confirm( this.cancel_text ) )
      Dom.goto( this.back_link );
  }
}



/*
function handleError( elementId, label, errorObj )
{
  if( errorObj.code )
  {
    error_border( elementId, 1 );
    //if( !errorObj.flag )
    {
      if( errorObj.code == E_ENTRY_REQUIRED )
        errorObj.str += "Please enter a value for '" + label + "'.<br/>";
      else if( errorObj.code == E_INVALID_FORMAT )
        errorObj.str += "The value for '" + label + "' is improperly formatted.<br/>";
      else if( errorObj.code == E_SELECTION_REQUIRED )
        errorObj.str += "Please select a value for '" + label + "'.<br/>";
      else if( errorObj.code == E_INVALID_DATE )
        errorObj.str += "Please enter a valid date for '" + label + "'.<br/>";
      else if( errorObj.code == E_OUT_OF_RANGE )
        errorObj.str += "The value for '" + label + "' is out of range.<br/>";
      else if( errorObj.code == E_VALUE_NOT_UNIQUE )
        errorObj.str += "Sorry, that " + label + " is already in use. Please enter a different one.<br/>";
        
      //alert( errorObj.code + ' ' + elementId + ' ' + errorObj.str );
      
      if( !errorObj.flag )
        Dom.get( elementId ).focus();
    }
    errorObj.flag = true;
  }
  else error_border( elementId, 0 );
}


// return codes - 
//   0: success
//   1: no data entered
//   2: improper format
function validateTime( timeVal, required, isDuration )
{
  var regex;
  
  if( !timeVal )
  {
    if( required )
      return 1;
    else
      return false;
  }
  
  if( isDuration && isDuration != "0" )
    regex = /^((\d{1,3})(:([0-5]\d))?((')|[':]([0-5]\d))?)?((")|[".](\d*))?$/;
  else
    regex = /^(([0-1])?\d|2[0-3])(:([0-5]\d))?(:([0-5]\d))? *(([aA]|[pP]).?[mM].?)?$/;
    
  if( regex.test( timeVal ) )
    return false;
  else
    return 2;
}

// return codes - 
//   0: success
//   1: no data entered
//   2: improper format
function validateNumber( numVal, required, unsigned, decimals, dec_point, thousands_sep )
{
  var neg='', extra='', regex='';
    
  if( !numVal )
  {
    if( required )
      return 1;
    else
      return false;
  }
  
  if( !unsigned || unsigned == "0" )
    neg = '-?';
  if( decimals && decimals != "0" )
    extra = '([' + dec_point + '][\\d]*){0,1}';
  if( thousands_sep && thousands_sep != "0" )
    regex = new RegExp( '^\\s*(' + neg + '(((\\d{1,2}[' + thousands_sep + '])?(\\d{3}[' + thousands_sep + '])*?(\\d{3}))|(\\d*?))' + extra +')\\s*$' );
  else
    regex = new RegExp( '^\\s*(' + neg + '(\\d*)' + extra + ')\\s*$' );
  
  if( regex.test( numVal ) )
    return false;
  else
    return 2;
}


// return codes - 
//   0: success
//   3: required value not selected
function validateEnum( enumVal )
{
  if( !enumVal || enumVal == "0" )
    return 3;
  return false;
}

// return codes - 
//   0: success
//   1: no data entered
function validateString( strVal )
{
  if( !strVal )
    return 1;
  return false;
}
*/




function Select_multi( me, id )
{
  this.me = me;
  this.id = id;

  this.val_arr = [];
  this.val_str = '';

  this.clear = 0;
  this.visible = false;
  this.enabled = true;

  this.clear_all    = '0';
  this.default_text = '- any -';
  this.default_value= 0;
  this.item_text    = 'items';

  this.override_output = null;

  //----------------------------
  // do constructor tasks
  addLoadEvent( this.me+".create()" );

  //------------------------------------------
  this.create = function( )
  {
    //set default values
    this.val_str = Dom.get( this.id+'_value' ).value;

    if( this.val_str.length )
    {
      this.val_arr = this.val_str.split( '.' );
    }

    var node_list = Dom.get( this.id+'_select' ).childNodes;

    for( var i = 0; i < this.val_arr.length; i++ )
    {
      if( this.val_arr[i] != '' )
        Dom.addClass( this.id+'_'+this.val_arr[i], 'selected' );
    }
    this.update( null, 1 );

    addEventHandler( 'onchange', this.id+'_value', this.me+".reset()" );

    addClickEvent( "setTimeout( \""+this.me+".hide()\", 20 )" );

    addEventHandler( 'onclick', this.id+'_select', this.me+".keep_open();" );

    addEventHandler( 'onclick', this.id+'_input_outer', this.me+".show();" );

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
    if( this.clear )
    { 
      hide( this.id+'_select');
      this.clear = 0;
      this.visible = false;
    }
  }


  //------------------------------------------
  this.show = function(  )
  {
    if (!this.enabled){
      return
    }

    if( !this.visible )
    {
      show( this.id+'_select' );
      this.keep_open( );
      this.visible = true;
    }
    else
    {
      this.clear = 1;
      this.hide( );
    }
  }

  //------------------------------------------
  this.update = function ( value, init )
  {
    if( !init )
    {
      if( this.default_value )
        this.val_arr.removeByValue( this.default_value );

      eval( 'var regex = /\\b'+value+'\\b/' );

      if( this.val_str.match( regex ) )
      {
        this.val_arr.removeByValue( value );
        Dom.removeClass( this.id+'_'+value, 'selected' );
      }
      else
      {
        this.val_arr.push( value );
        Dom.addClass( this.id+'_'+value, 'selected' );
      }

    }

    // display the value in the input box
    this.val_str = this.val_arr.join( '.' );

    if( !this.override_output )
    {
      var display = this.default_text;

      var len = this.val_arr.length;
      if( this.val_str == this.default_value.toString() )
        len--;

      if( len == 1 )
        display = Dom.get( this.id+'_'+this.val_arr[0] ).innerHTML;
      else if( len > 1 )
        display = this.val_arr.length+' '+this.item_text+' selected';

      Dom.get( this.id+'_input' ).innerHTML = display;
    }
    else
      Dom.get( this.id+'_input' ).innerHTML = this.override_output;

    // reset the checkbox for the 'any' value
    if( (!this.val_arr.length || this.val_str == this.default_value.toString() ) && this.clear_all != '' )
      Dom.addClass( this.id+'_'+this.clear_all, 'selected' );
    else if( this.clear_all != '' )
      Dom.removeClass( this.id+'_'+this.clear_all, 'selected' );
    
    Dom.get( this.id+'_value' ).value = this.val_str;
  }

  //------------------------------------------
  this.reset = function ( )
  {
    var node_list = Dom.get( this.id+'_select' ).childNodes;

    for( var i = 0; i < node_list.length; i++ )
    {
      if( node_list[i].id  )
        Dom.removeClass( node_list[i], 'selected' );
    }

    Dom.addClass( this.id+'_'+this.clear_all, 'selected' );

    if( this.default_value )
      this.val_arr = [this.default_value];
    else
      this.val_arr = [];

    this.update( null, 1 );

    this.clear = 1;
    this.hide( );
  }

// end of class
};






//=============================================
// Add/Remove array form elements
// -----------------------------------
function Form_array( me, id, obj_name )
{
  this.id = id;
  this.me = me;
  this.obj_name = obj_name;

  this.count = 0;

  //----------------------------
  // do constructor tasks
  addLoadEvent( this.me+".create()" );

  //------------------------------------------
  this.create = function( )
  {
    // create frame for results
    //popup_frame = Dom.create( 'div', this.id + '_popup', '' );
    //popup_frame.className = 'xhr_search_result';
    //Dom.insertBefore( popup_frame, this.id );
    //this.count = 0;
  }

  // -----------------------------------
  this.remove = function( my_id, obj_id )
  {
    if( obj_id )
    {
      var text = ' <input type="hidden" name="deleted_'+this.obj_name+'s[]" value="'+obj_id+'" />';
  
      var node = Dom.create( 'div', '', text );
      Dom.add( node, this.id );
    }
    
    Dom.remove( 'form_' + this.obj_name + my_id );
  }

  // -----------------------------------
  this.add = function( value, initial )
  {
    var text;
    eval( 'text = addFormText_'+this.obj_name+'( this.me, this.count, value, initial );' );
  
    var node = Dom.create( 'div', 'form_' + this.obj_name + this.count, text );

    Dom.add( node, this.id );

    this.count++;
  }
}



function reset_form( form_name )
{
  var my_form = Dom.get( form_name );

  for( var i = 0; i < my_form.elements.length; i++ )
  {
    switch( my_form.elements[i].type )
    {
      
      case( 'select-one' ):
        my_form.elements[i].options[0].selected = true;
        break;
      case( 'select-multiple' ):
        for( var j = 0; j < my_form.elements[i].options.length; j++ )
          my_form.elements[i].options[0].selected = false;
        break;
      case( 'text' ):
      case( 'textarea' ):
      case( 'password' ):
      case( 'file' ):
        my_form.elements[i].value = '';
        if( my_form.elements[i].onchange )
           my_form.elements[i].onchange();
        break;
      case( 'checkbox' ):
      case( 'radio' ):
        my_form.elements[i].checked = false;
        break;
      case( 'hidden' ):
    }
    
  }
}




//=============================================
// Multiple select box functions
// -----------------------------------
function moveOption( from, to, storeElement, add )
{
  var elementTo = Dom.get( to );
  var elementFrom = Dom.get( from );

  for( i = elementFrom.options.length-1; i >= 0; i-- )
  { 
    if( elementFrom[i].selected )
    {
      if( add )
        addOption( storeElement, elementFrom[i].value );
      else
        removeOption( storeElement, elementFrom[i].value );

      insertOption( elementTo, elementFrom[i].text, elementFrom[i].value )
      elementFrom.remove( i );
    }
  }
}

// -----------------------------------
function moveAllOptions( from, to, storeElement, add )
{
  var elementTo = Dom.get( to );
  var elementFrom = Dom.get( from );

  for( i = elementFrom.options.length-1; i >= 0; i-- )
  { 
    if( add )
      addOption( storeElement, elementFrom[i].value );
    else
      removeOption( storeElement, elementFrom[i].value );

    insertOption( elementTo, elementFrom[i].text, elementFrom[i].value )
    elementFrom.remove( i );
  }
}

// -----------------------------------
function findInsertPoint( obj, text )
{
  var selectObj = Dom.get( obj );
  var insertPoint = null;
  var value1, value2;
 
  for( var i = selectObj.options.length-1; i >= 0 ; i -- )
  {
    value1 = text;
    value2 = selectObj.options[i].text;

    if( value1.toLowerCase() < value2.toLowerCase() )
      insertPoint = selectObj.options[i];
    else
      return insertPoint;
  }

  return insertPoint;
}

// -----------------------------------
function insertOption( obj, text, value )
{
  var insertPoint = findInsertPoint( obj, text );

  var node = Dom.create( 'option', '', text );
  node.setAttribute( 'value', value );

  if( !insertPoint )
    Dom.get( obj ).appendChild( node );
  else
    Dom.get( obj ).insertBefore( node, insertPoint );
}

// -----------------------------------
function addOption( storeElement, value )
{
  element = Dom.get( storeElement );
//  var text = 
//    ' <input type="hidden" name="'+storeElement+'[]" value="'+value+'" />';
  
  var node = Dom.create( 'input', storeElement+value );
  node.setAttribute( 'type', 'hidden' );
  node.setAttribute( 'name', storeElement+'[]' );
  node.setAttribute( 'value', value );

  Dom.add( node, element );
}

// -----------------------------------
function removeOption( storeElement, value )
{
  Dom.remove( storeElement+value );
}
