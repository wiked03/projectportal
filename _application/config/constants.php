<?

// Error codes
define( "E_OK",                   0 );
define( "E_ENTRY_REQUIRED",       1 );
define( "E_INVALID_FORMAT",       2 );
define( "E_SELECTION_REQUIRED",   3 );
define( "E_INVALID_DATE",         4 );
define( "E_OUT_OF_RANGE",         5 );
define( "E_VALUE_NOT_UNIQUE",     6 );
define( "E_USER_NO_EMAIL",        7 );
define( "E_USER_NO_USERNAME",     8 );
define( "E_USER_WRONG_PASSWORD",  9 );
define( "E_FILE_NOT_FOUND",      10 );

define( "E_PWD_TOO_SHORT",      					 11 );
define( "E_PWD_DONT_CONTAIN_NUMBERS",      			 12 );
define( "E_PWD_DONT_CONTAIN_LOWERCASE_LETTERS",      13 );
define( "E_PWD_DONT_CONTAIN_UPPERCASE_LETTERS",      14 );

define( "V_REQUIRED",             1 );
define( "V_REGEX",                2 );
define( "V_DATE",                 3 );
define( "V_PHONE",                4 );
define( "V_EMAIL",                5 );
define( "V_UNIQUE",               6 );


// Defines
define( "F_PHP",            1 );
define( "F_HTM",            2 );
define( "F_SQL",            3 );
define( "F_TIMESTAMP",      4 );
define( "F_ALT",            5 );
define( "F_FORM",           6 );
define( "F_URI",            7 );
define( "F_SQL2",           8 );

define( "F_UNIT_METRIC",    0 );  
define( "F_UNIT_CUSTOMARY", 1 );

define( "F_DATE_USA",       0 );  
define( "F_DATE_EUR",       1 );
define( "F_DATE_INT",       2 );
define( "F_DATE_SQL",       3 );
define( "F_DATE_TIMESTAMP", 4 );
define( "F_DATE_HTM",       5 );

define( "F_DATE_SUNDAY",    0 );  
define( "F_DATE_MONDAY",    1 );

define( "F_TIME_12HR",      0 );
define( "F_TIME_24HR",      1 );

define( "F_TIME",           0 );
define( "F_DURATION",       1 );

define( "F_DURATION_STYLE1",0 );
define( "F_DURATION_STYLE2",1 );

define( "F_DURATION_MIN",   0 );
define( "F_DURATION_SEC",   1 );
define( "F_DURATION_HR",    2 );

define( "F_NUM_INTEGER",    0 );
define( "F_NUM_FLOAT",      1 );
define( "F_NUM_CURRENCY",   2 );
define( "F_NUM_UINT",       3 );
define( "F_NUM_INT",        0 );


define('S',     "&nbsp;");
define('SP_DIV','<div class="spacer">&nbsp;</div>');
define('HR',    '<div class="hr"><hr/></div>');
define('N',     "<br/>\n");
define('n',     "\n");
define('X',     NULL);



$REGEX['date'][F_DATE_INT] = '/^(((19|20)?\d\d)[\-|* \/.]?)?(0?[1-9]|1[012])[\-|* \/.]?(0?[1-9]|[12][0-9]|3[01])$/';
$REGEX['date'][F_DATE_USA] = '/^(0?[1-9]|1[012])[\-|* \/.](0?[1-9]|[12][0-9]|3[01])([\-|* \/.]((19|20)?\d\d))?$/';
$REGEX['date'][F_DATE_EUR] = '/^(0?[1-9]|[12][0-9]|3[01])[\-|* \/.](0?[1-9]|1[012])([\-|* \/.]((19|20)?\d\d))?$/';

$REGEX['date_replace'][F_DATE_INT] = '$m = (int)"${4}"; $d = (int)"${5}"; $y = (int)"${2}";';
$REGEX['date_replace'][F_DATE_USA] = '$m = (int)"${1}"; $d = (int)"${2}"; $y = (int)"${4}";';
$REGEX['date_replace'][F_DATE_EUR] = '$m = (int)"${2}"; $d = (int)"${1}"; $y = (int)"${4}";';

