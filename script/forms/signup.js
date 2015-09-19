function validateForm_f_user( action )
{
  if( action == 1 ) { return true; }
  var errorObj = { "str":"","code":0,"flag":false };
  hide("f_user_success");

  if( getElement("f_user-username") )
  { 
    var regexVal = /^[A-Z][A-Z0-9_-]{2,24}$/i;
    errorObj.code = validateRegex( document.getElementById("f_user-username").value, regexVal, 1);
    handleError( "f_user-username", "User Name", errorObj );
  }
  if( getElement("f_user-email") )
  {
    var regexVal = /^[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
    errorObj.code = validateRegex( document.getElementById("f_user-email").value, regexVal, 1 );
    handleError( "f_user-email", "Email Address", errorObj );
  }
  if( getElement("f_user-password") )
  {
    errorObj.code = validateString(document.getElementById("f_user-password").value);
    handleError( "f_user-password", "Password", errorObj );
  }
  
  if( errorObj.flag ) 
  {
    document.getElementById("f_user_error").innerHTML = errorObj.str;
    show("f_user_error");
    return false; 
  }
  hide("f_user_error");
  show("f_user_processing");
  return true;
}

function handleResponse_f_user( resObj )
{ 
  var errorObj = handle_response_generic( "f_user", resObj, 0 );
  if( resObj.act == 1 )
    return;
  
  if( errorObj.flag ) { }
  else if( !resObj.db ) { }
  else 
  {
     window.location="http://log.runsomemore.com/home.php?smg=0,3,1,1";
  }
}

var timeout_f_user;

function resetForm_f_user( )
{ 
  clearTimeout( timeout_f_user );
  hide_popup("f_user");
  error_border( "f_userusername", 0 );
  error_border( "f_useremail", 0 );
  error_border( "f_userpassword", 0 );
  document.getElementById( "f_useruser_id").value = 0;
  hide("f_user_error");
  hide("f_user_processing");
  hide("f_user_success");
  document.getElementById("f_user").reset();
  show("f_user");
}



// -----------------------------------
function addFormText_school( id, value )
{
  var ret_val =
   '<div>' +
   ' <input type="hidden" name="school_id[]" value="0">' +
   ' <label for="f_user-university'+id+'">University:</label>' +
   ' <input type="text" name="university[]" id="f_user-university'+id+'" maxlength="100" value="'+value[0]+'" />' +
   ' <a class="form_remove_link" href="javascript:removeFormArray(\'school\','+id+')"><strong>-Remove</strong> school</a>' +
   '</div>' +
   '<div>' +
   ' <label for="f_user-field_of_study'+id+'">Field(s) of Study:</label>' +
   ' <input type="text" name="field_of_study[]" id="f_user-field_of_study'+id+'" maxlength="100" value="'+value[1]+'" />' +
   '</div>'+
   '<div>'+
   ' <label for="f_user-school_type'+id+'">School Type:</label>'+
   ' <select id="f_user-school_type'+id+'" name="school_type[]" >'+
   '  <option value="1">Undergraduate</option><option value="2">Medical School</option><option value="3">Specialty School</option><option value="4">Fellowship</option>'+
   ' </select>'+
   '</div>'+
   '<div class="hr"><hr/></div>';
  return ret_val;
}

var form_school_id = 1;