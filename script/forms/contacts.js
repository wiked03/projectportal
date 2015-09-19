//------------------------
function make_userlog_query( )
{
  var form = Dom.get( 'f_userlog' );

  var q_str = '';

  if( form.name.value )
    q_str += '/na-'+CORE_uri_encode( form.name.value );

  if( form.created_by.value )
    q_str += '/cb-'+CORE_uri_encode( form.created_by.value );

  if( form.start.value != '' )
    {
      var date_obj = new Date( );
      if( !validate_date( form.start.value, 1, 1, date_obj ) )
        q_str += '/sd-'+CORE_uri_encode( printDate(date_obj, 2, '') );    
    }
  if( form.end.value != '' )
    {
      var date_obj = new Date( );
      if( !validate_date( form.end.value, 1, 1, date_obj ) )
        q_str += '/ed-'+CORE_uri_encode( printDate(date_obj, 2, '') );    
    }

  Dom.goto( 'admin/userlogs'+q_str );
}

//------------------------
function make_project_query( )
{
  var form = Dom.get( 'f_project' );

  var q_str = '';

  if( form.project_id.value )
    q_str += '/id-'+CORE_uri_encode( form.project_id.value );

  if( form.name.value )
    q_str += '/na-'+CORE_uri_encode( form.name.value );

  if( form.description.value )
    q_str += '/de-'+CORE_uri_encode( form.description.value );

  if( form.specialty1.value != '' )
    q_str += '/ta-'+CORE_uri_encode( form.specialty1.value );

  //if( form.industry.value != '' )
  //  q_str += '/in-'+CORE_uri_encode( form.industry.value );

  if( form.notes.value )
    q_str += '/not-'+CORE_uri_encode( form.notes.value );

  if( form.bd_poc.value != '' )
	q_str += '/bdpoc-'+CORE_uri_encode( form.bd_poc.value );  
  
  if( form.org_search.value )
    q_str += '/org-'+CORE_uri_encode( form.org_search.value );

  if( form.poc.value )
    q_str += '/poc-'+CORE_uri_encode( form.poc.value );

  if( form.directors.value )
	    q_str += '/dir-'+CORE_uri_encode( form.directors.value );
  
  if( form.managers.value )
    q_str += '/pid-'+CORE_uri_encode( form.managers.value );

  if( form.analysts.value )
    q_str += '/an-'+CORE_uri_encode( form.analysts.value );

 // if( form.collectors.value )
//	    q_str += '/coll-'+CORE_uri_encode( form.collectors.value );
  
  if( form.contractors.value )
    q_str += '/con-'+CORE_uri_encode( form.contractors.value );

  if( form.conferences.value != '' )
	    q_str += '/cnf-'+CORE_uri_encode( form.conferences.value );
  
  if( form.start.value != '' )
    {
      var date_obj = new Date( );
      if( !validate_date( form.start.value, 1, 1, date_obj ) )
        q_str += '/sd-'+CORE_uri_encode( printDate(date_obj, 2, '') );    
    }
  if( form.end.value != '' )
    {
      var date_obj = new Date( );
      if( !validate_date( form.end.value, 1, 1, date_obj ) )
        q_str += '/ed-'+CORE_uri_encode( printDate(date_obj, 2, '') );    
    }

  if( form.start_iec.value != '' )
    {
      var date_obj = new Date( );
      if( !validate_date( form.start_iec.value, 1, 1, date_obj ) )
        q_str += '/sdiec-'+CORE_uri_encode( printDate(date_obj, 2, '') );    
    }
  if( form.end_iec.value != '' )
    {
      var date_obj = new Date( );
      if( !validate_date( form.end_iec.value, 1, 1, date_obj ) )
        q_str += '/ediec-'+CORE_uri_encode( printDate(date_obj, 2, '') );    
    }

  if( form.is_active.value && form.is_active.value<4 )
    q_str += '/ia-'+CORE_uri_encode( form.is_active.value );

  if( form.is_life_science.value && form.is_life_science.value<4 )
    q_str += '/ils-'+CORE_uri_encode( form.is_life_science.value );

  if( form.prefix.value )
	    q_str += '/pfx-'+CORE_uri_encode( form.prefix.value );  
  
  if( q_str == '' )
    q_str = '/all';

  if( form.col.value != project_default_columns )
    q_str += '/col-'+CORE_uri_encode( form.col.value );
  
  if( form.remember.checked )
	    q_str += '/lay-'+form.remember.value;  

  Dom.goto( 'projects'+q_str );
}

