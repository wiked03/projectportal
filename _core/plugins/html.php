<?

// make nice external links for google analytics
function ext_link( $site, $g_analytics="" )
{
  global $PAGE, $PROGRAM;
  
  if( isset( $PAGE['ext_sites'][$site] ) )
  {
    $link = $PAGE['ext_sites'][$site]; 
    $g_analytics = $site;
  }
  else
  { $link = $site;
  }

  $ret_val = 'href="'.$link.'" target="_blank"';
  
  if( $PROGRAM['g_analytics'] )
  {  
    if( substr( $g_analytics, 0, 10 ) != '/outgoing/' )
      $g_analytics = '/outgoing/'.$g_analytics;

    $ret_val .= ' onClick="javascript:pageTracker._trackPageview(\''.$g_analytics.'\');"';
  }
  
  return $ret_val;
}



//-------------------------------------------------------------------------------------------
function htm_img ($src, $height=NULL, $width=NULL, $class=NULL, $style=NULL, $alt="")
{
  if( $height )
    $height = ' height="'.$height.'"';
  if( $width )
    $width = ' width="'.$width.'"';
  if( $class )
    $class = ' class="'.$class.'"';
  if( $style )
    $style = ' style="'.$style.'"';
    
  return sprintf( '<img src="%s"%s%s%s%s alt="%s" />',
    $src, $height, $width, $class, $style, $alt );
}

//-------------------------------------------------------------------------------------------
function htm_link ( $dest, $text, $class=NULL, $style=NULL, $target=NULL, $extra=NULL )
{
  if( $class )
    $class = ' class="'.$class.'"';
  if( $style )
    $style = ' style="'.$style.'"';
  if( $target ) 
    $target = ' target="'.$target.'"';
  $dest = eregi_replace( " ", "%20", $dest );
  
  return sprintf( '<a href="%s"%s%s%s %s title="">%s</a>',
    $dest, $target, $class, $style, $extra, $text );
}

//-------------------------------------------------------------------------------------------
function htm_label( $text, $for=NULL, $json_fields=NULL )
{
  global $JSON;
  
  // use JSON to parse out additional fields
  if( isset( $json_fields ) )
  {
    $j_values = $JSON->decode( $json_fields );
    foreach( $j_values as $key => $val )
    {
      if( $key == 'bold' && $val )
        $text = '<b>'.$text.':</b>';
      if( $key == 'required' && $val )
        $text = '<span class="required">*</span>'.$text;
      elseif( in_array( $key, array('style', 'class') ) )
        $extra_fields .= ' '.$key.'="'.$val.'"';
    }
  }
  
  if( $for ) 
    $for = ' for="'.$for.'"';
  
  return sprintf( '<label %s%s>%s</label>',
    $for, $extra_fields, $text );
}

//-------------------------------------------------------------------------------------------
function htm_input( $type, $name, $value=NULL, $json_fields=NULL )
{
  global $JSON;
  
  if( $value )
    $value = ' value="'.$value.'"';

  // use JSON to parse out additional fields
  if( isset( $json_fields ) )
  {
    $j_values = $JSON->decode( $json_fields );
    foreach( $j_values as $key => $val )
    {
      if( $key == 'cols' ) $key = 'size';
        
      if( in_array( $key, array('size', 'maxlength', 'accesskey', 'tabindex', 
                                'style', 'class', 'src', 'alt', 'accept',
                                'onfocus', 'onblur', 'onselect', 'onchange', 'onclick',
                                'ondblclick', 'onmousedown', 'onmouseup', 'onmouseover',
                                'onmousemove', 'onmouseout', 'onkeypress', 'onkeydown',
                                'onkeyup') ) )
      {
        if( isset( $val ) )
          $extra_fields .= ' '.$key.'="'.$val.'"';
      }
      elseif( in_array( $key, array('checked', 'disabled', 'readonly') ) )
      {
        if( $val )
          $extra_fields .= ' '.$key;
      }
    }
  }
  
  return sprintf( '<input type="%s" name="%s" id="%s"%s%s />',
    $type, $name, $name, $value, $extra_fields);
}

