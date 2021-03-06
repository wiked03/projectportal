<?
// -------------------------------------------
//$DEBUG = true;
if( $DEBUG )
  print_r( $_POST );
// -------------------------------------------


//===========================================================================================
// Inputs
//-------------------------------------------------------------------------------------------
//  GET[ c ]            Class name/Class file name
//  GET[ ret ]          0 = No return on success, 1 = Return submitted values in 'disp'
//  POST[ form_name ]   Form id
//  POST[ form_action ] 0 = write to db, 1 = get from db
//  POST[ form_params ] paramaters from request
//  POST                values from form

//===========================================================================================
// Outputs
//-------------------------------------------------------------------------------------------
//  JSON[ form ]        Form id
//  JSON[ db ]          0 = unsuccessful mysql db operation, otherwise db = mysql_insert_id
//  JSON[ err ]         array of field_id, label, error_code
//  JSON[ disp ]        array of field_id, data
//  JSON[ html ]        html output
//  JSON[ target ]      destination div for html
//  JSON[ action ]      html action ( 0 = append, 1 = replace )



//===========================================================================================
// Form Handler response Class
//-------------------------------------------------------------------------------------------
class HttpReqResponse
{
  var $form;     // the name of the form
  var $db;       // tells if db operation was successful, 1 for yes, 0 for failure
  var $db_err;   // error code of db operation
  var $act;      // action to preform, 0 = write, 1 = read
  
  var $err;      // array of field_id, label, error_code
  var $disp;     // array of field_id, data
  
  var $html;     
  var $target;
  var $target2;   
  var $action;   
  
  //var $more;     // array of key/value for each form-specific value to pass back
  //var $more_txt; // text-only 'more' field
  
  // ----------------
  function add_more ( $key, $value )
  {
    $this->more[$key] = $value;
  }
  
  // ----------------
  function print_response ( )
  {
    global $JSON;
    $ret_val = '{"form":"'.$this->form.'","db":'.$JSON->encode( $this->db );

    if( isset( $this->db_err ) )
      $ret_val .= ',"db_err":'.$this->db_err;

    if( isset( $this->act ) )
      $ret_val .= ',"act":'.$this->act;
    
    if( isset( $this->err ) )
      $ret_val .= ',"err":'.$JSON->encode( $this->err );

    if( isset( $this->disp ) )
      $ret_val .= ',"disp":'.$JSON->encode( $this->disp );

    if( isset( $this->html ) )
      $ret_val .= ',"html":"'.addcslashes( $this->html, '"\\' ).'","target":'.$JSON->encode($this->target).
                  ',"target2":'.$JSON->encode($this->target2).',"action":'.$JSON->encode( $this->action );

    //if( isset( $this->more ) )
      //foreach( $this->more as $key => $value )
        //$ret_val .= ',"'.$key.'":"'.addslashes( $value ).'"';
    
    //if( isset( $this->more_txt ) )
      //$ret_val .= ",".$this->more_txt;
    
    $ret_val.= '}';
    
    return $ret_val;
  }
}
//===========================================================================================


$response = new HttpReqResponse();
$response->form = $_POST['form_name'];
$response->db = 0;
$response->act = $_POST['form_action'];

if( !( include_once($_GET[c].'.php') ) )
{
  echo $response->print_response();
  exit( 0 );
}

eval( '$my_class = new '.$_GET[c].'("",X,"'.$_POST['form_name'].'" );' );

//===========================================================================================
// Form Handler:
//-------------------------------------------------------------------------------------------
//  Step 1: Validate
//-------------------------------------------------------------------------------------------
if( !$response->act )
{
  $my_class->set_values( $_POST, F_HTM, $response->form );

  // validate the form
  $errs_found = $my_class->validate( );
  
  if( $errs_found )
    $response->err = $errs_found;
  
  /*foreach( $my_class->objs as $key => $obj )
  {
    if( !$obj->params['no_validate'] && !$obj->params['no_db_field'] && !$obj->skip_update )
      $err_code = $obj->validate( );
  
    if( $err_code != E_OK )
      $response->err[] = array( $key, $obj->label, $err_code );
  }*/
}

//-------------------------------------------------------------------------------------------
//  Step 2: Submit to Database
//-------------------------------------------------------------------------------------------
if( !isset( $response->err ) && !$response->act )
{
  $response->db = $my_class->write_back( true );
  if( !$response->db )
    $response->db_err = mysql_errno();
}
elseif( $response->act == 1 )
{
  // if action == 1, set the key of the class and perform a get from the db
  if( method_exists( $my_class, 'set_id_from_params' ) )
    $response->db = $my_class->set_id_from_params( $_POST['form_params'] );
  else
  {
    $my_class->id_obj->set( $_POST['form_params'] );
    $response->db = $my_class->get_values( );
  }
}

//-------------------------------------------------------------------------------------------
//  Step 3: Prepare response to application
//-------------------------------------------------------------------------------------------
// return JSON formatted response
if( $response->db && ($_GET[ret] == 1) )
{
  foreach( $my_class->objs as $key => $obj )
    $response->disp[] = array( $key, $obj->get( F_HTM ) );
  
  $response->db = $my_class->id_obj->get( );
}
elseif( $response->db && ($_GET[ret] > 1) )
{
  $my_class->print_response( $_GET[ret], $response );
}
elseif( $response->act )
{
  foreach( $my_class->objs as $key => $obj )
    $response->disp[] = array( $key, $obj->get( F_FORM ) );
  
  $response->disp[] = array( $my_class->id_obj->name, $my_class->id_obj->get( F_FORM ) );
}


echo $response->print_response();


?>
