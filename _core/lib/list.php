<?

class CORE_List
{

  var $col_titles = array();
  var $col_aligns = array();
  var $col_widths = array();
  var $col_names = array();
  var $col_index = array();
  var $col_no_ob = array();

  var $col_count = 0;
  var $row_count = 0;
  var $data = array();
  var $row_links = array();
  var $footer_data = array();

  var $search_string = array();

  var $show_cols = NULL;
  var $override_cols = array();

  var $page_num;
  var $total = 0;
  var $total_pages;
  var $items_per_page;
  var $max_pages;
  var $link;

  var $order_by = 0;
  var $desc = false;

  //--------------------------------
  function CORE_List( $page_vars, $total=0, $items_per_page=25, $max_pages=8, $ob=0, $desc=false )
  {
    $regex =  array( '/\/ob-([\d]+)/', '/\/p-([\d]+)/', '/\/desc/' );

    if( preg_match( $regex[0], $page_vars, $matches ) )
      $this->order_by = $matches[1];
    else
      $this->order_by = $ob;

    if( preg_match( $regex[1], $page_vars, $matches ) )
      $this->page_num = $matches[1];
    else
      $this->page_num = 1;

    if( preg_match( $regex[2], $page_vars, $matches ) )
      $this->desc = true;
    elseif( !preg_match( $regex[0], $page_vars ) && !preg_match( $regex[1], $page_vars ) && !preg_match( $regex[2], $page_vars ) )
      $this->desc = $desc;

    if( preg_match( '/\/col-([\.\d]+)/', $page_vars, $matches ) )
    {
      $cols = explode( '.', $matches[1] );
      foreach( $cols as $col )
        $this->show_cols[ $col ] = true;
    }

    $this->link = preg_replace( $regex, '', PATH_SELF );

    $this->items_per_page = $items_per_page;
    $this->max_pages = ceil( $max_pages/2 )*2;
    $this->set_total( $total );
  }

  //-------------------------------------
  function set_total( $total )
  {
    $this->total = $total;

    if( $this->items_per_page )
      $this->total_pages = ceil( $total/$this->items_per_page );
  }

  //--------------------------------
  function required_columns( $cols )
  {
    if( !is_array( $cols ) )
      $cols = explode( '.', $cols );

    foreach( $cols as $col )
      $this->override_cols[ $col ] = true;
  }

  //--------------------------------
  function default_columns( $cols )
  {
    if( isset( $this->show_cols ) )
      return;

    if( !is_array( $cols ) )
      $cols = explode( '.', $cols );
    
    foreach( $cols as $col )
      $this->show_cols[ $col ] = true;
  }

  //--------------------------------
  function get_column_titles( )
  {
    foreach( $this->col_titles as $idx => $val )
      $ret_val[ $this->col_index[$idx] ] = $val;

    return $ret_val;
  }

  //--------------------------------
  function get_overrides( )
  {
    return $this->override_cols;
  }

  //--------------------------------
  function add_column( $index, $title, $width=NULL, $align=NULL, $no_ob=false )
  {
    $this->col_titles[$index] = $title;
    $this->col_aligns[$index] = $align;
    $this->col_widths[$index] = $width;
    $this->col_no_ob[$index]  = $no_ob;
    $this->col_index[$index] = $this->col_count;
    $this->col_names[$this->col_count++] = $index;
  }

  //--------------------------------
  function add_row( $data, $footer=false, $format=F_SQL, $row_link=NULL )
  {
    $data = CORE_encode( $data, F_HTM, $format );

    if( $footer )
      $this->footer_data = $data;
    else
    {
      if( isset( $row_link ) )
        $this->row_links[$this->row_count] = $row_link;
      $this->data[$this->row_count++] = $data;
    }

    return $this->row_count-1;
  }

  //--------------------------------
  function set_row_data( $index, $data )
  {
    $this->data[($this->row_count-1)][$index] = $data;
  }

  //--------------------------------
  function set_row_link( $link )
  {
    $this->row_links[($this->row_count-1)] = $link;
  }