//------------------------
function make_contact_query( )
{
  var form = Dom.get( 'f_contact' );

  var q_str = '';

  if( form.source_id.value )
    q_str += '/id-'+CORE_uri_encode( form.source_id.value );

  if( form.first_name.value )
    q_str += '/fn-'+CORE_uri_encode( form.first_name.value );

  if( form.last_name.value )
    q_str += '/ln-'+CORE_uri_encode( form.last_name.value );

  if( form.title.value )
    q_str += '/t-'+CORE_uri_encode( form.title.value );

  if( form.org_search.value )
    q_str += '/org-'+CORE_uri_encode( form.org_search.value );

  if( form.notes.value )
    q_str += '/bg-'+CORE_uri_encode( form.notes.value );

  if( form.email1.value )
    q_str += '/e-'+CORE_uri_encode( form.email1.value );

  if( form.city.value )
    q_str += '/cit-'+CORE_uri_encode( form.city.value );
    
  if( form.zipcode.value )
    q_str += '/zip-'+CORE_uri_encode( form.zipcode.value );

  if( form.state.value != '' )
    q_str += '/st-'+CORE_uri_encode( form.state.value );

  if( form.country.value != '' )
    q_str += '/co-'+CORE_uri_encode( form.country.value );

  if( form.phone1.value )
    q_str += '/ph-'+CORE_uri_encode( form.phone1.value.replace( /[^x\d]/gi, '' ) );

  //if( form.type1.value != '' )
  //  q_str += '/typ-'+CORE_uri_encode( form.type1.value );

  if( form.is_source.value != '2' )
    q_str += '/src-'+CORE_uri_encode( form.is_source.value );

  if( form.recontact.value != '2' )
    q_str += '/rc-'+CORE_uri_encode( form.recontact.value );

  if( form.specialty1.value != '' )
    q_str += '/ta-'+CORE_uri_encode( form.specialty1.value );

  if( form.degree1.value != '' )
    q_str += '/deg-'+CORE_uri_encode( form.degree1.value );
    
  if( form.project_list.value != '' )
    q_str += '/prl-'+CORE_uri_encode( form.project_list.value );    

  if( form.conference_list.value != '' )
	    q_str += '/cnf-'+CORE_uri_encode( form.conference_list.value );
  
  if( q_str == '' )
    q_str = '/all';

  if( form.col.value != contact_default_columns )
    q_str += '/col-'+CORE_uri_encode( form.col.value );
  
  if( form.remember.checked )
	    q_str += '/lay-'+form.remember.value;  

  Dom.goto( 'contacts'+q_str );
}

