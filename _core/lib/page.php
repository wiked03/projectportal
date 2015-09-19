<?

class CORE_Page
{

  var $data;
  var $tags;

  var $content;
  var $sidebar;
  var $menu;

  var $script;
  var $inline_script;
  var $style;
  var $inline_style;

  //--------------------------------
  function CORE_Page()
  {
    require( PATH_APP.'config/page'.EXT );
    
    foreach( $page as $key => $val )
      $this->$key = $val;
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
  function set_tag( $name, $key, $val )
  {
    $this->tags[$name][$key][] = $val;
  }

  //--------------------------------
  function set_class( $name, $val )
  {
    $this->set_tag( $name, 'class', $val );
  }
  
  //--------------------------------
  function set_extra( $name, $val )
  {
    $this->set_tag( $name, 'extra', $val );
  }

  //--------------------------------
  function print_tag( $name, $print_value=true )
  {

    if( is_array( $this->tags[$name] ) )
    {
      foreach( $this->tags[$name] as $key => $val )
      {
        $val = implode( " ", $this->tags[$name][$key] );
      
        if( $key === 'extra' )
          $ret_val .= ' '.$val;
        else
          $ret_val .= ' '.$key.'="'.$val.'"';
      }
    }

    if( $print_value )
      $ret_val .= ' value="'.$this->get( $name, F_HTM ).'"';

    return $ret_val;
  }

  //--------------------------------
  function print_span( $name, $value_list=NULL, $override_value=NULL )
  {

    if( is_array( $this->tags[$name] ) )
    {
      foreach( $this->tags[$name] as $key => $val )
      {
        $val = implode( " ", $this->tags[$name][$key] );
      
        if( $key === 'extra' )
          $ret_val .= ' '.$val;
        else
          $ret_val .= ' '.$key.'="'.$val.'"';
      }
    }

    $value = $this->get( $name, F_HTM );

    if( isset( $value_list ) )
      $value = $value_list[ $value ];

    if( isset( $override_value ) )
      $value = $override_value;

    $ret_val .= '<span class="value" '.$ret_val.'>'.$value.'</span>';

    return $ret_val;
  }

  //-------------------------------
  function print_form_item( $form_name, $item_name, $label=NULL )
  {

    $ret_val = '
   <div class="form_item '.$item_name.'">';

    if( $label )
      $ret_val .= '<label for="'.$form_name.'-'.$item_name.'">'.$label.':</label>';

    $ret_val .= '<input type="text" name="'.$item_name.'" id="'.$form_name.'-'.$item_name.'" '.$this->print_tag( $item_name ).' />
    '.SP_DIV.'
   </div>';

    return $ret_val;
  }

  //-------------------------------
  function print_display_item( $item_name, $label, $value_list=NULL, $override_value=NULL )
  {

    $ret_val = '
   <div class="form_item '.$item_name.'">
    <label>'.$label.':</label>
    '.$this->print_span( $item_name, $value_list, $override_value ).'
    '.SP_DIV.'
   </div>';

    return $ret_val;
  }

  //-------------------------------
  function add_script( $file )
  {

    //$this->script[] = 'script/'.str_replace( '.js', '', $file ).'.js?20';
	$this->script[] = 'script/'.str_replace( '.js', '', $file ).'.js';
  }

  //-------------------------------
  function append_script( $code )
  {
    $this->inline_script .= $code;
  }

  //-------------------------------
  function add_style( $file )
  {

    $this->style[] = 'style/'.str_replace( '.css', '', $file ).'.css';
  }

  //-------------------------------
  function append_style( $code )
  {
    $this->inline_style .= $code;
  }

  //-------------------------------
  function append_content( $content )
  {
    $this->content .= $content;
  }

  //-------------------------------
  function append_sidebar( $content )
  {
    $this->sidebar .= $content;
  }

  //-------------------------------
  function add_load_event( $script )
  {
    $this->body['onload'] .= $script;
  }

  function add_menu_item( $text, $link, $class='', $extra='' )
  {
    $this->menu[] = array( 'text'=>$text, 'link'=>$link, 'class'=>$class, 'extra'=>$extra );
  }

  //===========================================================================================
  function print_page( )
  {
    load_view( 'pagehead' );

    $this->print_sidebar( );

    $this->print_content( );

    load_view( 'pagetail' );
  }

  //-------------------------------
  function print_sidebar( )
  {
    return '<div id="page_sidebar">'.N.$this->sidebar.N.'</div>';
  }

  //-------------------------------
  function print_content( )
  {
    return '<div class="page_content">'.N.$this->content.N.'</div>';
  }
}