$REGEX['phone'] = '/^(?:[\s]*[\+]?[\s]*1[-. ]*)?(?:[-.( ]*([0-9]{3})[-.) ]*)?([0-9]{3})[-. ]*([0-9]{4})[\s]*(?:[\D]*(x)[\D]*([0-9]+))?[\s]*$/i'; // US
//$REGEX['phone2'] = '!^\+[0-9]{1,3}\.[0-9]{4,14}$!';  //Int +CCC.EEEEEEEEEEEEEE

$REGEX['phone_replace'][0] = '${1}${2}${3}';
$REGEX['phone_replace'][1] = '${5}';

$REGEX['email'] = '/^[\s]*([a-z0-9._%-]+@[a-z0-9.-]+\.[a-z]{2,4})[\s]*$/i';

$REGEX['email_replace'][0] = '${1}';
$REGEX['email_replace'][1] = '<a href="mailto:${1}">${1}</a>';

$LANG = array();
$LANG['countries'] = array( "US"=>"United States","AF"=>"Afghanistan","AL"=>"Albania","DZ"=>"Algeria","AD"=>"Andorra","AO"=>"Angola","AG"=>"Antigua and Barbuda","AR"=>"Argentina","AM"=>"Armenia","AU"=>"Australia","AT"=>"Austria","AZ"=>"Azerbaijan","BS"=>"Bahamas, The","BH"=>"Bahrain","BD"=>"Bangladesh","BB"=>"Barbados","BY"=>"Belarus","BE"=>"Belgium","BZ"=>"Belize","BJ"=>"Benin","BT"=>"Bhutan","BO"=>"Bolivia","BA"=>"Bosnia and Herzegovina","BW"=>"Botswana","BR"=>"Brazil","BN"=>"Brunei","BG"=>"Bulgaria","BF"=>"Burkina Faso","BI"=>"Burundi","KH"=>"Cambodia","CM"=>"Cameroon","CA"=>"Canada","CV"=>"Cape Verde","CF"=>"Central African Republic","TD"=>"Chad","CL"=>"Chile","CN"=>"China, People's Republic of","CO"=>"Colombia","KM"=>"Comoros","CD"=>"Congo, (Congo - Kinshasa)","CG"=>"Congo, (Congo - Brazzaville)","CR"=>"Costa Rica","CI"=>"Cote d'Ivoire (Ivory Coast)","HR"=>"Croatia","CU"=>"Cuba","CY"=>"Cyprus","CZ"=>"Czech Republic","DK"=>"Denmark","DJ"=>"Djibouti","DM"=>"Dominica","DO"=>"Dominican Republic","EC"=>"Ecuador","EG"=>"Egypt","SV"=>"El Salvador","GQ"=>"Equatorial Guinea","ER"=>"Eritrea","EE"=>"Estonia","ET"=>"Ethiopia","FJ"=>"Fiji","FI"=>"Finland","FR"=>"France","GA"=>"Gabon","GM"=>"Gambia, The","GE"=>"Georgia","DE"=>"Germany","GH"=>"Ghana","GR"=>"Greece","GD"=>"Grenada","GT"=>"Guatemala","GN"=>"Guinea","GW"=>"Guinea-Bissau","GY"=>"Guyana","HT"=>"Haiti","HN"=>"Honduras","HU"=>"Hungary","IS"=>"Iceland","IN"=>"India","ID"=>"Indonesia","IR"=>"Iran","IQ"=>"Iraq","IE"=>"Ireland","IL"=>"Israel","IT"=>"Italy","JM"=>"Jamaica","JP"=>"Japan","JO"=>"Jordan","KZ"=>"Kazakhstan","KE"=>"Kenya","KI"=>"Kiribati","KP"=>"Korea, North","KR"=>"Korea, South","KW"=>"Kuwait","KG"=>"Kyrgyzstan","LA"=>"Laos","LV"=>"Latvia","LB"=>"Lebanon","LS"=>"Lesotho","LR"=>"Liberia","LY"=>"Libya","LI"=>"Liechtenstein","LT"=>"Lithuania","LU"=>"Luxembourg","MK"=>"Macedonia","MG"=>"Madagascar","MW"=>"Malawi","MY"=>"Malaysia","MV"=>"Maldives","ML"=>"Mali","MT"=>"Malta","MH"=>"Marshall Islands","MR"=>"Mauritania","MU"=>"Mauritius","MX"=>"Mexico","FM"=>"Micronesia","MD"=>"Moldova","MC"=>"Monaco","MN"=>"Mongolia","ME"=>"Montenegro","MA"=>"Morocco","MZ"=>"Mozambique","MM"=>"Myanmar (Burma)","NA"=>"Namibia","NR"=>"Nauru","NP"=>"Nepal","NL"=>"Netherlands","NZ"=>"New Zealand","NI"=>"Nicaragua","NE"=>"Niger","NG"=>"Nigeria","NO"=>"Norway","OM"=>"Oman","PK"=>"Pakistan","PW"=>"Palau","PA"=>"Panama","PG"=>"Papua New Guinea","PY"=>"Paraguay","PE"=>"Peru","PH"=>"Philippines","PL"=>"Poland","PT"=>"Portugal","QA"=>"Qatar","RO"=>"Romania","RU"=>"Russia","RW"=>"Rwanda","KN"=>"Saint Kitts and Nevis","LC"=>"Saint Lucia","VC"=>"Saint Vincent and the Grenadines","WS"=>"Samoa","SM"=>"San Marino","ST"=>"Sao Tome and Principe","SA"=>"Saudi Arabia","SN"=>"Senegal","RS"=>"Serbia","SC"=>"Seychelles","SL"=>"Sierra Leone","SG"=>"Singapore","SK"=>"Slovakia","SI"=>"Slovenia","SB"=>"Solomon Islands","SO"=>"Somalia","ZA"=>"South Africa","ES"=>"Spain","LK"=>"Sri Lanka","SD"=>"Sudan","SR"=>"Suriname","SZ"=>"Swaziland","SE"=>"Sweden","CH"=>"Switzerland","SY"=>"Syria","TW"=>"Taiwan","TJ"=>"Tajikistan","TZ"=>"Tanzania","TH"=>"Thailand","TL"=>"Timor-Leste (East Timor)","TG"=>"Togo","TO"=>"Tonga","TT"=>"Trinidad and Tobago","TN"=>"Tunisia","TR"=>"Turkey","TM"=>"Turkmenistan","TV"=>"Tuvalu","UG"=>"Uganda","UA"=>"Ukraine","AE"=>"United Arab Emirates","GB"=>"United Kingdom","UY"=>"Uruguay","UZ"=>"Uzbekistan","VU"=>"Vanuatu","VA"=>"Vatican City","VE"=>"Venezuela","VN"=>"Vietnam","YE"=>"Yemen","ZM"=>"Zambia","ZW"=>"Zimbabwe" 
 );