//------------------------
function make_interview_query( )
{
  var form = Dom.get( 'f_int' );

  var q_str = '';

  if( form.source_id.value )
    q_str += '/id-'+CORE_uri_encode( form.source_id.value );

  if( form.title.value )
    q_str += '/t-'+CORE_uri_encode( form.title.value );

  if( form.confidential.value )
    q_str += '/txt-'+CORE_uri_encode( form.confidential.value );

  if( form.city.value )
    q_str += '/cit-'+CORE_uri_encode( form.city.value );

  if( form.state.value != '' )
    q_str += '/st-'+CORE_uri_encode( form.state.value );

  if( form.country.value != '' )
    q_str += '/co-'+CORE_uri_encode( form.country.value );

  if( form.type1.value != '' )
    q_str += '/typ-'+CORE_uri_encode( form.type1.value );

  if( form.specialty1.value != '' )
    q_str += '/ta-'+CORE_uri_encode( form.specialty1.value );


  if( form.first_name.value )
    q_str += '/fn-'+CORE_uri_encode( form.first_name.value );

  if( form.last_name.value )
    q_str += '/ln-'+CORE_uri_encode( form.last_name.value );

  if( form.title.value )
    q_str += '/t-'+CORE_uri_encode( form.title.value );

  if( form.org_search.value )
    q_str += '/org-'+CORE_uri_encode( form.org_search.value );

  if( form.int_notes.value )
    q_str += '/bg-'+CORE_uri_encode( form.int_notes.value );

  if( form.specialty1.value != '' )
    q_str += '/ta-'+CORE_uri_encode( form.specialty1.value );

  if( form.select_date.value != '0' && form.select_date.value != '1' )
  {
    q_str += '/dr-'+CORE_uri_encode( form.select_date.value );
  }
  else
  {
    if( form.start.value != '' )
    {
      var date_obj = new Date( );
      if( !validate_date( form.start.value, 1, 1, date_obj ) )
        q_str += '/sd-'+CORE_uri_encode( printDate(date_obj, 2, '') );    
    }
    if( form.end.value != '' )
    {
      var date_obj = new Date( );
      if( !validate_date( form.end.value, 1, 1, date_obj ) )
        q_str += '/ed-'+CORE_uri_encode( printDate(date_obj, 2, '') );    
    }
  }

  if( form.project_list.value != '' )
    q_str += '/prl-'+CORE_uri_encode( form.project_list.value );

  if( form.conference_list.value != '' )
	    q_str += '/cnf-'+CORE_uri_encode( form.conference_list.value );
  
  if( form.analyst_list.value != '' )
    q_str += '/anl-'+CORE_uri_encode( form.analyst_list.value );

//  if( form.approaches.value != '' )
//    q_str += '/apr-'+CORE_uri_encode( form.approaches.value );

  if( form.is_activity1.value != '1' )
    q_str += '/ac-0';

  if( q_str == '' )
    q_str = '/all';

  if( form.col.value != interview_default_columns )
    q_str += '/col-'+CORE_uri_encode( form.col.value );

  if( form.remember.checked )
	    q_str += '/lay-'+form.remember.value;  
  
  Dom.goto( 'interviews'+q_str );
}

// -----------------------------------
function org_query( )
{
  var my_orgs = Dom.get('f_contact').elements['contact_orgs[]'];

  var my_org_list = [0];

  if( typeof my_orgs == "undefined" )
    return "&not_in=0";

  if( isset( my_orgs.value ) && is_uint( my_orgs.value ) )
    my_org_list[0] = my_orgs.value;
  else
  {
    for( var i = 0; i < my_orgs.length; i++ )
    {
      if( is_uint(my_orgs[i].value) )
        my_org_list[i] = my_orgs[i].value;
    }
  }

  my_org_list = my_org_list.join( ',' );

  var q_str = "&not_in=" + CORE_uri_encode( my_org_list );

  return q_str;
}

// -----------------------------------
function org_write_back( obj, i )
{

  eval( 'obj = '+obj );

  if( i == 'new' )
    org_list.add( [obj.string_raw, obj.string, 0, 1, '', 0, 0], 0 );
  else
    org_list.add( [obj.values[i].id, obj.values[i].val, 0, 1, '', 0, 0], 0 );

  Dom.get( org_search.id ).value = '';
}

// -----------------------------------
function org_update_value( obj, i, do_replace, change_class )
{
  field = org_search.id;

  eval( 'obj = '+obj );

  if( change_class && i !== 'new' ) 
  {
    Dom.removeClass( field, 'org_add' );
    Dom.addClass( field, 'org' );
  }
  else if( change_class )
  {
    Dom.removeClass( field, 'org' );
    Dom.addClass( field, 'org_add' );
  }


  if( !do_replace )
  {
    var regex = /(.*;[\s]*)?.*/
    var current_val = Dom.get( field ).value;

    current_val = (current_val + '').replace( regex, '$1' );

    Dom.get( field ).value = current_val + obj[i].raw + '; ';

    Dom.get( field ).focus();
  }
  else if( i !== 'new' )
    Dom.get( field ).value = obj[i].raw;

}

