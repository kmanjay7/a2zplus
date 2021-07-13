<?php 

define('PAYTM_MERCHANT_KEY', 'mMEAeswk2Elaa7ZC'); //digicash
define('SUBWALLET_GUID', 'c2525d35-0473-4429-be15-836484f6443b'); //digicash
define('SUBWALLET_GUID_AEPS', 'ebaaa4c3-fd8d-4525-b8e8-2e17e8f4c245'); //digicash
define('PAYTM_MERCHANT_MID', 'DIGICA14186603293483');  //digicash


define('PAYTM_ENVIRONMENT', 'PROD'); // TEST digicash

define('PAYTM_MERCHANT_WEBSITE', 'WEBSTAGING'); //digicash

define('CHANNEL_ID','WEB');
define('INDUSTRY_TYPE_ID','Retail');

 
$PAYTM_FUND_TXN_URL ='https://staging-dashboard.paytm.com/bpay/api/v1/disburse/order/bank';
$PAYTM_FUND_CHECKSUM_URL ='https://staging-dashboard.paytm.com/bpay/api/v1/disburse/order/query';

$PAYTM_CHECK_BALANCE_URL ='https://staging-dashboard.paytm.com/bpay/api/v1/account/list';


if ( PAYTM_ENVIRONMENT == 'PROD') { 
	$PAYTM_FUND_TXN_URL='https://dashboard.paytm.com/bpay/api/v1/disburse/order/bank';
	$PAYTM_FUND_CHECKSUM_URL ='https://dashboard.paytm.com/bpay/api/v1/disburse/order/query';
	$PAYTM_CHECK_BALANCE_URL ='https://dashboard.paytm.com/bpay/api/v1/account/list';
}

define('PAYTM_FUND_TXN_URL', $PAYTM_FUND_TXN_URL);
define('PAYTM_FUND_CHECKSUM_URL', $PAYTM_FUND_CHECKSUM_URL);
define('PAYTM_CHECK_BALANCE_URL', $PAYTM_CHECK_BALANCE_URL);

?>
