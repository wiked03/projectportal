
var g_start_day_of_week = 0;

var month_names = [];

month_names[ 0 ] = [ "J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D" ];
month_names[ 1 ] = [ "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec" ];
month_names[ 2 ] = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];

var day_names = [];

day_names[ 0 ] = [ "S", "M", "T", "W", "T", "F", "S" ];
day_names[ 1 ] = [ "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat" ];
day_names[ 2 ] = [ "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday" ];


function printDate( date_obj, format, separator )
{

  if( !isset(separator) )
    separator = g_date_separator;
  if( !isset(format) )
    format = g_date_format;
  //var separator = '/';
  // format -  international: 2, USA: 0, EUR: 1
  
  if( format == 2)
    return date_obj.getFullYear() + separator + zeroPad((date_obj.getMonth() + 1), 2) + separator + zeroPad(date_obj.getDate() ,2);
  else if( format == 0)
    return (date_obj.getMonth() + 1) + separator + date_obj.getDate() + separator + date_obj.getFullYear();
  else if( format == 1)
    return date_obj.getDate() + separator + (date_obj.getMonth() + 1) + separator + date_obj.getFullYear();
}


function makeDate( range_type, end )
{
  var begin_date, end_date;
  var today = new Date( );

  switch( range_type )
  {
    case( 'lw' ):
      end_date = new Date( );
      end_date.setDate( end_date.getDate() - 1 );
      while( end_date.getDay() != 6 )
        end_date.setDate( end_date.getDate() - 1 );

      begin_date = new Date( end_date );
      begin_date.setDate( end_date.getDate() - 6 );
      break;

    case( 'lm' ):
      begin_date = new Date( );
      begin_date.setDate( 1 );
      begin_date.setMonth( today.getMonth() - 1 );
      end_date = new Date( begin_date );
      end_date.setMonth( today.getMonth() );
      end_date.setDate( 0 );
      break;

    case( 'cm' ):
      begin_date = new Date( );
      begin_date.setDate( 1 );
      begin_date.setMonth( today.getMonth() );
      end_date = new Date( begin_date );
      end_date.setMonth( today.getMonth() + 1 );
      end_date.setDate( 0 );
      break;

    case( 'ly' ):
      begin_date = new Date( );
      begin_date.setMonth( 0 );
      begin_date.setDate( 1 );
      begin_date.setYear( today.getFullYear() - 1 );
      end_date = new Date( begin_date );
      end_date.setMonth( 11 );
      end_date.setDate( 31 );
      break;

    case( 'ytd' ):
      begin_date = new Date( );
      begin_date.setMonth( 0 );
      begin_date.setDate( 1 );
      end_date = new Date( today );
      break;

    case( '0' ):
      return '';
  }

  if( end )
    return printDate( end_date );
  else
    return printDate( begin_date );

}