// -----------------------------------
// Special case for target
function org_target_update_value( obj, i, do_replace, change_class )
{
  field = org_search_target.id;

  eval( 'obj = '+obj );

  if( change_class && i !== 'new' ) 
  {
    Dom.removeClass( field, 'org_add' );
    Dom.addClass( field, 'org' );
  }
  else if( change_class )
  {
    Dom.removeClass( field, 'org' );
    Dom.addClass( field, 'org_add' );
  }


  if( !do_replace )
  {
    var regex = /(.*;[\s]*)?.*/
    var current_val = Dom.get( field ).value;

    current_val = (current_val + '').replace( regex, '$1' );

    Dom.get( field ).value = current_val + obj[i].raw + '; ';

    Dom.get( field ).focus();
  }
  else if( i !== 'new' )
    Dom.get( field ).value = obj[i].raw;

}

// -----------------------------------
function org_clear_value( )
{
  Dom.addClass( org_search.id, 'org_add' );
  Dom.removeClass( org_search.id, 'org' );
  if( !Dom.get(org_search.id).value )
    Dom.removeClass( org_search.id, 'org_add' );
}

// -----------------------------------
function prj_query( )
{
  var my_prjs = Dom.get('f_contact').elements['contact_prjs[]'];

  var my_prj_list = [0];

  if( typeof my_prjs == "undefined" )
    return "&not_in=0";

  if( isset( my_prjs.value ) && is_uint( my_prjs.value ) )
    my_prj_list[0] = my_prjs.value;
  else
  {
    for( var i = 0; i < my_prjs.length; i++ )
    {
      if( is_uint(my_prjs[i].value) )
        my_prj_list[i] = my_prjs[i].value;
    }
  }

  my_prj_list = my_prj_list.join( ',' );

  var q_str = "&not_in=" + CORE_uri_encode( my_prj_list );

  return q_str;
}

// -----------------------------------
function prj_write_back( obj, i )
{

  eval( 'obj = '+obj );

  if( i == 'new' )
    prj_list.add( [obj.string_raw, obj.string, 0, 1, '', 0, 0], 0 );
  else
    prj_list.add( [obj.values[i].id, obj.values[i].val, 0, 1, '', 0, 0], 0 );

  Dom.get( prj_search.id ).value = '';
}

// -----------------------------------
function prj_update_value( obj, i, do_replace, change_class )
{
  field = prj_search.id;

  eval( 'obj = '+obj );

  if( change_class && i !== 'new' ) 
  {
    Dom.removeClass( field, 'prj_add' );
    Dom.addClass( field, 'prj' );
  }
  else if( change_class )
  {
    Dom.removeClass( field, 'prj' );
    Dom.addClass( field, 'prj_add' );
  }


  if( !do_replace )
  {
    var regex = /(.*;[\s]*)?.*/
    var current_val = Dom.get( field ).value;

    current_val = (current_val + '').replace( regex, '$1' );

    Dom.get( field ).value = current_val + obj[i].raw + '; ';

    Dom.get( field ).focus();
  }
  else if( i !== 'new' )
    Dom.get( field ).value = obj[i].raw;

}

// -----------------------------------
function prj_clear_value( )
{
  Dom.addClass( prj_search.id, 'prj_add' );
  Dom.removeClass( prj_search.id, 'prj' );
  if( !Dom.get(prj_search.id).value )
    Dom.removeClass( prj_search.id, 'prj_add' );
}

