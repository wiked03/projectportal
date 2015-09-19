<? //============================================================ ?>
<div id="user_email_popup" class="hidden">
 <div class="blur_background"><?=SP_DIV?></div>

 <div class="popup_frame">
  <div class="popup_window">
   <h2><img src="img/icons/email_go.png" class="icon"/>Email Interview</h2>
   <div class="content">
    <p>Select user to send interview to:</p>

<form name="f_user_email" id="f_user_email" method="post" action="<?=PATH_SELF?>">
  <input type="hidden" name="submit_form" value="user_email" />
  <input type="hidden" id="f_user_email-user_id" name="user_id" value="" />
  <input type="hidden" id="f_interview_id" name="interview_id" value="" />
  <?=SP_DIV?>

 <!-- 
  <select id="f_user_email-user_id" name="user_id">
<?
  $my_view = new View();
  $user_list = $my_view->get_list( 'active_users' );

  foreach( $user_list as $key => $val )
  {
    echo '<option value="'.$key.'">'.$val.'</option>';
  }

?>
</select>
 -->

<div class="custom_select" id="f_users">
   <input type="text" name="users" id="f_users_value" value="">
   <div class="input" id="f_users_input_outer">
      <div class="inner"><a href="javascript:void(0)" id="f_users_input">None Selected</a></div>
   </div>
   <div class="select scroll" id="f_users_select" style="display: none;">
      <div class="option" id="f_users_88" onclick="sel_users.update( '88' )"><span>Abby Komito</span></div>
      <div class="option" id="f_users_79" onclick="sel_users.update( '79' )"><span>Alex Porter </span></div>
      <div class="option" id="f_users_86" onclick="sel_users.update( '86' )"><span>Andres Portillo</span></div>
      <div class="option" id="f_users_97" onclick="sel_users.update( '97' )"><span>Andres Portillo</span></div>
      <div class="option" id="f_users_87" onclick="sel_users.update( '87' )"><span>Andres Test</span></div>
      <div class="option" id="f_users_95" onclick="sel_users.update( '95' )"><span>Benjamin McLain</span></div>
      <div class="option" id="f_users_73" onclick="sel_users.update( '73' )"><span>Chris Renn</span></div>
      <div class="option" id="f_users_105" onclick="sel_users.update( '105' )"><span>Cliff Hilton</span></div>
      <div class="option" id="f_users_113" onclick="sel_users.update( '113' )"><span>Corey Wood</span></div>
      <div class="option" id="f_users_101" onclick="sel_users.update( '101' )"><span>Daniel Wainright</span></div>
      <div class="option" id="f_users_24" onclick="sel_users.update( '24' )"><span>Dave Richards</span></div>
      <div class="option" id="f_users_106" onclick="sel_users.update( '106' )"><span>Devin Langan</span></div>
      <div class="option" id="f_users_92" onclick="sel_users.update( '92' )"><span>Drew Lewis</span></div>
      <div class="option" id="f_users_31" onclick="sel_users.update( '31' )"><span>Elissa Kaywork</span></div>
      <div class="option" id="f_users_2" onclick="sel_users.update( '2' )"><span>Heath Gross</span></div>
      <div class="option" id="f_users_30" onclick="sel_users.update( '30' )"><span>Heather Dyer</span></div>
      <div class="option" id="f_users_33" onclick="sel_users.update( '33' )"><span>Heather Rumancik</span></div>
      <div class="option" id="f_users_91" onclick="sel_users.update( '91' )"><span>Jeff Wilson</span></div>
      <div class="option" id="f_users_77" onclick="sel_users.update( '77' )"><span>Jenniffer Paz </span></div>
      <div class="option" id="f_users_38" onclick="sel_users.update( '38' )"><span>Jeremy Hunt</span></div>
      <div class="option" id="f_users_93" onclick="sel_users.update( '93' )"><span>Jesse Leightenheimer</span></div>
      <div class="option" id="f_users_3" onclick="sel_users.update( '3' )"><span>Josh Thornburg</span></div>
      <div class="option" id="f_users_109" onclick="sel_users.update( '109' )"><span>Katerina Fisher</span></div>
      <div class="option" id="f_users_27" onclick="sel_users.update( '27' )"><span>Kristin Baldauf</span></div>
      <div class="option" id="f_users_112" onclick="sel_users.update( '112' )"><span>Kyle Miller</span></div>
      <div class="option" id="f_users_94" onclick="sel_users.update( '94' )"><span>Lee Cohen</span></div>
      <div class="option" id="f_users_75" onclick="sel_users.update( '75' )"><span>Matthew Crawford</span></div>
      <div class="option" id="f_users_111" onclick="sel_users.update( '111' )"><span>Minde Willardsen</span></div>
      <div class="option" id="f_users_4" onclick="sel_users.update( '4' )"><span>Rachael Hunt</span></div>
      <div class="option" id="f_users_114" onclick="sel_users.update( '114' )"><span>Sameen Syed</span></div>
      <div class="option" id="f_users_96" onclick="sel_users.update( '96' )"><span>Scott Lucas</span></div>
      <div class="option" id="f_users_107" onclick="sel_users.update( '107' )"><span>Snehal Jadey</span></div>
      <div class="option" id="f_users_25" onclick="sel_users.update( '25' )"><span>Steve Hernan</span></div>
      <div class="option" id="f_users_74" onclick="sel_users.update( '74' )"><span>Susanne Radke</span></div>
      <div class="option" id="f_users_103" onclick="sel_users.update( '103' )"><span>Tamer Sharkawy</span></div>
      <div class="option" id="f_users_76" onclick="sel_users.update( '76' )"><span>Tracey Gratch</span></div>
      <div class="option" id="f_users_108" onclick="sel_users.update( '108' )"><span>Varun Naik</span></div>
   </div>
</div>

 <br/>
 <br/>
</form>

<a onclick="Dom.get('f_user_email').submit()" class="button_small"><span>Send</span></a>

<a onclick="hide('user_email_popup')" class="button_small"><span>Cancel</span></a>

<?=SP_DIV?>
   </div>
  </div>
 </div>
</div>

<script type="text/javascript">
var sel_users = new Select_multi( 'sel_users', 'f_users' );
sel_users.clear_all = '';
sel_users.default_text = 'None Selected';
sel_users.item_text = 'users';
</script>