$LANG['states'] = array( "AL"=>"Alabama","AK"=>"Alaska","AZ"=>"Arizona","AR"=>"Arkansas","CA"=>"California","CO"=>"Colorado","CT"=>"Connecticut","DE"=>"Delaware","DC"=>"District of Columbia","FL"=>"Florida","GA"=>"Georgia","GU"=>"Guam","HI"=>"Hawaii","ID"=>"Idaho","IL"=>"Illinois","IN"=>"Indiana","IA"=>"Iowa","KS"=>"Kansas","KY"=>"Kentucky","LA"=>"Louisiana","ME"=>"Maine","MD"=>"Maryland","MA"=>"Massachusetts","MI"=>"Michigan","MN"=>"Minnesota","MS"=>"Mississippi","MO"=>"Missouri","MT"=>"Montana","NE"=>"Nebraska","NV"=>"Nevada","NH"=>"New Hampshire","NJ"=>"New Jersey","NM"=>"New Mexico","NY"=>"New York","NC"=>"North Carolina","ND"=>"North Dakota","OH"=>"Ohio","OK"=>"Oklahoma","OR"=>"Oregon","PA"=>"Pennsylvania","PR"=>"Puerto Rico","RI"=>"Rhode Island","SC"=>"South Carolina","SD"=>"South Dakota","TN"=>"Tennessee","TX"=>"Texas","UT"=>"Utah","VT"=>"Vermont","VA"=>"Virginia","WA"=>"Washington","WV"=>"West Virginia","WI"=>"Wisconsin","WY"=>"Wyoming" 
 );