// -----------------------------------
function search_update_value( obj, i, clas, no_add )
{
  eval( 'field = '+obj+'.id' );

  eval( 'obj = '+obj+'.resp_obj.values' );

  if( i !== 'new' ) 
  {
    if( Dom.get( field+'_val' ) )
      Dom.get( field+'_val' ).value = obj[i].id;
    Dom.get( field ).value = obj[i].raw;
    Dom.removeClass( field, clas+'_add' );
    Dom.addClass( field, clas );
  }
  else
  {
    if( Dom.get( field+'_val' ) )
      Dom.get( field+'_val' ).value = '';
    Dom.removeClass( field, clas );
    if( !no_add )
      Dom.addClass( field, clas+'_add' );
  }
}

function search_clear_value( obj, clas, no_add )
{
  eval( 'field = '+obj+'.id' );

  if( Dom.get( field+'_val' ) )
    Dom.get( field+"_val" ).value = "";

  if( !no_add )
    Dom.addClass( field, clas+'_add' );
  Dom.removeClass( field, clas );
  if( !Dom.get(field).value )
    Dom.removeClass( field, clas+'_add' );
}

// -----------------------------------
function poc_update_value( obj, i )
{
  eval( 'obj = '+obj );

  if( i !== 'new' ) 
  {
    Dom.get( poc_search.id+'_val' ).value = obj[i].id;
    Dom.get( poc_search.id ).value = obj[i].raw;
    Dom.removeClass( poc_search.id, 'contact_add' );
    Dom.addClass( poc_search.id, 'contact' );
  }
  else
  {
    Dom.get( poc_search.id+'_val' ).value = '';
    Dom.removeClass( poc_search.id, 'contact' );
    Dom.addClass( poc_search.id, 'contact_add' );
  }
}

function poc_clear_value( )
{
  Dom.get(poc_search.id+"_val").value="";

  Dom.addClass( poc_search.id, 'contact_add' );
  Dom.removeClass( poc_search.id, 'contact' );
  if( !Dom.get(poc_search.id).value )
    Dom.removeClass( poc_search.id, 'contact_add' );
}


// -----------------------------------
function org_search_update_value( field, obj, i, do_replace )
{

  eval( 'obj = '+obj );
  eval( 'field = '+field );

  if( !do_replace )
  {
    var regex = /(.*;[\s]*)?.*/
    var current_val = Dom.get( field.id ).value;

    current_val = (current_val + '').replace( regex, '$1' );

    Dom.get( field.id ).value = current_val + obj[i].raw + '; ';

    Dom.get( field.id ).focus();
  }
  else
  {
    Dom.get( field.id ).value = obj[i].raw;
    Dom.get( field.id+'_id' ).value = obj[i].id;
  }
}


// -----------------------------------
function addFormText_org( obj, count, value, initial )
{
  var remove = 0;
  if( initial )
    remove = value[0];

  var is_primary = '';
  if( value[2] == 1 )
    is_primary = ' checked';

  var is_current = '';
  if( value[3] == 1 )
    is_current = ' checked';

  var clas = '';
  if( !is_uint(value[0]) )
    clas = '_add';

  var ret_val =
   '<div class="contact_org">' +
   ' ' +
   '  <input type="hidden" name="org_keys[]" id="f_contact-org_key'+count+'" value="'+count+'" />' +
   '  <input type="hidden" name="contact_orgs[]" id="f_contact-org'+count+'" value="'+value[0]+'" />' +
   '  <span class="org_name org'+clas+'">' + value[1] + '</span>' +

   ' <label for="f_contact-org_is_primary'+count+'" class="checkbox">' +
   '  <input type="radio" class="checkbox" name="org_is_primary" id="f_contact-org_is_primary'+count+'" value="'+count+'" '+is_primary+' />' +
   '  Primary</label>' +
   ' <label for="f_contact-org_is_current'+count+'" class="checkbox">' +
   '  <input type="checkbox" class="checkbox" name="org_is_current[]" id="f_contact-org_is_current'+count+'" value="'+count+'" '+is_current+' />' +
   '  Current</label>';


  if( !initial )
    ret_val += ' <a class="hilight img org_delete" onclick="'+obj+'.remove('+count+','+remove+')">Remove</a>';

  ret_val += SP_DIV+'  <div class="city"><label for="f_contact-city'+count+'">City:</label>' +
   '<input type="text" id="f_contact-city'+count+'" name="city[]" value="' + value[4] + '"/></div>' +
   '  <div class="state"><label for="f_contact-state'+count+'">State:</label>' +
   make_state_select_box( value[5], "f_contact-state'+count+'", "state[]" ) + '</div>' +
   '  <div class="country"><label for="f_contact-country'+count+'">Country:</label>' +
   make_country_select_box( value[6], "f_contact-country'+count+'", "country[]" ) + '</div>' +
   '  <div class="zipcode"><label for="f_contact-zipcode'+count+'">Zipcode:</label>' +
   '<input type="text" id="f_contact-zipcode'+count+'" name="zipcode[]" value="' + value[4] + '"/></div>';
  
  ret_val += SP_DIV+'</div>';

  return ret_val;
}

