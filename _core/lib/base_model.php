<?

//----------------------------------------------
// Optional Call-back Functions
//----------------------------------------------
// _set_defaults( )
// _post_get_data( $ret_val, $format )
// _post_set_data( $data, $format )
// _delete_data( )
// _load_data( $id )
// _post_load_data( $id )
// _write_back( )
// _post_write_back( )


class CORE_Model
{
  var $id           = 0;
  var $id_name      = 'pk_id';
  var $table_name   = '';
  var $fields       = array();
  var $data         = array();
  var $valid        = false;

  // ----------------------------------------------------------------------------------------
  function get( $item, $format=F_PHP )
  {
    if( $item == 'id' || $item == $this->id_name )
    {
      if( is_array( $this->id_name ) )
      {
        foreach( $this->id_name as $key => $name )
        {
          $ret_val[$key] = $this->get( $name, $format );
        }
        return $ret_val;
      }
      else
        return $this->id;
    }

    // get to the value we want if 'something[1][2]' was passed
    // matches[1] contains "something", $matches[2] contains "[1][2]"
    if( preg_match('!([\w]+)(\[.+\])!', $item, $matches) )
      eval('$data = $this->data['.$matches[1].']'.$matches[2].';');
    else
      $data = $this->data[$item];

    return CORE_encode( $data, $format );
  }

  // ----------------------------------------------------------------------------------------
  function set( $item, $value, $format=F_PHP )
  {
    if( $item == 'id' || $item == $this->id_name )
    {

      if( is_array( $this->id_name ) )
      {
        $this->id = 1;
        foreach( $this->id_name as $key => $name )
        {
          $this->set( $name, $value[ $key ], $format );
          if( !$value[ $key ] )
            $this->id = 0;
        }
      }
      else
        $this->id = $value;
      return;
    }

    $data = CORE_decode( $value, $format );

    if( preg_match('!([\w]+)(\[.+\])!', $item, $matches) )
      eval('$this->data['.$matches[1].']'.$matches[2].' = $data;');
    else
      $this->data[$item] = $data;
  }

  // ----------------------------------------------------------------------------------------
  function get_data( $format=F_PHP )
  {
    // use prototype: _pre_get_data( &$data, $format );
    if( method_exists( $this, '_pre_get_data' ) )
      $this->_pre_get_data( $this->data, $format );

    if( !$this->valid )
      return NULL;

    if( !is_array( $this->id_name ) )
      $ret_val[$this->id_name] = $this->id;

    if( is_array( $this->data ) )
      foreach( $this->data as $key => $val )
        $ret_val[$key] = CORE_encode( $val, $format );

    // use prototype: _post_get_data( &$data, $format );
    if( method_exists( $this, '_post_get_data' ) )
      $this->_post_get_data( $ret_val, $format );

    return $ret_val;
  }

  // ----------------------------------------------------------------------------------------
  function set_data ( $data, $format=F_PHP, $not_id=false )
  {
    if( method_exists( $this, '_pre_set_data' ) )
      $this->_pre_set_data( $data, $format );

    if( !$not_id && is_array( $this->id_name ) )
    {
      $this->id = 1;
      foreach( $this->id_name as $name )
        if( !$data[$name] )
          $this->id = 0;
    }
    elseif( !$not_id && $data[$this->id_name] )
      $this->id = $data[$this->id_name];

    if( is_array( $data ) )
      foreach( $data as $key => $val )
        $this->data[$key] = CORE_decode( $val, $format );

    $this->valid = true;

    if( method_exists( $this, '_post_set_data' ) )
      $this->_post_set_data( $data, $format );
  }

  // ----------------------------------------------------------------------------------------
  function clear_data( )
  {
    $this->id = 0;
    $this->data = array( );
    $this->valid = false;

    if( method_exists( $this, '_set_defaults' ) )
      $this->_set_defaults( );
  }

  // ----------------------------------------------------------------------------------------
  function delete_data( )
  {
    if( method_exists( $this, '_delete_data' ) )
      return $this->_delete_data( );

    if( !$this->id || !$this->table_name || !$this->id_name )
      return false;

    if( is_array( $this->id_name ) )
    {
      foreach( $this->id_name as $name )
        $where[] = $name."=".$this->get( $name, F_SQL );
      $where = implode( ' AND ', $where );
    }
    else
      $where = $this->id_name."=".$this->id;

    $sql = "DELETE 
            FROM ".$this->table_name."
            WHERE ".$where;

    mysql_query( $sql );
    $this->clear_data( );
    return true;
  }

  // ----------------------------------------------------------------------------------------
  function load_data( $id=0 )
  {
    if( method_exists( $this, '_load_data' ) )
      return $this->_load_data( $id );
    if( method_exists( $this, '_pre_load_data' ) )
      $this->_pre_load_data( $id );

    if( $id )
      $this->set('id', $id );
    if( !$this->id || !$this->table_name || !$this->id_name )
      return false;

    if( is_array( $this->id_name ) )
    {
      foreach( $this->id_name as $name )
        $where[] = $name."=".$this->get( $name, F_SQL );
      $where = implode( ' AND ', $where );
    }
    else
      $where = $this->id_name."=".$this->id;

    $sql = "SELECT *
            FROM ".$this->table_name."
            WHERE ".$where;

//echo $sql;

    $result = mysql_query( $sql );

    if( !($result = mysql_fetch_array($result, MYSQL_ASSOC)) )
      return false;

    $this->set_data( $result );

    if( method_exists( $this, '_post_load_data' ) )
      $this->_post_load_data( $id );

    return $this->id;
  }

  // ----------------------------------------------------------------------------------------
  function write_back ( )
  {
    if( method_exists( $this, '_write_back' ) )
      return $this->_write_back( );
    if( method_exists( $this, '_pre_write_back' ) )
      $this->_pre_write_back( );

    if( !$this->valid || !$this->table_name || !$this->id_name || !$this->fields )
      return false;


    if( is_array( $this->id_name ) )
    {
      foreach( $this->id_name as $name )
        $where[] = $name."=".$this->get( $name, F_SQL );
      $where = implode( ' AND ', $where );

      $sql = "SELECT *
              FROM ".$this->table_name."
              WHERE ".$where;

      $result = mysql_query( $sql );

      $insert = false;

      if( mysql_num_rows($result) == 0 )
        $insert = true;
    }


    if( !$this->id || $insert )
    {
      foreach( $this->fields as $field )
        $values[] = $this->get( $field, F_SQL );

      $sql = "INSERT INTO ".$this->table_name."
                  ( ".implode(',', $this->fields)." )
              VALUES ( ".implode(',', $values)." )";

error_log($sql);
//echo $sql;
      $result = mysql_query( $sql );
     
      $this->id = mysql_insert_id( );
    }
    else
    {  
      foreach( $this->fields as $field )
        $values[] = $field.'='.$this->get( $field, F_SQL );

      if( !is_array( $this->id_name ) )
        $where = $this->id_name."=".$this->id;

      $sql = "UPDATE ".$this->table_name."
              SET ".implode(',', $values)."
              WHERE ".$where;
      
error_log($sql);      
//echo $sql;
      $result = mysql_query( $sql );
    }

    if( method_exists( $this, '_post_write_back' ) )
      $this->_post_write_back( );

    return $this->id;
  }

}