//-------------------------------------------------------------------------------------------
function htm_select( $name, $options, $sel_idx=NULL, $json_fields=NULL )
{
  global $JSON;
  
  // use JSON to parse out additional fields
  if( isset( $json_fields ) )
  {
    $j_values = $JSON->decode( $json_fields );
    foreach( $j_values as $key => $val )
    {
      if( in_array( $key, array('size', 'accesskey', 'tabindex', 'style', 'class',
                                'onfocus', 'onblur', 'onchange') ) )
      {
        if( isset( $val ) )
          $extra_fields .= ' '.$key.'="'.$val.'"';
      }
      elseif( in_array( $key, array('multiple', 'disabled') ) )
      {
        if( $val )
          $extra_fields .= ' '.$key;
      }
    }
  }
  
  $ret_val = sprintf( '<select name="%s" id="%s"%s>',
    $name, $name, $extra_fields);
  
  if( gettype($options) == 'string' )
    $options = $JSON->decode( $options );
  foreach( $options as $key => $val )
  {
    if( $sel_idx == $key )
      $selected = " selected";
    else
      $selected = "";
    if( !isset( $val ) )
      $val = $key;
      
      $ret_val .= sprintf( '<option value="%s"%s>%s</option>',
        $key, $selected, $val );
  }
    
    
  $ret_val .= '</select>';
  
  return $ret_val;
}

//-------------------------------------------------------------------------------------------
function htm_textarea( $name, $value=NULL, $json_fields=NULL )
{
  global $JSON;
  
  $extra_fields = NULL;
  // use JSON to parse out additional fields
  if( isset( $json_fields ) )
  {
    $j_values = $JSON->decode( $json_fields );
    foreach( $j_values as $key => $val )
    {
      if( in_array( $key, array('rows', 'cols', 'accesskey', 'tabindex', 
                                'style', 'class',
                                'onfocus', 'onblur', 'onselect', 'onchange', 'onclick',
                                'ondblclick', 'onmousedown', 'onmouseup', 'onmouseover',
                                'onmousemove', 'onmouseout', 'onkeypress', 'onkeydown',
                                'onkeyup') ) )
      {
        if( isset( $val ) )
          $extra_fields .= ' '.$key.'="'.$val.'"';
      }
      elseif( in_array( $key, array('disabled', 'readonly') ) )
      {
        if( $val )
          $extra_fields .= ' '.$key;
      }
    }
  }
  
  return sprintf( '<textarea name="%s" id="%s"%s>%s</textarea>',
    $name, $name, $extra_fields, $value);
}

//-------------------------------------------------------------------------------------------
function htm_checkbox( $type, $name, $value, $label=NULL, $json_fields=NULL )
{
  global $JSON;
  
  // type = checkbox or option
  $id = $name.$value;
  $value = ' value="'.$value.'"';
  if( $label )
    $label = '<label for="'.$id.'">'.$label.'</label>';

  // use JSON to parse out additional fields
  if( isset( $json_fields ) )
  {
    $j_values = $JSON->decode( $json_fields );
    foreach( $j_values as $key => $val )
    {
      if( $key == 'cols' ) $key = 'size';
        
      if( in_array( $key, array('size', 'maxlength', 'accesskey', 'tabindex', 
                                'style', 'class', 'src', 'alt', 'accept',
                                'onfocus', 'onblur', 'onselect', 'onchange', 'onclick',
                                'ondblclick', 'onmousedown', 'onmouseup', 'onmouseover',
                                'onmousemove', 'onmouseout', 'onkeypress', 'onkeydown',
                                'onkeyup') ) )
      {
        if( isset( $val ) )
          $extra_fields .= ' '.$key.'="'.$val.'"';
      }
      elseif( in_array( $key, array('checked', 'disabled', 'readonly') ) )
      {
        if( $val )
          $extra_fields .= ' '.$key;
      }
    }
  }
  
  return sprintf( '<p class="checkbox"><input type="%s" name="%s" id="%s"%s%s>%s</p>',
    $type, $name, $id, $value, $extra_fields, $label);
}