// -----------------------------------
function addFormText_prj( obj, count, value, initial )
{
  var remove = 0;
  if( initial )
    remove = value[0];

  var is_primary = '';
  if( value[2] == 1 )
    is_primary = ' checked';

  var is_current = '';
  if( value[3] == 1 )
    is_current = ' checked';

  var clas = '';
  if( !is_uint(value[0]) )
    clas = '_add';

  var ret_val =
   '<div class="contact_prj">' +
   ' ' +
   '  <input type="hidden" name="prj_keys[]" id="f_contact-prj_key'+count+'" value="'+count+'" />' +
   '  <input type="hidden" name="contact_prjs[]" id="f_contact-prj'+count+'" value="'+value[0]+'" />' +
   '  <span class="prj_name prj'+clas+'">' + value[1] + '</span>';

/*
   ' <label for="f_contact-prj_is_primary'+count+'" class="checkbox">' +
   '  <input type="radio" class="checkbox" name="prj_is_primary" id="f_contact-prj_is_primary'+count+'" value="'+count+'" '+is_primary+' />' +
   '  Primary</label>' +
   ' <label for="f_contact-prj_is_current'+count+'" class="checkbox">' +
   '  <input type="checkbox" class="checkbox" name="prj_is_current[]" id="f_contact-prj_is_current'+count+'" value="'+count+'" '+is_current+' />' +
   '  Current</label>';
*/

  if( !initial )
    ret_val += ' <a class="hilight img prj_delete" onclick="'+obj+'.remove('+count+','+remove+')">Remove</a>';

  /*
  ret_val += SP_DIV+'  <div class="city"><label for="f_contact-city'+count+'">City:</label>' +
   '<input type="text" id="f_contact-city'+count+'" name="city[]" value="' + value[4] + '"/></div>' +
   '  <div class="state"><label for="f_contact-state'+count+'">State:</label>' +
   make_state_select_box( value[5], "f_contact-state'+count+'", "state[]" ) + '</div>' +
   '  <div class="country"><label for="f_contact-country'+count+'">Country:</label>' +
   make_country_select_box( value[6], "f_contact-country'+count+'", "country[]" ) + '</div>';
  */
  ret_val += SP_DIV+'</div>';

  return ret_val;
}