foreach( $LANG['states'] as $key => $val )
  $LANG['states_short'][$key] = $key;

$LANG['errors'][E_ENTRY_REQUIRED]     = "Please enter a value for '%NAME%'.";
$LANG['errors'][E_INVALID_FORMAT]     = "The value for '%NAME%' is improperly formatted.";
$LANG['errors'][E_SELECTION_REQUIRED] = "Please select a value for '%NAME%'.";
$LANG['errors'][E_INVALID_DATE]       = "Please enter a valid date for '%NAME%'.";
$LANG['errors'][E_OUT_OF_RANGE]       = "The value for '%NAME%' is out of range.";
$LANG['errors'][E_VALUE_NOT_UNIQUE]   = "Sorry, that %NAME% is already in use. Please enter a different one.";

$LANG['errors'][E_PWD_TOO_SHORT]    				  = "Password is too short";
$LANG['errors'][E_PWD_DONT_CONTAIN_NUMBERS]     	  = "Password does not contain numbers";
$LANG['errors'][E_PWD_DONT_CONTAIN_LOWERCASE_LETTERS] = "Password does not contain lower-case letters";
$LANG['errors'][E_PWD_DONT_CONTAIN_UPPERCASE_LETTERS] = "Password does not contain capital letters";

//----------------------------------------------------
//sources

$LANG['source_types'] = array( 
1=>"Corporate Business", 
2=>"Clinical / R&D", 
13=>'Customer Service',
21=>'Customer Support',
16=>'Distributor', 
22=>'Engineering & Technical',
3=>"Financial", 
24=>"Finance & Legal",
4=>"Government", 
5=>"Investigator", 
14=>'Investor Relations', 
6=>"Legal", 
17=>'Manufacturer', 
20=>'Marketing',
7=>"Media", 
8=>"Non-Profit", 
23=>'Operations',
10=>"Payor",
9=>"Pharmacy", 
18=>'Physician', 
11=>"Sales", 
12=>"Vendor", 
15=>'Other');

$LANG['source_types_short'] = array( 
1=>"COR", 
2=>"CLI", 
13=>'CSV', 
21=>'CUS', 
16=>'DST',
22=>'ENG',
3=>"FIN",
24=>"FLG",
4=>"GOV", 
5=>"INV", 
14=>'INR', 
6=>"LEG", 
17=>'MAN',
20=>'MKT',
7=>"MED", 
8=>"NPR",
23=>'OPT', 
10=>"PAY", 
9=>"PHA", 
18=>'PHY',
11=>"SAL", 
12=>"VEN", 
15=>'OTH');

