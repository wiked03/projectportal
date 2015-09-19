<?

class CORE_Form
{
  var $data   = array();

  var $tags   = array();
  var $fields = array();
  var $types  = array();
  var $labels = array();
  var $index  = array();
  var $option_list = array();
  var $settings = array();


  var $validate = array();
  var $errors = array();
  var $error_msgs = array();

  var $name;
  var $action;
  var $field_count = 0;

  //--------------------------------
  function CORE_Form( $name=NULL, $action=NULL )
  {
    $this->name = $name;
    $this->action = $action;
  }

  // ----------------------------------------------------------------------------------------
  function get( $item, $format=F_HTM )
  {
    if( preg_match('!([\w]+)(\[.+\])!', $item, $matches) )
      eval('$data = $this->data['.$matches[1].']'.$matches[2].';');
    else
      $data = $this->data[$item];

    return CORE_encode( $data, $format );
  }

  // ----------------------------------------------------------------------------------------
  function set( $item, $value, $format=F_HTM )
  {
    $data = CORE_decode( $value, $format );

    if( preg_match('!([\w]+)(\[.+\])!', $item, $matches) )
      eval('$this->data['.$matches[1].']'.$matches[2].' = $data;');
    else
      $this->data[$item] = $data;
  }

  // ----------------------------------------------------------------------------------------
  function set_data ( $data, $format=F_PHP )
  {
    $data = CORE_decode( $data, $format );
    $this->data = $data;
  }

  //--------------------------------
  function set_tag( $item, $key, $val, $override=false )
  {
    if( $override )
      $this->tags[$item][$key][0] = $val;
    else
      $this->tags[$item][$key][] = $val;
  }

  //--------------------------------
  function set_error( $item, $error_code )
  {
    $this->errors[$item] = $error_code;
  }

  //--------------------------------
  function set_label( $item, $value, $format=F_HTM )
  {
    $this->labels[$item] = CORE_encode( $value, $format );
  }

  //--------------------------------
  function set_tags( $item, $tags )
  {
    if( is_array( $tags ) )
    {
      foreach( $tags as $key => $val )
        $this->set_tag( $item, $key, $val );
    }

  }

  //--------------------------------
  function print_tag( $item, $value=NULL )
  {
    if( isset( $value ) )
      $value = $this->get( $item, F_PHP );

    $ret_val = make_tag( $this->tags[$item], $value );

    return $ret_val;
  }

  //--------------------------------
  function add_input( $item, $label=NULL, $tags=NULL, $type='text' )
  {
    $this->fields[$this->field_count++] = $item;
    $this->labels[$item] = $label;
    $this->types[$item] = $type;

    $this->set_tags( $item, $tags );

  }

  //--------------------------------
  function add_textarea( $item, $label=NULL, $size=4, $tags=NULL )
  {
    $this->fields[$this->field_count++] = $item;
    $this->labels[$item] = $label;
    $this->types[$item] = 'textarea';

    $tags['rows'] = $size;

    $this->set_tags( $item, $tags );

  }

  //--------------------------------
  function add_select( $item, $list, $label=NULL, $settings=array(), $tags=NULL )
  {
    $this->fields[$this->field_count++] = $item;
    $this->labels[$item] = $label;

    $this->types[$item] = 'select';

    $this->option_list[$item] = $list;

    $this->settings[$item] = $settings;
    $this->set_tags( $item, $tags );

    $this->field_count++;
  }

  //-------------------------------
  function update_select( $item, $list=NULL, $initial=NULL )
  {
    if( !$list )
      $list = $this->option_list[$item];

    // append 'select one' text option to array of choices
    if( is_array( $initial ) )
      $list = $initial + $list;
    elseif( $initial )
      $list = array(0=>$initial) + $list;

    $this->option_list[$item] = $list;
  }

