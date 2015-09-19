<?


// Program Customization
$page['program_name']       = 'Project Portal';
$page['program_title']      = 'Project Portal';
$page['program_version']    = '1.0';
$page['copy_date']  = '2015';
$page['copy_owner'] = 'Some Guy';

// Program include switches
$page['g_analytics']= false;

// API keys
$page['keys']['g_maps'] = '';


/* ---- Localization Settings --- */
// F_DATE_USA, F_DATE_EUR, F_DATE_INT
define( "G_DATE_FORMAT", F_DATE_USA );

define( "G_DATE_SEPARATOR", '/' );

define( "G_DATE_LEADING_ZERO", false );

//-------------------------------------------------------------------------------------------
// Page Customizations
//-------------------------------------------------------------------------------------------

// Meta tags
$page['meta'] = 
  '<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   <meta name="author" content="Carl Wennerholm" />
   <meta http-equiv="Pragma" content="no-cache" />';



$page['menu'][1][] = array( 'name' => 'Login',          'link' => 'login' );


$page['menu'][2][] = array( 'name' => 'Home',         'link' => 'home' );
$page['menu'][2][] = array( 'name' => 'Sources',      'link' => 'contacts' );
#$page['menu'][2][] = array( 'name' => 'Interviews',   'link' => 'interviews' );
$page['menu'][2][] = array( 'name' => 'Organizations','link' => 'organizations' );

$page['menu'][3][] = array( 'name' => 'Home',         'link' => 'home' );
$page['menu'][3][] = array( 'name' => 'Sources',      'link' => 'contacts' );
#$page['menu'][3][] = array( 'name' => 'Interviews',   'link' => 'interviews' );
$page['menu'][3][] = array( 'name' => 'Organizations','link' => 'organizations' );
$page['menu'][3][] = array( 'name' => 'Projects',     'link' => 'projects' );

$page['menu'][5][] = array( 'name' => 'Home',         'link' => 'home' );
$page['menu'][5][] = array( 'name' => 'Sources',      'link' => 'contacts' );
#$page['menu'][5][] = array( 'name' => 'Interviews',   'link' => 'interviews' );
$page['menu'][5][] = array( 'name' => 'Organizations','link' => 'organizations' );
$page['menu'][5][] = array( 'name' => 'Projects',     'link' => 'projects' );
#$page['menu'][5][] = array( 'name' => 'Contractors',  'link' => 'admin/contractors' );
#$page['menu'][5][] = array( 'name' => 'Expenses',     'link' => 'admin/expenses' );
$page['menu'][5][] = array( 'name' => 'Users',        'link' => 'admin/users' );
$page['menu'][5][] = array( 'name' => 'Conferences',  'link' => 'admin/conferences' );
$page['menu'][5][] = array( 'name' => 'Activity',  'link' => 'admin/userlogs' );

$page['style'][] = 'style/style_main.css';
$page['style'][] = 'style/style_layout.css';
$page['style'][] = 'style/style_colors.css';
$page['style'][] = 'style/style_pp.css';
$page['style'][] = 'style/style_pp_icons.css';

$page['script'][] = 'script/common.js';

$page['head'] = 
'   <!--[if lt IE 7.]>
     <script defer type="text/javascript" src="script/pngfix.js"></script>
   <![endif]-->';