$LANG['source_types_full'] = array( 
1=>"COR: Corporate Business", 
2=>"CLI: Clinical / R&D", 
13=>'CSV: Customer Service',
21=>'CUS: Customer Support',  
16=>'DST: Distributor', 
22=>'ENG: Engineering & Technical',
3=>"FIN: Financial",
24=>"FLG: Finance & Legal", 
4=>"GOV: Government", 
5=>"INV: Investigator", 
14=>'INR: Investor Relations',
6=>"LEG: Legal", 
17=>'MAN: Manufacturer', 
20=>'MKT: Marketing', 
7=>"MED: Media", 
8=>"NPR: Non-Profit",
23=>'OPT: Operations',  
10=>"PAY: Payor", 
9=>"PHA: Pharmacy", 
18=>'PHY: Physician', 
11=>"SAL: Sales",
12=>"VEN: Vendor",  
15=>'OTH: Other');

$LANG['contact_types'] = array( 1=>'Source', 0=>'Lead', 3=>'Personal Source', 4=>'Contractor', );

$LANG['reliability'] = array( 10=>"n/a", 4=>"A: Completely Reliable", 3=>"B: Usually Reliable", 2=>"C: Not Usually Reliable", 1=>"D: Unreliable" );

$LANG['reliability_short'] = array( 10=>"-", 4=>"A", 3=>"B", 2=>"C", 1=>"D" );

$LANG['degrees'] = array( 10=>'n/a', 1=>'PhD', 2=>'RPh', 3=>'PharmD', 4=>'MD', 5=>'DMD', 6=>'RN', 7=>'MPH', 8=>'DO' );

$LANG['salutations']   = array( 10=>' ', 1=>'Dr.', 2=>'Mr.', 3=>'Mrs.', 4=>'Ms.', 5=>'Miss' );

$LANG['user_types'] = array( 2=>'Analyst', 3=>'Lead Analyst', 5=>'Administrator' );

$LANG['phone_types'] = array( 1=>'Mobile', 2=>'Office', 3=>'Home' );

$LANG['email_types'] = array( 1=>'Company', 2=>'Personal' );

$LANG['industries'] = array( 1000=>'n/a',
                              1=>"Consumer",
                              2=>"Manufacturing & Retail",
                              3=>"Financial",
                              4=>"Professional & Consulting Services",
                              5=>"Energy",
                              6=>"Public Sector",
                              7=>"Technology",
                              8=>"Media & Telecommunications",
                              9=>"Travel",
                              10=>"Transport & Logistics",
);