  //-------------------------------
  function print_item( $item, $is_form=true, $class=NULL,
                         $override_value=NULL, $override_class=false )
  {
    if( !isset( $class ) )
      $class = $item;
    if( $this->errors[$item] )
      $class .= ' error';

   if ($override_class) {
      $ret_val = '
     <div class="'.$class.'" id="'.$this->name.'-'.$item.'_div">';
   } else {
      $ret_val = '
     <div class="form_item '.$class.'" id="'.$this->name.'-'.$item.'_div">';
   }
    
    $ret_val .= $this->print_label( $item, $is_form );

    if( !$is_form )
      $type = 'span';
    else
      $type = $this->types[$item];

    switch( $type )
    {
      case( 'span' ):
        $ret_val .= $this->print_span( $item, $override_value );
        break;

      case( 'text' ):
      case( 'hidden' ):
        $ret_val .= $this->print_input( $item );
        if( $this->validate[$item] )
          $ret_val .= $this->print_error_div( $item );
        break;

      case( 'date' ):
        $ret_val .= $this->print_date( $item );
        if( $this->validate[$item] )
          $ret_val .= $this->print_error_div( $item );
        break;

      case( 'search' ):
        $ret_val .= $this->print_search( $item );
        if( $this->validate[$item] )
          $ret_val .= $this->print_error_div( $item );
        break;

      case( 'select' ):
      case( 'select_multi' ):
        $ret_val .= $this->print_select( $item );
        break;
      
      case( 'select_multi_popup' ):
      	$ret_val .= $this->print_select_popup( $item );
        break;
        
      case( 'textarea' ):
        $ret_val .= $this->print_textarea( $item );
        break;
    }

    $ret_val .= SP_DIV.'</div>';

    return $ret_val;
  }

  //====================================================================================================
  function print_label( $item, $for=true )
  {
    if( $for )
      $for = ' for="'.$this->name.'-'.$item.'"';
    else
      $for = '';

    if( $this->labels[$item] )
      $ret_val .= '<label'.$for.'>'.$this->labels[$item].':</label>';

    return $ret_val;
  }

  //--------------------------------
  function print_span( $item, $override_value=NULL )
  {
    $value = $this->get( $item, F_HTM );
    if( $this->option_list[$item] && $value != '' )
    {
      $vals = explode( '.', $value );
      foreach( $vals as $val )
        $values[] = $this->option_list[$item][$val];
      $value = implode( ', ', $values );
    }

    if( $this->types[$item] == 'date' )
      $value = CORE_date( $this->get( $item ), F_DATE_HTM );

    if( isset( $override_value ) )
      $value = $override_value;

    $this->set_tag( $item, 'class', 'value' );

    $ret_val .= '<span class="value" '.$this->print_tag( $item ).'>'.$value.'</span>';

    return $ret_val;
  }

  //--------------------------------
  function print_error_div( $item )
  {

    if( $this->errors[$item] )
      $error_text = $this->error_msgs[$item][$this->errors[$item]];

    $ret_val .= '<div class="error_div" id="'.$this->name.'-'.$item.'_error" onmouseover="show( \''.$this->name.'-'.$item.'_error_popup\' )" onmouseout="hide(\''.$this->name.'-'.$item.'_error_popup\')">
                  <div id="'.$this->name.'-'.$item.'_error_popup" class="popup">'.$error_text.SP_DIV.'</div></div>';

    return $ret_val;

  }

  //--------------------------------
  function print_input( $item )
  {
    $ret_val = '<input type="'.$this->types[$item].'" name="'.$item.'" id="'.$this->name.'-'.$item.'" '.$this->print_tag( $item, 1 ).' />';

    return $ret_val;
  }

  //--------------------------------
  function print_date( $item )
  {
    $ret_val .= '<input type="text" name="'.$item.'" id="'.$this->name.'-'.$item.'" '.$this->print_tag( $item ).
                      ' value="'.CORE_date( $this->get( $item ), F_DATE_HTM ).'" size="12" maxlength="10" />'.
                ' <div class="cal_popup_container">'.
                '  <div class="cal_popup mini_cal" id="cal_'.$this->name.'-'.$item.'"></div>'.
                '  <a id="cal_link_'.$this->name.'-'.$item.'" class="cal_popup_link"><img src="img/icons/calendar.png" width=16 height=16 /></a>'.
                ' </div>';

    return $ret_val;
  }