function Calendar( me, id, is_popup )
{
  this.start_day = g_start_day_of_week;
  this.show_number_of_days = 7;
  this.show_number_of_years = 5;
  this.date_format = g_date_format;
  this.date_separator = g_date_separator;

  this.month_style = 2;
  this.day_style = 0;
  this.month_select = 0;
  this.year_select = 0;

  this.max_date = 0;
  this.min_date = 0;
  this.default_date = 0;

  this.id = id;
  this.me = me;
  this.is_popup = is_popup;

  this.link_obj = null;
  this.link_gtlt = '';

  this.clear_cal = 0;

  //----------------------------
  // do constructor tasks
  addLoadEvent( this.me+".create()" );

  //------------------------------------------
  this.create = function( )
  {
    if( !this.is_popup )
      this.update( Dom.get( this.id ).value, Dom.get( this.id ).value );
    else
    {
      addClickEvent( "setTimeout( \""+this.me+".hide()\", 20 )" );

      addEventHandler( 'onclick', 'cal_'+this.id, this.me+".keep_open();" );

      addEventHandler( 'onclick', 'cal_link_'+this.id, this.me+".show( Dom.get( '"+this.id+"' ).value, Dom.get( '"+this.id+"' ).value );" );
    } 

  }

  //------------------------------------------
  this.set_range = function( min_date, max_date, num_years )
  {
    var my_date;

    if( min_date )
    {
      if( min_date == 'today' )
        my_date = new Date(); 
      else
        my_date = new Date( min_date.toString() );
      this.min_date = new Date( my_date.getFullYear(), my_date.getMonth(), my_date.getDate(), 0, 0, 0, 0 );
    }
    else
      this.min_date = 0;

    if( max_date )
    {
      if( max_date == 'today' )
        my_date = new Date(); 
      else
        my_date = new Date( max_date.toString() );
      this.max_date = new Date( my_date.getFullYear(), my_date.getMonth(), my_date.getDate(), 23, 59, 59, 999 );
    }
    else
      this.max_date = 0;

    if( isset(num_years) )
      this.show_number_of_years = num_years;
  
  }

  //------------------------------------------
  this.link_date = function( gtlt, link_obj )
  {
    eval( 'var obj = '+link_obj );

    // add onchange event to other item
    addEventHandler( 'onchange', obj.id, this.me+".update_range();" );

    this.link_obj = obj.id;
    this.link_gtlt = gtlt;
  }


  //------------------------------------------
  this.update_range = function( )
  {

    var my_date    = new Date( );
    var other_date = new Date( );

    var my_valid    = !validate_date( Dom.get( this.id ).value, 1, 1, my_date );
    var other_valid = !validate_date( Dom.get( this.link_obj ).value, 1, 1, other_date );

    if( this.link_gtlt == 'gt' && other_valid )
    {
      this.set_range( other_date.toString(), 0 );

      if( my_date < other_date || !my_valid )
        Dom.get( this.id ).value = '';
    }
    if( this.link_gtlt == 'lt' && other_valid )
    {
      this.set_range( 0, other_date.toString() );
  
      if( my_date > other_date || !my_valid )
        Dom.get( this.id ).value = '';
    }

  }



  //------------------------------------------
  this.write_back = function( value )
  {
    Dom.get( this.id ).value = value;
    if( Dom.get( this.id ).onchange )
      Dom.get( this.id ).onchange();

    if( this.is_popup )
    {
      setTimeout( this.me+'.clear_cal=1;'+this.me+'.hide()', 60 );
    }
    else
      this.update( value, value )
  }

  //------------------------------------------
  this.keep_open = function( )
  {
    this.clear_cal = 0;
    setTimeout( this.me+'.clear_cal=1', 40 );
  }

  //------------------------------------------
  this.hide = function( )
  {
    if( Dom.get( 'cal_' + this.id ) )
    {
      if( this.clear_cal && Dom.get( 'cal_' + this.id ).innerHTML != '' )
      { 
        Dom.get( 'cal_' + this.id ).innerHTML = '';
        this.clear_cal = 0;
      }
    }
  }


  //------------------------------------------
  this.show = function ( sel_date_in, cur_date_in )
  {
    var calText;

    calText = this.print( sel_date_in, cur_date_in );
  
  
    // print the calendar as a popup
    show_popup_iframe( 'cal_' + this.id, Dom.get( 'cal_' + this.id ), 100 );
  
    if( !Dom.get( 'cal_' + this.id + '_popup_frame') )
    {
      popup_frame = Dom.create( 'div', 'cal_' + this.id + '_popup_frame', '' );
      popup_frame.className = 'cal_popup_frame';
      Dom.add( popup_frame, 'cal_' + this.id );
    }
    else
      popup_frame = Dom.get( 'cal_' + this.id + '_popup_frame');
  
    popup_frame.innerHTML = calText;  

    adjust_popup_iframe( 'cal_' + this.id );  

    this.keep_open( );
  
  }


  //------------------------------------------
  this.update = function ( sel_date_in, cur_date_in )
  {
    if( this.is_popup )
    {
      this.show( sel_date_in, cur_date_in );
      return;
    }

    var calText;

    calText = this.print( sel_date_in, cur_date_in );

    cal_frame = Dom.get( 'cal_' + this.id );
  
    cal_frame.innerHTML = calText;   

  }



  //------------------------------------------
  this.print = function ( sel_date_in, cur_date_in )
  {
    var cur_date = new Date( );
    var show_date;
    var sel_date = new Date( );
    var today = new Date ( );

    var sel_date_valid = !validate_date( sel_date_in, 1, 1, sel_date );
    var cur_date_valid = !validate_date( cur_date_in, 1, 1, cur_date );

    if( !cur_date_valid )
      validate_date( this.default_date, 0, 1, cur_date );

    if( this.min_date && (cur_date < this.min_date) )
      cur_date = new Date( this.min_date.toString() );
    if( this.max_date && (cur_date > this.max_date) )
      cur_date = new Date( this.max_date.toString() );

    if( (this.min_date && (sel_date < this.min_date)) || (this.max_date && (sel_date > this.max_date)) )
      sel_date_valid = false;

    //===========================================================================================
    // Calendar Header
    //===========================================================================================
    calText = '<div class="cal_header">';
  
    // Make Previous Month link -----------------
    show_date = new Date( cur_date );
    show_date.setDate( 1 );
    show_date.setMonth(show_date.getMonth() - 1);
  
    calText += '<a onclick="'+this.me+'.update( \'' + sel_date_in + '\', \'' + printDate(show_date, 2) + '\' )"><span>&laquo;</span></a>';
      
    // Month, Year -----------------
    // calText += '<div class="header_date"><span>' + month_names_long[( cur_date.getMonth() )] +' ' + cur_date.getFullYear() + '</span></div>';
    calText += '<div class="header_date"><span>';

    //--- month select
    if( this.month_select )
    {
      calText += '<select id="' + this.id + '_month" class="cal_select_month" onchange="'+this.me+'.update( \'' + sel_date_in + '\', Dom.get(\'' + this.id + '_month\').value )">';

      var month_list = [];

      // check the range
      for( var m = 0, y = 0; m < 12; m++ )
      {
        y = cur_date.getFullYear();

        show_date.setFullYear( y, m, 1 );

        if( this.max_date && ( (show_date.getFullYear() > this.max_date.getFullYear()) || (y == this.max_date.getFullYear() && m > this.max_date.getMonth()) ) )
          y = y - 1;
        if( this.min_date && ( (y < this.min_date.getFullYear()) || (y == this.min_date.getFullYear() && m < this.min_date.getMonth()) ) )
        {
          y = y + 1;
          if( this.max_date && ( (show_date.getFullYear() > this.max_date.getFullYear()) || (y == this.max_date.getFullYear() && m > this.max_date.getMonth()) ) )
            continue;
        }

        month_list.push( [m,y] );

      }

      month_list.sort( function(a, b) 
                       { if (a[1]<b[1]||(a[1]==b[1]&&a[0]<b[0])) return -1;
                         if (a[1]>b[1]||(a[1]==b[1]&&a[0]>b[0])) return 1;
                         return 0; } );

      for( var m = 0; m < month_list.length; m++ )
      {
        show_date.setFullYear( month_list[m][1], month_list[m][0], 1 );

        if( show_date.getMonth( ) == cur_date.getMonth( ) )
          calText += '<option selected';
        else
          calText += '<option';

        calText += ' value="' + printDate(show_date, 2) + '">' + month_names[ this.month_style ][( show_date.getMonth() )] + '</option>';
      }

      calText += '</select>';
    }
    else
      calText += '' + month_names[ this.month_style ][( cur_date.getMonth() )] + '';

    //--- year select
    if( this.year_select )
    {
      show_date.setFullYear( cur_date.getFullYear(), cur_date.getMonth(), 1 );

      calText += '<select id="' + this.id + '_year" class="cal_select_year" onchange="'+this.me+'.update( \'' + sel_date_in + '\', Dom.get(\'' + this.id + '_year\').value )">';

      var low = cur_date.getFullYear() - this.show_number_of_years;
      var high = cur_date.getFullYear() + this.show_number_of_years;

      if( this.min_date && this.min_date.getFullYear() > low )
      {
        if( this.max_date )
          high = Math.min( (high + this.min_date.getFullYear() - low), this.max_date.getFullYear() );
        else
          high = high + this.min_date.getFullYear() - low;
        low = this.min_date.getFullYear();
      }
      if( this.max_date && this.max_date.getFullYear() < high )
      {
        if( this.min_date )
          low = Math.max( (low - (high - this.max_date.getFullYear())), this.min_date.getFullYear() );
        else
          low = low - (high - this.max_date.getFullYear());
        high = this.max_date.getFullYear();
      }

      for( var y = low; y <= high; y++ )
      {
        show_date.setFullYear( y );

        if( show_date.getFullYear( ) == cur_date.getFullYear( ) )
          calText += '<option selected';
        else
          calText += '<option';

        calText += ' value="' + printDate(show_date, 2) + '">' + show_date.getFullYear() + '</option>';
      }

      calText += '</select>';
    }
    else
      calText += '&nbsp;&nbsp;' + cur_date.getFullYear() + '';

    calText += '</span></div>';

    // Make Next Month link -----------------
    show_date.setFullYear( cur_date.getFullYear(), cur_date.getMonth() + 1, 1 );
  
    calText += '<a onclick="'+this.me+'.update( \'' + sel_date_in + '\', \'' + printDate(show_date, 2) + '\' )"><span>&raquo;</span></a>'; 

    calText += '</div>';
    
    show_date.setFullYear( cur_date.getFullYear(), cur_date.getMonth(), 1 );
  
    // find the starting date
    while( show_date.getDay() != this.start_day )
    {
      show_date.setDate( show_date.getDate() - 1 );
    }
  
    // -----------------------------------------------------------------------------
    // Day of the week labels
    // -----------------------------------------------------------------------------
    calText += '<div class="cal_body">';

    // ------------- Print day of week headers (Sun, Mon...) 
    calText += '<table><thead>';
  
    for(var i = 0; i < this.show_number_of_days; i++)
    { 
      style = '';
    
      // grey out weekend if selected
      if( ((show_date.getDay() + i)%7 == 0) || ((show_date.getDay() + i)%7 == 6) )
        style = ' class="weekend_label"';
    
      // choose display option (S, Sun, Sunday)
      calText += '<th' + style + '>' + day_names[ this.day_style ][( (show_date.getDay() + i)%7 )] + '</th>';
    }
  
    calText += '</thead><tbody>';
  
  
    // -----------------------------------------------------------------------------
    // Calendar part
    // -----------------------------------------------------------------------------
    
    var curMonth = cur_date.getMonth();
  
    while( (show_date.getMonth() <= cur_date.getMonth() && show_date.getFullYear() == cur_date.getFullYear()) ||
         (show_date.getMonth() > cur_date.getMonth() && show_date.getFullYear() < cur_date.getFullYear()) )
    {
      // loop through one week
      for( var j = 0; j < this.show_number_of_days && j < 7; j++ )
      {
        var theDate = printDate( show_date, 2 ); 
        style = '';
        if( ((show_date.getDay() + i)%7 == 0) || ((show_date.getDay() + i)%7 == 6) ) style = ' class="weekend"';
        if( theDate == printDate(today, 2) ) style = ' class="today"';
        if( theDate == printDate(sel_date, 2) && sel_date_valid ) style = ' class="selected"';
        if( curMonth != show_date.getMonth() ) style = ' class="other_month"';
      
        calText += '<td' + style + '>';

        if( (this.min_date && (show_date < this.min_date)) || (this.max_date && (show_date > this.max_date)) )
          calText += '<span>' + show_date.getDate() + '</span>';
        else
          calText += '<a onclick="'+this.me+'.write_back(\'' + printDate( show_date, this.date_format, this.date_separator ) + '\' );">' + show_date.getDate() + '</a>';

        calText += '</td>';
      
        show_date.setDate(show_date.getDate() + 1);
      }
      // finish incrementing through week in case day count less than 7
      for(; j < 7;  j++)
        show_date.setDate(show_date.getDate() + 1);
       
      calText += '</tr>';
    }

    calText += '</tbody></table></div>';

    return calText;
  }


// end of class
};