# max 128
$LANG['specialties'] = array( 1000=>'n/a',
                              //1=>"Acupuncture",
                              //2=>"Addiction Medicine",
                              //3=>"Adolescent Medicine",
                              //4=>"Aerospace Medicine",
                              5=>"Allergy",
                              6=>"Anesthesiology",
                              7=>"Audiology",
                              124=>"Auto Immune",
                              122=>"Biosimilars",
                              127=>"Biosurgery",
                              //8=>"Bariatrics",
                              //9=>"Cardiac Electrophysiology",
                              //10=>"Cardio Thoracic Surgery",
                              11=>"Cardiology",
                              //12=>"Cardiovascular Disease",
                              //13=>"Cardiovascular Surgery",
                              //14=>"Chiropractic",
                              //15=>"Immunology",
                              //16=>"Clinical Pathology",
                              //17=>"Colon/Rectal Surgery",
                              //18=>"Critical Care",
                              //19=>"Cytopathology",
                              20=>"Dermatology",
                              120=>"Devices",
                              //21=>"Dermatopathology",
                              22=>"Diabetes",
                              125=>"Diagnostics",
                              //23=>"Diagnostic Radiology",
                              24=>"Emergency Medicine",
                              25=>"Endocrinology",
                              //26=>"Endovascular Surgical Neuroradiology",
                              //27=>"Family Practice",
                              28=>"Gastroenterology",
                              119=>"Generics",
                              //29=>"General Practice",
                              //30=>"General Surgery",
                              //31=>"Genetics",
                              //32=>"Geriatrics",
                              33=>"Gynecology",
                              //34=>"Gynecology Oncology",
                              //35=>"Hand Surgery",
                              //36=>"Head and Neck Surgery",
                              37=>"Hematology",
                              38=>"Hematology/Oncology",
                              //39=>"Hepatology",
                              //40=>"Holistic Medicine",
                              //41=>"Hospitalist",
                              42=>"Immunology",
                              43=>"Infectious Disease",
                              117=>"Insurance",
                              //44=>"Internal Medicine",
                              //45=>"Interventional Cardiology",
                              //46=>"Legal Medicine",
                              //47=>"Medical Oncology",
                              //48=>"Medical Toxicology",
                              49=>"Neonatology",
                              50=>"Nephrology",
                              51=>"Neurology",
                              //52=>"Neuroradiology",
                              //53=>"Neurosurgery",
                              //54=>"Nuclear Cardiology",
                              //55=>"Nuclear Medicine",
                              56=>"Nutrition",
                              //57=>"Obstetrics",
                              58=>"Obstetrics/Gynecology",
                              //59=>"Occupational Medicine",
                              60=>"Oncology",
                              61=>"Ophthalmology",
                              128=>"Orthopedic surgery",
                              113=>"Osteoporosis",
                              126=>"OTC",
                              //62=>"Optometry",
                              //63=>"Orthopedic Foot & Ankle",
                              //64=>"Orthopedic Reconstructive Surgery",
                              //65=>"Orthopedic Spine Surgery",
                              //66=>"Orthopedic Surgery",
                              //67=>"Osteopathy",
                              //68=>"Otorhinolaryngology",
                              69=>"Pain Management",
/*
                              70=>"Palliative Medicine",
                              71=>"Pathology",
                              72=>"Pediatric Allergy",
                              73=>"Pediatric Cardiology",
                              74=>"Pediatric Critical Care",
                              75=>"Pediatric Emergency Medicine",
                              76=>"Pediatric Endocrinology",
                              77=>"Pediatric Gastroenterology",
                              78=>"Pediatric Hematology/Oncology",
                              79=>"Pediatric Internal Medicine",
                              80=>"Pediatric Neurology",
                              81=>"Pediatric Ophthalmology",
                              82=>"Pediatric Psychiatry",
                              83=>"Pediatric Pulmonology",
                              84=>"Pediatric Radiology",
                              85=>"Pediatric Surgery",
                              86=>"Pediatrics",
                              87=>"Perinatal",
                              88=>"Physical Medicine/Rehab",
                              89=>"Physician",
                              90=>"Plastic Surgery",
*/
                              91=>"Podiatry",
                              //92=>"Preventative Medicine",
                              115=>"Primary Care",
                              118=>"Product Support",
                              93=>"Psychiatry",
                              //94=>"Psychology",
                              //95=>"Pulmonary Critical Care",
                              96=>"Pulmonology",
                              97=>"Radiation Oncology",
                              //98=>"Radiology",
                              99=>"Reproductive Endocrinology",
                              121=>"Respiratory",
                              100=>"Rheumatology",
                              101=>"Sleep Medicine",
                              116=>"Surgery",
                              //102=>"Sports Medicine",
                              //103=>"Surgical Oncology",
                              //104=>"Thoracic Surgery",
                              105=>"Transplant Surgery",
                              123=>"Tuberculosis",
                              //106=>"Trauma Surgery",
                              //107=>"Urgent Care",
                              108=>"Urology",
                              114=>"Vaccinations",
                              //109=>"Vascular & Interventional Radiology",
                              //110=>"Vascular Surgery",
                              111=>"Women's Health",
                              112=>"Wound Care",
 );

# max 9
$LANG['languages'] = array( 1000=>"n/a", 
                             8=>"Arabic",
                             10=>"Bulgarian",
                             11=>"Czech",
                             12=>"Danish",
                             13=>"Dutch",
                             14=>"English",
                             15=>"Estonian",
                             16=>"Finnish",
                             5=>"French", 
                             3=>"German", 
                             17=>"Greek",
                             18=>"Hungarian",
                             19=>"Irish",
                             2=>"Italian",
                             1=>"Japanese", 
                             20=>"Latvian",
                             21=>"Lithuanian",
                             22=>"Maltese",
                             6=>"Mandarin", 
                             28=>"Norwegian", 
                             23=>"Polish",
                             9=>"Portuguese", 
                             24=>"Romanian",
                             7=>"Russian", 
                             25=>"Slovak",
                             26=>"Slovene",
                             4=>"Spanish", 
                             27=>"Swedish",
                             29=>"Turkish",
 );

