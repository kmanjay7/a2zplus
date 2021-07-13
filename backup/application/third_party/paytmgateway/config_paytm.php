<?php

/*define('PAYTM_ENVIRONMENT', 'TEST'); // TEST
define('PAYTM_MERCHANT_KEY', 'jbBH7r2V@_I_TEsM'); 
define('PAYTM_MERCHANT_MID', 'DIGICA67896127209968'); 
define('PAYTM_MERCHANT_WEBSITE', 'WEBSTAGING');
define('PAYTM_MERCHANT_WEBSITE_APP', 'APPSTAGING');
define('INDUSTRY_TYPE_ID', 'Retail');
define('CHANNEL_ID', 'WEB');
define('CHANNEL_ID_APP', 'WAP');*/

define('PAYTM_ENVIRONMENT', 'PROD'); // PROD
define('PAYTM_MERCHANT_KEY', 'TCe6KHi1a2dgts_F'); 
define('PAYTM_MERCHANT_MID', 'DIGICA29793206888966'); 
define('PAYTM_MERCHANT_WEBSITE', 'DEFAULT');
define('PAYTM_MERCHANT_WEBSITE_APP', 'DEFAULT'); 
define('INDUSTRY_TYPE_ID', 'Retail102');
define('CHANNEL_ID', 'WEB');
define('CHANNEL_ID_APP', 'WAP');


$PAYTM_STATUS_QUERY ='https://securegw-stage.paytm.in/merchant-status/getTxnStatus';
$PAYTM_TXN_URL ='https://securegw-stage.paytm.in/theia/processTransaction'; 
if (PAYTM_ENVIRONMENT == 'PROD') {
	$PAYTM_STATUS_QUERY ='https://securegw.paytm.in/order/status';
	$PAYTM_TXN_URL='https://securegw.paytm.in/order/process';
}
define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY);
define('PAYTM_TXN_URL', $PAYTM_TXN_URL);

?>