// -----------------------------------
function addFormText_disporg( obj, count, value, initial )
{

  var ret_val =
   '<div class="contact_org">' +
   '  <span class="org_name org"><a href="organizations/view/'+value[0]+'">' + value[1] + '</a>'+
   '&nbsp;<a href="javascript:delete_org('+value[0]+')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a>' +
   '</span>';
  
  if( value[2] == 1 )
    ret_val +=
   ' <label class="img checkbox star">' +
   '  Primary</label>';
  else
   ret_val +=
   ' <label class="img checkbox">' +
   '  &nbsp;</label>';

  if( value[3] == 1 )
    ret_val += 
   ' <label class="checkbox">' +
   '  Current</label>';
  else
    ret_val += 
   ' <label class="checkbox">' +
   '  Former</label>';
  
  ret_val += SP_DIV+
   '  <div class="city_full"><span>' + value[4] + '</span>';
  if( value[5] && value[5] != '0' )
   ret_val += '<span>'+( value[4] != 0 ? ',' : '')+' ' + value[7][value[5]] + '</span>';
  if( value[6] && value[6] != '0' )
   ret_val += '<span>'+( (value[4] != 0 || value[5] != 0) ? ',' : '')+' ' + value[8][value[6]] + '</span>';

  ret_val += SP_DIV+
  '  <div class="zipcode"><span>' + value[9] + '</span>';
  
  ret_val += '</div>'+SP_DIV+'</div>';

  return ret_val;
}

// -----------------------------------
function addFormText_dispprj( obj, count, value, initial )
{

  var ret_val =
   '<div class="contact_prj">' +
   '  <span class="prj_name prj"><a href="projects/view/'+value[0]+'">' + value[1] + '</a>' +
   '&nbsp;<a href="javascript:delete_prj('+value[0]+')" title="delete"><img width="16" height="16" src="img/icons/trash.png"/></a>' +  
   '</span>';
  ret_val += SP_DIV+'</div>';

  return ret_val;
}

// -----------------------------------
function org_show( obj, text, clas, no_add, any_match )
{

  eval( 'obj = '+obj );

 //  var objs = Dom.get('tail_debug_text');
 //  objs.innerHTML += "<br/>" + text;

    // print the responses
    Dom.get( obj.id+'_popup' ).innerHTML = '';
    hide( obj.id+'_popup' );

    var exact_match = false;

    if( obj.resp_obj.values.length )
    {
      for( var i = 0; i < obj.resp_obj.values.length; i++ )
      {
        var value = obj.resp_obj.values[i];

        // for lack of better solution, split based on if first character is special char or not
        if( obj.resp_obj.string.substring(0, 1) == '&' )
        {
          eval( 'var my_regex = /(' + escape_regex( obj.resp_obj.string ) + ')/gi' );

          var stringy = value.val.replace( my_regex, '<b>$1</b>' );
        }
        else
        {
          eval( 'var my_regex = /(&[^;]+;)|(' + escape_regex( obj.resp_obj.string ) + ')/gi' );

          var stringy = value.val.replace( my_regex, '$1<b>$2</b>' );
        }

        eval( 'var my_regex = /^(' + escape_regex( obj.resp_obj.string ) + ')$/i' );

        if( value.val.match( my_regex ) )
          exact_match = true;
 
        var func = obj.me+'.write_back(\''+i+'\');';

        var extra = '';
        if( value.extra )
          extra = '&nbsp;&nbsp;<span class="small">( '+value.extra+' )</span>';

        Dom.get( obj.id+'_popup' ).innerHTML += '<a class="img_small '+clas+'" href="javascript:'+func+'">'+stringy+extra+'</a>';
      }
      show( obj.id+'_popup' );
    }

    if( obj.resp_obj.string != '' && !no_add && (!exact_match || any_match) )
    {
      
      Dom.get( obj.id+'_popup' ).innerHTML += '<a class="img_small '+clas+'_add gray" href="javascript:'+obj.me+'.write_back(\'new\');'+'">Create <b>'+obj.resp_obj.string+'</b></a>';

      show( obj.id+'_popup' );
    }

}