# max 22
$LANG['areas'] = array( 1000=>"n/a", 
                             4=>"USA", 
                             15=>"Africa", 
                             13=>"Argentina",
                             17=>"Asia",
                             9=>"Australia",
                             5=>"Brazil", 
                             3=>"Canada", 
                             22=>"Central America", 
                             8=>"China", 
                             20=>"Eastern Europe", 
                             1=>"France",
                             10=>"Germany",
                             7=>"India", 
                             16=>"Indonesia", 
                             11=>"Italy", 
                             12=>"Japan", 
                             14=>"Middle East", 
                             19=>"Portugal", 
                             6=>"Russia",
                             21=>"South America", 
                             18=>"Spain",
                             2=>"UK", 
);

//-----------------------------------
//interviews
$LANG['anonymous'] = "(Personal Source)";
$LANG['source_hidden'] = "(Source Name Hidden)";

$LANG['credibility'] = array( 4=>"4: Highly Credible", 3=>"3: Credible", 2=>"2: Not Credible", 1=>"1: Rumor" );

$LANG['credibility_short'] = array( 4=>"4", 3=>"3", 2=>"2", 1=>"1" );

$LANG['approach'] = array( 1=>'Intellent', 2=>'Clarity', 3=>'PFW', 4=>'Vendor Sourcing', 5=>'Pharmapeek', 6=>'BIR', 7=>'VaxTrac', 8=>'Personal', 9=>'Student', 10=>'Medical Colleague', 11=>'PharmaForce', 12=>'Trial Information Inquiry', 14=>'Health Care Consultant', 13=>'Other' );

$LANG['status'] = array( 0=>'Open', 1=>'Resolved' );
$LANG['concerns'] = array( 0=>'Project on track', 1=>'Concerns', 2=>'Immediate problem' );

$LANG['open'] = array( 1=>'Open', 0=>'Closed' );
$LANG['yes'] = array( 1=>'Yes', 0=>'No' );
$LANG['yes_null'] = array( 2=>'n/a', 1=>'Yes', 0=>'No' );
$LANG['life_science'] = array( 1=>'Life Science', 0=>'Business Services' );
$LANG['prefix'] = array( 0=>'', 1=>'US', 2=>'EU');

$LANG['contact_method'] = array( 1=>'Phone', 2=>'Email', 3=>'Email and Phone', 4=>'Face to Face' );

$LANG['clientinteraction'] = array( 1=>'On Site', 2=>'TC', 3=>'Report Due' );
$LANG['deliverable_type'] = array( 1=>'Weekly Report',
                                   2=>'Bi-Weekly Report',
                                   3=>'Monthly Report',
                                   4=>'Bi-Monthly Report',
                                   5=>'Quarterly Report',
                                   6=>'Semi-Annual Report',
                                   7=>'Interim Report',
                                   8=>'Conference Planner',
                                   9=>'Conference Report',
                                   10=>'Flash Report',
                                   11=>'War Game Briefing Book',
                                   12=>'Deep Dive Report',
                                   13=>'Final Report',
                                   14=>'Ad Hoc',
                                   15=>'Client Call',
 );

$LANG['log_type'] = array(
		1=>"Login",
		2=>"Logout",
		3=>"Access Sources",
		4=>"Access Interviews",
		5=>"Access Organizations",
		6=>"Access Projects",
		7=>"Access Users",
		8=>"Access Conferences",
		9=>"Access Activity",
);

$LANG['expense_type'] = array(
		1=>"Contractor",
		0=>"Other",
);

