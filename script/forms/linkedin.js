
// Once we have an authorization, fetch the user's profile via API
function onLinkedInAuth( )
{
  IN.API.Profile("me")
    .fields("firstName", "lastName", "industry", "educations")
    .result( function(r) {setProfile(r);} )
    .error( function(e) {alert("something broke " + e.toSource());} );
}

// Display basic profile information inside the page
function setProfile( result )
{
  var user = result.values[0];

  if( userFormPage == 1 )
  {
    getElement( 'f_user-firstname' ).value = user.firstName;
    getElement( 'f_user-lastname' ).value = user.lastName;
  }
  else if( userFormPage == 2 )
  {
    var i;

    for( i = 0; i < user.educations._total; i++ )
    {
      if( i == 0 )
        remove_school(0);

      var school = user.educations.values[i];
      add_school( school.schoolName, school.fieldOfStudy );
    }
  }
}