  //--------------------------------
  function print_table( $empty_text='No records found matching that search criteria. Try another search.' )
  {
    if( !$this->row_count )
      return '<div class="list_table_empty">'.$empty_text.'</div>';

    $ret_val .= '<table class="list_table'.( $this->no_footer ? ' no_foot' : '' ).( $this->no_header ? ' no_head' : '' ).'">';

    if( !$this->no_header )
      $ret_val .= $this->get_header( );
    
    if( !$this->no_footer )
      $ret_val .= $this->get_footer( );

    $ret_val .= $this->get_rows( );
    $ret_val .= '</table>';

    return $ret_val;
  }


  //--------------------------------
  function get_header( )
  {
    $ret_val = '<thead><tr>';

    $first = false;
    foreach( $this->col_names as $i => $index )
    {
      if( !isset( $this->show_cols ) || $this->show_cols[$i] || $this->override_cols[$i] )
      {
        $class = '';
        $style = '';
        $img = '';
        $desc = '';

        if( !$first )
          $class = ' class="first"';

        if( $this->col_widths[$index] )
          $style = ' style="width:'.$this->col_widths[$index].'px"';

        $desc = ( ($this->order_by == $i && !$this->desc) ? '/desc' : '' );

        if( $this->order_by == $i )
          //$img = '<img class="arrow_s " src="'.($this->desc ? 'down' : 'up' ).'" />';
          $img = '<img class="arrow_s '.($this->desc ? 'down' : 'up' ).'" src="img/layout/sp.gif"/>';

        //if( $this->total_pages > 1 )
        //  $page = '/p-'.$this->page_num;
        if( !$this->col_no_ob[$index] )
          $ret_val .= '<th '.$class.$style.'><a href="'.$this->link.'/ob-'.$i.$desc.'">'.$this->col_titles[$index].$img.'</a></th>';
        else
          $ret_val .= '<th '.$class.$style.'><a>'.$this->col_titles[$index].'</a></th>';

        $first = true;
      }
    }
    $ret_val .= '</tr></thead>';

    return $ret_val;
  }

  //--------------------------------
  function get_footer( )
  {
    $ret_val = '<tfoot><tr>';


    foreach( $this->col_names as $i => $index )
    {
      if( !isset( $this->show_cols ) || $this->show_cols[$i] || $this->override_cols[$i] )
      {
        $class = '';
        if( !$i )
          $class = ' class="first"';

        if( $this->footer_data[$index] )
          $ret_val .= '<td'.$class.'>'.$this->footer_data[$index].'</td>';
        else
          $ret_val .= '<td class="first">'.SP_DIV.'</td>';
        $i++;
      }
    }

    $ret_val .= '</tr></tfoot>';

    return $ret_val;
  }

  //--------------------------------
  function disable_row_link( $index )
  {
    $this->no_row_link[$index] = true;
  }

  //--------------------------------
  function get_rows( )
  {
    $ret_val = '<tbody>';

    foreach( $this->data as $key => $data )
    {
      if( $key % 2 )
        $ret_val .= '<tr class="alt">';
      else
        $ret_val .= '<tr>';

      foreach( $this->col_names as $i => $index )
      {
        if( !isset( $this->show_cols ) || $this->show_cols[$i] || $this->override_cols[$i] )
        {
          $class = '';
          $style = '';
          $onclick = '';

          if( $this->col_widths[$index] )
          {
            $style = ' style="width:'.$this->col_widths[$index].'px"';
            if( $this->col_aligns[$index] == 'left' || !$this->col_aligns[$index] )
              $data[$index] = '<div class="cool">'.$data[$index].'</div>';
          }
          if( $this->col_aligns[$index] )
            $class = ' class="td_'.$this->col_aligns[$index].'"';
      
          if( $this->row_links[ $key ] && !$this->no_row_link[$index] )
            $onclick = ' onclick="Dom.goto(\''.$this->row_links[ $key ].'\')"';

          $ret_val .= '<td'.$class.$onclick.'><div'.$style.'>'.$data[$index].'</div></td>';
        }
      }
      $ret_val .= '</tr>';

    }

    $ret_val .= '</tbody>';

    return $ret_val;
  }

