<?

class CORE_Email
{
  var $to        = '';
  var $subject   = '';
  var $message   = '';
  var $headers   = "From: Project Portal Database <noreply@replaceme.com>";
//\r\nContent-Type: text/html; charset=\"iso-8859-1\"";

  // ----------------------------------------------------------------------------------------
  function CORE_Email( $to='' )
  {
    $this->to = $to;
  }

  // ----------------------------------------------------------------------------------------
  function set( $item, $value, $format=F_PHP )
  {
    $data = CORE_decode( $value, $format );

    $this->$item = $data;
  }

  // ----------------------------------------------------------------------------------------
  function create_message( $type, $data, $format=F_HTM )
  {
    $data = CORE_encode($data, $format);
    switch( $type )
    {
      case( 1 ): // signup
        break;

      case( 2 ): // forgot password
        $this->subject = 'Project Portal Database Password Reset Request';
        $body = 'Hi '.$data['firstname'].',

A new password has been generated for your Project Portal Database account.

You can log into your account here: '.PATH_WEB.'login using the following credentials:

    Username: '.$data['username'].'
    Password: '.$data['password'].'

Thanks,
The Project Portal Database Team';
        break;

      case( 3 ): // account imported
        $this->subject = 'Welcome to the Project Portal Database';
        $body = 'Hi '.$data['firstname'].',

An account has been set up for you at the Project Portal Database website.

You can log into your account here: '.PATH_WEB.'login using the following credentials:

    Username: '.$data['username'].'
    Password: '.$data['password'].'

Thanks,
The Project Portal Database Team';
        break;
    }

    //$this->message = $this->_add_frame( $body );
    $this->message = $body;
  }

  // ----------------------------------------------------------------------------------------
  function send( )
  {
    $message = wordwrap($this->message, 70);
    mail($this->to, $this->subject, $message, $this->headers);
  }

  // ----------------------------------------------------------------------------------------
  function _add_frame( $body )
  {

    $ret_val = '<table width="98%" border="0" cellspacing="0" cellpadding="40">
 <tbody>
 <tr>
  <td bgcolor="#f0f0f0" width="100%" style="font-family:\'lucida grande\', tahoma, verdana, arial, sans-serif">
   <table cellpadding="0" cellspacing="0" border="0" width="620">
    <tbody>
    <tr>
     <td style="padding:4px 8px;text-align:right"><a href="'.PATH_WEB.'" target="_blank"><img src="'.PATH_WEB.'/img/vop-logo_email.gif" alt="VOICE OF PHYSICIANS" width="102" height="30" border="0"/></a></td></tr>
    <tr>
     <td style="background-color:#ffffff;border-bottom:1px solid #961e1f;border-top:3px solid #961e1f;border-left:1px solid #b0b0b0;border-right:1px solid #b0b0b0;font-family:\'lucida grande\', tahoma, verdana, arial, sans-serif;padding:15px" valign="top">
      <table width="100%">
       <tbody>
       <tr>
        <td width="100%" style="font-size:12px;color:#303030;" valign="top" align="left">'.$body.'</td></tr></tbody>
      </table></td></tr>
    <tr>
     <td colspan="2" style="color:#8f8f90;padding:10px;font-size:11px;font-family:\'lucida grande\', tahoma, verdana, arial, sans-serif">
     This message was intended for <a href="mailto:'.$this->to.'" style="color:#961e1f" target="_blank">'.$this->to.'</a>. To unsubscribe, <a href="'.PATH_WEB.'login" style="color:#961e1f" target="_blank">click here</a>.
     <br/>
     To update your e-mail address or change your personal options, visit the My Account area at <a href="'.PATH_WEB.'" style="color:#961e1f" target="_blank">voiceofphysicians.com</a>.
     <br/>
     © 2010 - Voice of Physicians</td></tr></tbody>
   </table></td></tr></tbody>
</table>';

    return $ret_val;
  }

}
