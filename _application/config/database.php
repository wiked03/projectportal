<?

$session['domain']  = false;
$session['path']    = '/';
$session['secure']  = false;
$session['timeout'] = 3600; // 3600 = 1 hr, 31536000 = 1 year
$session['name']    = 'crm_session';

$active_db = 'test';

$db['test']['name']   = 'project_portal_db';
$db['test']['user']   = 'testuser1';
$db['test']['passwd'] = 'testtest1';
$db['test']['host']   = 'localhost';


$db['remote_test']['name']   = 'projectportaldb';
$db['remote_test']['user']   = 'projectportaldb';
$db['remote_test']['passwd'] = '';
$db['remote_test']['host']   = '';
//$db['default']['cookiename']   = 'vop_cookie';

$db['remote']['name']   = '';
$db['remote']['user']   = '';
$db['remote']['passwd'] = '';
$db['remote']['host']   = '';