  //-------------------------------
  function print_textarea( $item )
  {
    $ret_val = '<textarea name="'.$item.'" id="'.$this->name.'-'.$item.'" '.$this->print_tag( $item ).'>'.$this->get( $item, F_HTM ).'</textarea>';

    return $ret_val;
  }

  //--------------------------------
  function print_search( $item )
  {
    $ret_val = '<div class="xhr_search_container">'.
               ' <input autocomplete="off" type="text" name="'.$item.'" id="'.$this->name.'-'.$item.'" '.$this->print_tag( $item, 1 ).' />'.
               '</div>';

    return $ret_val;
  }

  //----------------------------------------
  function print_select( $item )
  {
    $id = $this->name.'-'.$item;
    $name = $item;

    // append 'select one' text option to array of choices
    if( $this->settings[$item]['init'] )
      $list = array( 0 => $this->settings[$item]['init'] ) + $this->option_list[$item];
    else
      $list = $this->option_list[$item];

    if( !$this->settings[$item]['multi'] )
    {
      $ret_val = '<select id="'.$id.'" name="'.$name.'" '.$this->print_tag( $item ).'>'.n;
      foreach( $list as $key => $val )
      {
        $ret_val .= '<option value="'.$key.'"'.( $this->get( $item, F_PHP ) == $key ? ' selected' : '' ).'>'.$val.'</option>';
      }
      $ret_val .= '</select>'.n;
    }
    else
    {
      $select_obj = $this->settings[$item]['me'];
      $reset_id   = $this->settings[$item]['reset_id'];
      $reset_str  = $this->settings[$item]['reset'];

      $override = array( );
      if( $this->settings[$item]['override'] )
        $override = $this->settings[$item]['override'];

      $ret_val = '<div class="custom_select" id="'.$id.'"><input type="text" '.
                     ' name="'.$name.'" id="'.$id.'_value" value="'.$this->get( $item ).'" />
         <div class="input" id="'.$id.'_input_outer"><div class="inner"><a href="javascript:void(0)" id="'.$id.'_input">&nbsp;</a></div></div><div class="select'.(count($list)>12 ? ' scroll' : '').'" id="'.$id.'_select">';

      if( isset( $reset_id ) )
        $ret_val .= '<div class="option" id="'.$id.'_'.$reset_id.'"'.
                        ' onclick="'.$select_obj.'.reset( )"><span>'.$reset_str.'</span></div>';

      if( $list )
      {
        foreach( $list as $key => $val )
        {
          if( !isset( $reset_id ) || $key !== $reset_id )
          {
            if( $override[$key] )
              $ret_val .= '<div class="option override" id="'.$id.'_'.$key.'"><span>'.$val.'</span></div>';
            else
              $ret_val .= '<div class="option" id="'.$id.'_'.$key.'" onclick="'.$select_obj.'.update( \''.$key.'\' )"><span>'.$val.'</span></div>';
          }
        }
      }
        else $ret_val .= '<div class="option override" id="'.$id.'_0">None</div>';
      $ret_val .= '</div></div>';

    }

    return $ret_val;
  }
  