//-------------------------------------------------------------------------------------------
function format_query_str( $array_in, $q_mark=false )
{
  foreach( $array_in as $key => $item )
    $q_str .= '&'.$key.'='.urlencode( $item );
  
  if( $q_mark && $q_str )
    $q_str[0] = '?';
  
  return $q_str;
}

//-------------------------------------------------------------------------------------------
function append_query_str( $location, $q_str_in )
{
  if( $q_str_in == '' || is_null($q_str_in) )
    return $location;
  
  if( gettype( $q_str_in ) == 'array' )
  {
    foreach( $q_str_in as $key => $item )
      $q_str .= '&'.$key.'='.urlencode( $item );
    
    if( !$q_str )
      return $location;
  }
  else
    $q_str = $q_str_in;
  
  if( $q_str[0] != '&' )
    $q_str = '&'.$q_str;
  
  if( !preg_match( '/\?/', $location ) )
    $q_str[0] = '?';
  
  return $location.$q_str;
}

/*
//-------------------------------------------------------------------------------------------
class htm_list
{
  // html meta data
  var $l_type;
  var $l_class;
  var $l_style;
  
  // actual list data
  var $i_data;
  var $i_class;
  var $i_style;
  var $i_num;
  
  function htm_list ( $type="ul", $class=NULL, $style=NULL )
  {
    $this->i_num     = 0;
    $this->l_type    = $type;
    $this->l_class   = $class;
    $this->l_style   = $style; 
  }
  
  function add_item ( $item_data, $class=NULL, $style=NULL )
  {
    $this->i_data[ $this->i_num ] = $item_data;
    $this->i_class[$this->i_num ] = $class;
    $this->i_style[$this->i_num ] = $style;
    $this->i_num++;
  }
  
  // print out entire list
  function format ( )
  {
    if( $this->l_class )
      $this->l_class = " class=\"".$this->l_class."\"";
    if( $this->l_style )
      $this->l_style = " style=\"".$this->l_style."\"";
    
    $ret_val = sprintf( "<%s%s%s>", $this->l_type, $this->l_class, $this->l_style );
    
    for( $i=0; $i < $this->i_num; $i++ )
    {
      $ret_val .= $this->i_format( $i );
    }
    
    $ret_val .= "</".$this->l_type.">";
    
    return $ret_val;
  }
  
  // print out individual item
  function i_format ( $i_num )
  {
    if( $this->i_class[ $i_num ] )
      $this->i_class[ $i_num ] = " class=\"".$this->i_class[ $i_num ]."\"";
    if( $this->i_style[ $i_num ] )
      $this->i_style[ $i_num ] = " style=\"".$this->i_style[ $i_num ]."\"";
    
    return sprintf( "<li%s%s>%s</li>", $this->i_class[ $i_num ], 
                     $this->i_style[ $i_num ], $this->i_data[ $i_num ] );
  }
}


class htm_menu extends htm_list
{
  function htm_menu ( )
  {
    $this->i_num     = 0;
    $this->l_type    = "ul";
    $this->l_class   = NULL;
    $this->l_style   = NULL; 
  }
  
  function add_menu_item ( $label, $link=NULL, $here=0 )
  {
    if( $link )
      $this->i_data[ $this->i_num ] = htm_link( $link, $label );
    else
      $this->i_data[ $this->i_num ] = $label;
    
    if( $here )
      $this->i_class[$this->i_num ] = "here";
    elseif( $this->i_num == 0 )
      $this->i_class[$this->i_num ] = "first";
    else
      $this->i_class[$this->i_num ] = NULL;
    
    
    $this->i_style[$this->i_num ] = NULL;
    $this->i_num++;
  }

}
*/

?>