  //--------------------------------
  function print_csv(  )
  {
    // get headers
    $row = array( );
    foreach( $this->col_names as $i => $index )
    {
      if( !isset( $this->show_cols ) || $this->show_cols[$i] || $this->override_cols[$i] )
      {
        $row[] = '"'.preg_replace('/"/', '""', $this->col_titles[$index]).'"';
      }
    }

    $ret_val = implode( ',', $row )."\r\n";
    
    // get rows
    foreach( $this->data as $key => $data )
    {
      $row = array();
      foreach( $this->col_names as $i => $index )
      {
        if( !isset( $this->show_cols ) || $this->show_cols[$i] || $this->override_cols[$i] )
        {
          //$row[] = '"'.preg_replace('/"/', '""', $data[$index]).'"';
          $row[] = '"'.preg_replace('/"/', '""', preg_replace('#<br\s*/?>#', "\n", $data[$index])).'"';
        }
      }
      $ret_val .= implode( ',', $row )."\r\n";
    }

    return $ret_val;
  }


  //--------------------------------
  function get_order_by( )
  {
    $ret_val = $this->col_names[ $this->order_by ];

    if( $this->desc )
      $ret_val .= ' DESC';
    else
      $ret_val .= ' ASC';
    return $ret_val;
  }
  
  //--------------------------------
  function get_limit( )
  {
    if( !$this->items_per_page )
      return '18446744073709551615';

    return (($this->page_num - 1)*$this->items_per_page).', '.$this->items_per_page;
  }

  //--------------------------------
  function print_pagination( )
  {
    $page_num = $this->page_num;
    $total_pages = $this->total_pages;
    $max_pages = $this->max_pages;
    $link = $this->link;
    $q_str = ($this->order_by ? '/ob-'.$this->order_by : '').($this->desc ? '/desc' : '');

    // limit the amount of page links to $max_pages + 1
    if( $total_pages > ($max_pages + 1) )
    {
      if( ($page_num - $max_pages/2) < 1 )
        $i = 1;
      elseif( ($page_num + $max_pages/2) > $total_pages )
        $i = $total_pages - $max_pages;
      else
        $i = $page_num - $max_pages/2;

      $total_pages = $i + $max_pages;
    }
    else 
      $i = 1;

    if( $total_pages <= 1 )
      return '';

    $ret_val = '<div class="list_link"><span class="list_link_text">Page:</span>'.n;

    if( $total_pages > 2 )
    {
      if( $page_num > 1 )
        $links[] = '<a href="'.$link.'/p-1'.$q_str.'" class="first arrow_h" title="First">'.SP_DIV.'</a>
                    <a href="'.$link.'/p-'.($page_num - 1).$q_str.'" class="arrow_sh" title="Previous">'.SP_DIV.'</a>';
      else
        $links[] = '<span class="first arrow gray">'.SP_DIV.'</span><span class="arrow_s gray">'.SP_DIV.'</span>';
    }

    for( ; $i <= $total_pages; $i++ )
    {
      $span = '';
      if( $total_pages <= 2 && $i == 1 )
        $span = ' class="first"';
      
      if( $i == $page_num )
        $links[] = '<span'.$span.'>'.$i.'</span>';
      else
        $links[] = '<a '.$span.' href="'.$link.'/p-'.$i.$q_str.'">'.$i.'</a>';
    }

    if( $total_pages > 2 )
    {
      if( $page_num < $this->total_pages )
        $links[] = '<a href="'.$link.'/p-'.($page_num + 1).$q_str.'" class="arrow_sh right" title="Next">'.SP_DIV.'</a>
                    <a href="'.$link.'/p-'.$this->total_pages.$q_str.'" class="arrow_h right" title="Last">'.SP_DIV.'</a>';
      else
        $links[] = '<span class="arrow_s gray right">'.SP_DIV.'</span><span class="arrow gray right">'.SP_DIV.'</span>';
    }

    $ret_val .= implode( '', $links );

    $ret_val .= '</div>';
 
    return $ret_val;
  }


}