  //----------------------------------------
  function print_select_popup( $item )
  {
  	$id = $this->name.'-'.$item;
  	$name = $item;
  
  	// append 'select one' text option to array of choices
  	if( $this->settings[$item]['init'] )
  		$list = array( 0 => $this->settings[$item]['init'] ) + $this->option_list[$item];
  	else
  		$list = $this->option_list[$item];
  
  	if( !$this->settings[$item]['multi'] )
  	{
  		$ret_val = '<select id="'.$id.'" name="'.$name.'" '.$this->print_tag( $item ).'>'.n;
  		foreach( $list as $key => $val )
  		{
  			$ret_val .= '<option value="'.$key.'"'.( $this->get( $item, F_PHP ) == $key ? ' selected' : '' ).'>'.$val.'</option>';
  		}
  		$ret_val .= '</select>'.n;
  	}
  	else
  	{
  		$select_obj = $this->settings[$item]['me'];
  		$reset_id   = $this->settings[$item]['reset_id'];
  		$reset_str  = $this->settings[$item]['reset'];
  
  		$override = array( );
  		if( $this->settings[$item]['override'] )
  			$override = $this->settings[$item]['override'];
  
  		$ret_val = '<div class="custom_select_popup" id="'.$id.'"><input type="text" '.
  				   ' name="'.$name.'" id="'.$id.'_value" value="'.$this->get( $item ).'" />
         			<div class="input" id="'.$id.'_input_outer"><div class="inner"><a href="javascript:void(0)" id="'.$id.'_input">&nbsp;</a></div></div>
         				<div style="position:fixed; top:50%; left:50%; margin-top: -180px; margin-left: -160px;" class="select'.(count($list)>12 ? ' scroll' : '').'" id="'.$id.'_select">';
  
  		if( isset( $reset_id ) )
  			$ret_val .= '<div class="option" id="'.$id.'_'.$reset_id.'"'.
  			' onclick="'.$select_obj.'.reset( )"><span>'.$reset_str.'</span></div>';
  
  		$i = 0;
  		if( $list )
  		{
  			foreach( $list as $key => $val )
  			{
  				if( !isset( $reset_id ) || $key !== $reset_id )
  				{
  					if ($i % 2) {
  						$align_class = 'custom_select_popup_left';
  					} else {
  						$align_class = 'custom_select_popup_right';
  					}
  						
  					if( $override[$key] )
  						$ret_val .= '<div class="option '.$align_class.' override" id="'.$id.'_'.$key.'"><span>'.$val.'</span></div>';
  					else
  						$ret_val .= '<div class="option '.$align_class.'" id="'.$id.'_'.$key.'" onclick="'.$select_obj.'.update( \''.$key.'\' )"><span>'.$val.'</span></div>';
  					$i = $i + 1;
  				}
  			}
  		}
  		else $ret_val .= '<div class="option override" id="'.$id.'_0">None</div>';
  		$ret_val .= '<div style="margin-top:25px; margin-left:140px;"><a href="javascript:hideSelColumns()">Done</a></div></div>
  				</div>';
  	}
  
  	return $ret_val;
  }

  //--------------------------------
  function is_checked( $index, $data )
  {
    if( !is_array( $data ) )
      $data = explode( ',', $data );

    if( in_array( $index, $data ) && $index )
      return ' checked';

    return '';
  }


  //====================================================================================================
  // validation functions
  function add_validation( $item, $type, $warn=0, $options=NULL )
  {
    switch( $type )
    {
      case( V_REQUIRED ):
        $this->create_error_msg( $item, E_ENTRY_REQUIRED );
        $this->validate[$item][] = array( "type"=>V_REQUIRED, "warn"=>$warn );
        break;

      case( V_REGEX ):
        $this->create_error_msg( $item, E_INVALID_FORMAT );
        $this->validate[$item][] = array( "type"=>V_REGEX, "warn"=>$warn, "regex"=>$options );
        break;

      case( V_DATE ):
        $this->create_error_msg( $item, E_INVALID_DATE );
        $this->create_error_msg( $item, E_INVALID_FORMAT );
        $this->validate[$item][] = array( "type"=>V_DATE, "warn"=>$warn );
        break;
        
    }

  }

  //--------------------------------
  function create_error_msg( $item, $code, $msg=NULL )
  {
    global $LANG;

    if( !isset( $msg ) )
      $this->error_msgs[$item][$code] = preg_replace( '/\%NAME\%/', $this->labels[$item], $LANG['errors'][$code] );
    else
      $this->error_msgs[$item][$code] = preg_replace( '/\%NAME\%/', $this->labels[$item], $msg );
  }

  //--------------------------------
  function get_validation_json( )
  {
    foreach( $this->validate as $item => $arr )
      $ret_val[] = array( 'id'=>$this->name.'-'.$item, 'validate'=>$this->validate[$item], 'msgs'=>$this->error_msgs[$item] );

    return json_encode( $ret_val );
  }
}
