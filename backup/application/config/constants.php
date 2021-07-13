<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


defined('ADMINURL')      OR define('ADMINURL', 'https://mydigicash.in/');
defined('APIURL')      OR define('APIURL', 'https://mydigicash.in/');
defined('ADMINAPIURL')      OR define('ADMINAPIURL', 'https://myadmin.mydigicash.in/');  
defined('AGENTPANEL')     OR define('AGENTPANEL', 'https://mydigicash.in/');


defined('SMSKEY')  OR define('SMSKEY','2576ad5aececd1b0cd578441b102b4c');
defined('SENDERID')  OR define('SENDERID','DIGICS');
defined('ROOTID')  OR define('ROOTID','8');
defined('SMSPREFIX')  OR define('SMSPREFIX','Digicash');

/* Recharge Api start */
//defined('RECH_MOBILE')  OR define('RECH_MOBILE','manimulti');
defined('RECH_MOBILE')  OR define('RECH_MOBILE','emoney');
/* Recharge Api start */

/* mani multi Recharge Api start */
//defined('MANIMULTI_TOKEN')  OR define('MANIMULTI_TOKEN','dd4781167e2649c1bfb4e2c49a17563c');
defined('MANIMULTI_TOKEN')  OR define('MANIMULTI_TOKEN','25336bcf06bb46a0b39db100a5c94415');
/* mani multi Recharge Api end */

/* mplan Api token start */
defined('MPLAN_API_KEY') OR define('MPLAN_API_KEY','8009f35efc5eb975102c061562c0a9e7');
/* mplan Api token end */

/* emoney Api token start */
defined('EMONEY_API_KEY') OR define('EMONEY_API_KEY','HMQ1KRFB$T5KN8JH');
defined('EMONEY_USERNAME') OR define('EMONEY_USERNAME','69207');
/* emoney Api token end */

defined('INFOMAIL')      OR define('INFOMAIL', 'info@mydigicash.in' ); 

/* M-Robotics Api token start */
defined('MROBOTICX_API_KEY') OR define('MROBOTICX_API_KEY','5847a161-f6b2-4e5b-8d86-b911bbd73c47');
defined('MROBOTICX_API_URL') OR define('MROBOTICX_API_URL','https://mrobotics.in');
/* M-Robotics Api token end */

defined('FROMMAIL')     OR define('FROMMAIL', 'no-reply@mydigicash.in'); 
defined('FMAILER')      OR define('FMAILER', 'no-reply@mydigicash.in'); 
defined('FCC')          OR define('FCC', ''); 
defined('FHOST')        OR define('FHOST', '3.6.49.157'); 
defined('FSERVERUSER')  OR define('FSERVERUSER', 'info@mydigicash.in');
defined('FPASSWORD')    OR define('FPASSWORD',  'Info@DGCASH'); 

 
defined('TDS')  OR define('TDS','5');
defined('INSTANTPAY_TOKEN')  OR define('INSTANTPAY_TOKEN','dc2dcd636fb7f9dd2a041b2e419743ef');


//staging
// defined('BBPS_URL')  OR define('BBPS_URL','https://digitalproxy-staging.paytm.com/billpay/'); 
// defined('BBPS_URL_AGENT_ID')  OR define('BBPS_URL_AGENT_ID','11065108');
// defined('BBPS_NPCI_AGENT_ID')  OR define('BBPS_NPCI_AGENT_ID','PT03');
// defined('BBPS_AGENT_ID')  OR define('BBPS_AGENT_ID','PT01PT0MOB7118732216');
// defined('BBPS_USERNAME')  OR define('BBPS_USERNAME','7777777777');
// defined('BBPS_PASSWORD')  OR define('BBPS_PASSWORD','paytm@123');

//live
defined('BBPS_URL')  OR define('BBPS_URL','https://billpayment.paytm.com/billpay/v2/');
defined('BBPS_URL_AGENT_ID')  OR define('BBPS_URL_AGENT_ID','1229398742');
defined('BBPS_NPCI_AGENT_ID')  OR define('BBPS_NPCI_AGENT_ID','PT09');
defined('BBPS_AGENT_ID')  OR define('BBPS_AGENT_ID','PT01PT09AGT522527450');
defined('BBPS_USERNAME')  OR define('BBPS_USERNAME','mahtab.alam@digicash.co.in');
defined('BBPS_PASSWORD')  OR define('BBPS_PASSWORD','Dlgic@$h$$19');

//a2z live
defined('CALL_BACK_URL')  OR define('CALL_BACK_URL','https://partners.a2zsuvidhaa.com/api/v1/recharge/bill-recharge');
defined('A2Z_USERID')  OR define('A2Z_USERID','12816');
defined('A2Z_TOKEN')  OR define('A2Z_TOKEN','eVHGnU59YQEo1Rvwa7cNyF9pTRNeGQQ5Lbi0EWQHW5uso4rnKLkttCKfky6w');
defined('A2Z_SECRET_KEY')  OR define('A2Z_SECRET_KEY','12816yvRbT7rBYqwPAUQqVdNb0P4xbs8cVBY6S95PqDSVdC7bcihRyEkFxqoCh2cM');

defined('GOLDPAY_USERID')  OR define('GOLDPAY_USERID','digicash.ind@gmail.com');
defined('GOLDPAY_TOKEN')  OR define('GOLDPAY_TOKEN','LcI+2Wh49tkgy7VPNF+PhQ==');

defined('MAHIPAY_USERID')  OR define('MAHIPAY_USERID','165');
defined('MAHIPAY_TOKEN')  OR define('MAHIPAY_TOKEN','f700b95314b6755873304e72cc26de8e');

defined('DIGIO_URL')        OR define('DIGIO_URL', 'https://api.digio.in/'); 
defined('DIGIO_CLIENT_ID')  OR define('DIGIO_CLIENT_ID', 'AIKNKBWJVL8GSYKY846GGWSWPITE2U9G'); 
defined('DIGIO_SECRET_KEY') OR define('DIGIO_SECRET_KEY', '3QQ2F3OWRITOUCPEYWASML9MJ3V1GE7B');

//DigiuPay Electricity
defined('DIGIUPAY_URL')  OR define('DIGIUPAY_URL','http://digitalupay.in/');
defined('DIGIUPAY_USERNAME')  OR define('DIGIUPAY_USERNAME','9559071243');
defined('DIGIUPAY_APITOKEN')  OR define('DIGIUPAY_APITOKEN','WoAszJBXdPqfMULxITeC6YwuVhlSH91kg4yDva7F30');