// -----------------------------------
function prj_show( obj, text, clas, no_add, any_match )
{

  eval( 'obj = '+obj );

 //  var objs = Dom.get('tail_debug_text');
 //  objs.innerHTML += "<br/>" + text;

    // print the responses
    Dom.get( obj.id+'_popup' ).innerHTML = '';
    hide( obj.id+'_popup' );

    var exact_match = false;

    if( obj.resp_obj.values.length )
    {
      for( var i = 0; i < obj.resp_obj.values.length; i++ )
      {
        var value = obj.resp_obj.values[i];

        // for lack of better solution, split based on if first character is special char or not
        if( obj.resp_obj.string.substring(0, 1) == '&' )
        {
          eval( 'var my_regex = /(' + escape_regex( obj.resp_obj.string ) + ')/gi' );

          var stringy = value.val.replace( my_regex, '<b>$1</b>' );
        }
        else
        {
          eval( 'var my_regex = /(&[^;]+;)|(' + escape_regex( obj.resp_obj.string ) + ')/gi' );

          var stringy = value.val.replace( my_regex, '$1<b>$2</b>' );
        }

        eval( 'var my_regex = /^(' + escape_regex( obj.resp_obj.string ) + ')$/i' );

        if( value.val.match( my_regex ) )
          exact_match = true;
 
        var func = obj.me+'.write_back(\''+i+'\');';

        var extra = '';
        if( value.extra )
          extra = '&nbsp;&nbsp;<span class="small">( '+value.extra+' )</span>';

        Dom.get( obj.id+'_popup' ).innerHTML += '<a class="img_small '+clas+'" href="javascript:'+func+'">'+stringy+extra+'</a>';
      }
      show( obj.id+'_popup' );
    }

    if( obj.resp_obj.string != '' && !no_add && (!exact_match || any_match) )
    {
      
      Dom.get( obj.id+'_popup' ).innerHTML += '<a class="img_small '+clas+'_add gray" href="javascript:'+obj.me+'.write_back(\'new\');'+'">Create <b>'+obj.resp_obj.string+'</b></a>';

      show( obj.id+'_popup' );
    }

}

// -----------------------------------
function search_all_show( obj, text )
{
  eval( 'obj = '+obj );

//   var objs = Dom.get('tail_debug_text');
//   objs.innerHTML += "<br/>" + text;

    // print the responses
    Dom.get( obj.id+'_popup' ).innerHTML = '';
    hide( obj.id+'_popup' );

    var exact_match = false;

    if( obj.resp_obj.values.length )
    {
      for( var i = 0; i < obj.resp_obj.values.length && i < 7; i++ )
      {
        var value = obj.resp_obj.values[i];
        var stringy = value.val;
        var patterns = obj.resp_obj.string.split( ' ' );

        for( var j = 0; j < patterns.length; j++ )
        {

          if( patterns[j] )
          {
            // for lack of better solution, split based on if first character is special char or not
            if( patterns[j].substring(0, 1) == '&' )
            {
              eval( 'var my_regex = /(' + escape_regex( patterns[j] ) + ')/gi' );

              stringy = stringy.replace( my_regex, '<b>$1</b>' );
            }
            else
            {
              eval( 'var my_regex = /(&[^;]+;)|(' + escape_regex( patterns[j] ) + ')/gi' );

              stringy = stringy.replace( my_regex, '$1<b>$2</b>' );
            }
          }
        }

        var path = 'contacts';
        if( value.class_name == 'org' )
          path = 'organizations';
        else if( value.class_name == 'proj' )
          path = 'projects';
        else if( value.class_name == 'interview' )
          path = 'interviews';

        var extra = '';
        if( value.extra )
          extra = '&nbsp;&nbsp;<span class="small">( '+value.extra+' )</span>';

        Dom.get( obj.id+'_popup' ).innerHTML += '<a class="img_small '+value.class_name+'" href="'+path+'/view/'+value.id+'">'+stringy+extra+'</a>';
      }
      show( obj.id+'_popup' );
    }

}


//---------------------------
function make_select_box( list, selected, s_id, s_name )
{
  ret_val = '<select id="'+s_id+'" name="'+s_name+'">';

  for( var key in list )
  {
    sel = '';
    if( selected == key )
      sel = ' selected'
    if( key != 'length' )
      ret_val += '<option value="'+key+'"'+sel+'>'+list[key]+'</option>';
  }

  ret_val += '</select>';

  return ret_val;
}

