<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rech_commission { 


public function index($dt_arr = array(), $table ){ 
 
      
$serviceid    = $dt_arr['service'];
$operatorid   = $dt_arr['operatorid'];
$apiid        = null;//$dt_arr['apiid'];
$amount       = $dt_arr['amount'];
$orderid      = $dt_arr['orderid']; 
$userid       = $dt_arr['userid'];
$tableid      = $dt_arr['id'];
$final_status = $dt_arr['final_status'];
$tablename    = $table;

$save = []; 

if($final_status == 'yes'){ exit;}

             $where['id'] = $userid;
             $where['user_type'] = 'AGENT'; 
             $userlist = $this->getSingleUserlist( $where ); 

            /* generate pairs to get diffrence between two users start */
             $slot = $this->createPlot($userlist);  
            /* generate pairs to get diffrence between two users end */ 



            $agent_array = array();
            $ad_array = array();
            $md_array = array();
            $bp_array = array(); 


            $schemelist = $this->getScheme(); 
            if($operatorid){ $cm_where['operatorid'] = $operatorid; } 
            if($apiid){ $cm_where['apiid'] = $apiid; }  
            $cm_where['serviceid'] = $serviceid;
            $cmlist = $this->getUserComission($cm_where,$amount,$schemelist);

 

            $ag_tds       = 0;
            $ag_comi      = 0; 
            $ag_comission = 0;

            $ad_tds       = 0;
            $ad_comi      = 0; 
            $ad_comission = 0;

            $md_tds       = 0;
            $md_comi      = 0; 
            $md_comission = 0;

            $bp_tds       = 0;
            $bp_comi      = 0; 
            $bp_comission = 0;

    $postarray = array();


    foreach ($cmlist as $key => $smvalue) {
        if($smvalue['user_type']=='AD'){   
             $ad_tds = percentage( $smvalue['commision'], TDS );
             $ad_comi = (float)($smvalue['commision'] - $ad_tds);
        }elseif($smvalue['user_type']=='MD'){
             $md_tds = percentage( $smvalue['commision'], TDS );
             $md_comi = (float)($smvalue['commision'] - $md_tds);
        }elseif($smvalue['user_type']=='BP'){
             $bp_tds = percentage( $smvalue['commision'], TDS );
             $bp_comi = (float)($smvalue['commision'] - $bp_tds);
        }
    } 

          


 $surcharge = 0;
 foreach ($slot as $key => $slvalue) {
            $first_mem = $slvalue[0];
            $last_mem = $slvalue[1]; 
            if(($first_mem['user_type'] == 'AGENT') && ($last_mem['user_type'] == 'AD')){
              $postarray['ad_comi'] = twoDecimal($ad_comi);
              $postarray['ad_tds'] = twoDecimal($ad_tds);
              $this->updateinWallet($last_mem['id'],$last_mem['user_type'],$amount,$orderid,$ad_comi,$surcharge,$ad_tds,$serviceid );
            }else if(($first_mem['user_type'] == 'AGENT') && ($last_mem['user_type'] == 'MD')){
              $postarray['md_comi'] = twoDecimal(( $ad_comi + $md_comi ));
              $postarray['md_tds'] = twoDecimal(( $ad_tds + $md_tds ));
              $this->updateinWallet($last_mem['id'],$last_mem['user_type'],$amount,$orderid,$postarray['md_comi'],$surcharge,$postarray['md_tds'],$serviceid ); 
            }
            else if(($first_mem['user_type'] == 'AGENT') && ($last_mem['user_type'] == 'BP')){
              $postarray['bp_comi'] = twoDecimal(($ad_comi + $md_comi + $bp_comi));
              $postarray['bp_tds'] = twoDecimal(( $ad_tds + $md_tds + $bp_tds ));
              $this->updateinWallet($last_mem['id'],$last_mem['user_type'],$amount,$orderid,$postarray['bp_comi'],$surcharge,$postarray['bp_tds'],$serviceid );  
            }
            else if(($first_mem['user_type'] == 'AD') && ($last_mem['user_type'] == 'MD')){
              $postarray['md_comi'] = twoDecimal($md_comi);
              $postarray['md_tds'] = twoDecimal($md_tds); 
              $this->updateinWallet($last_mem['id'],$last_mem['user_type'],$amount,$orderid,$postarray['md_comi'],$surcharge,$postarray['md_tds'],$serviceid ); 
            }
            else if(($first_mem['user_type'] == 'AD') && ($last_mem['user_type'] == 'BP')){
              $postarray['bp_comi'] = twoDecimal( ($md_comi + $bp_comi ));
              $postarray['bp_tds'] = twoDecimal( ($md_tds + $bp_tds)); 
              $this->updateinWallet($last_mem['id'],$last_mem['user_type'],$amount,$orderid,$postarray['bp_comi'],$surcharge,$postarray['bp_tds'],$serviceid ); 
            }
            else if(($first_mem['user_type'] == 'MD') && ($last_mem['user_type'] == 'BP')){
              $postarray['bp_comi'] =  twoDecimal($bp_comi);
              $postarray['bp_tds'] = twoDecimal($bp_tds); 
              $this->updateinWallet($last_mem['id'],$last_mem['user_type'],$amount,$orderid,$postarray['bp_comi'],$surcharge,$postarray['bp_tds'],$serviceid ); 
            }
 }        

 
     /*update commission in table start script*/
     $postarray['final_status'] = 'yes';
     $updatetbl = ci()->c_model->saveupdate($tablename,$postarray,null,['id'=>$tableid ] );
     return true;
     if(!$updatetbl){ 
      exit;
     }
     /*update commission in table end script*/
          
}




public function createPlot($userlist){
              $i = 1; $slot = array(); $store = '';
             foreach($userlist as $key=>$value){
               
               if( $i != 1 ){
                 $arr = array();
                 $arr[] = $store;
                 $arr[] = $value; 
                 array_push($slot, $arr);
               } 
               $store = '';
               $store = $value;

             $i++;
             }
             return $slot;
}

 
public function getUserComission($cm_where,$amount,$schemelist){  

    $cmkey = 'commision_percent,commision_fixed,surcharge_percent,surcharge_fixed,user_type';
      
    $invalue = '';
    foreach ($schemelist as $key => $schvalue) {
      $invalue .= $schvalue['id'].',';
    }

    $invalue = ltrim($invalue,',');
    $invalue = rtrim($invalue,',');


             $get_comi = $invalue ? ci()->c_model->getfilter('dt_set_commission',$cm_where,null,null,null,null,null,null,null, null,'get','scheme_type', $invalue, $cmkey ) : [];

             
            $output = [];
            if(empty($get_comi)){
              return $output; exit;
            } 

   $store = [];
   foreach ($get_comi as $key => $value) { 
            
             $comi_p = $value['commision_percent']; 
             $comi_f = $value['commision_fixed'];
             $such_p = $value['surcharge_percent'];
             $such_f = $value['surcharge_fixed']; 

             $comi = false;
             $such = false;

                if( $comi_f > 0){
                  $comi = $comi_f;
                }else if( $comi_p > 0){
                  $comi = percentage($amount,$comi_p);
                }

                if( $such_f > 0){
                  $such = $such_f;
                }else if( $such_p > 0){
                  $such = percentage($amount,$such_p);
                } 
             
             $store['commision'] = $comi; 
             $store['surcharge'] = $such;
             $store['user_type'] = $value['user_type'];   
             array_push($output, $store);
    }    
      
             return $output;
}




public function getSingleUser($where){
             $keys = 'id,user_type,parenttype,parentid'; 
             return ci()->c_model->getSingle('dt_users',$where, $keys );
}

public function getSingleUserlist($where){ 
            $output = array();
            if($where['user_type']=='AGENT'){
             $response1 = $this->getSingleUser($where);
                   if(!empty($response1)){ 
                   array_push($output, $response1 );
                      $where2['id'] = $response1['parentid'];
                      $where2['user_type'] = $response1['parenttype'];
                      $response2 = $this->getSingleUser($where2);
                        if(!empty($response2)){ 
                        array_push($output, $response2 );
                            $where3['id'] = $response2['parentid'];
                            $where3['user_type'] = $response2['parenttype'];
                            $response3 = $this->getSingleUser($where3);
                                  if(!empty($response3)){ 
                                  array_push($output, $response3 );
                                        $where4['id'] = $response3['parentid'];
                                        $where4['user_type'] = $response3['parenttype'];
                                        $response4 = $this->getSingleUser($where4);
                                          if(!empty($response4)){ 
                                          array_push($output, $response4 );
                                             $where5['id'] = $response4['parentid'];
                                             $where5['user_type'] = $response4['parenttype'];
                                             $response5 = $this->getSingleUser($where5);
                                                if(!empty($response5)){ 
                                                array_push($output, $response5 );
                                                }
                                          }else{
                                            array_push($output, $where4 );
                                          }
                                  }else{
                                    array_push($output, $where3 );
                                  }
                        }else{
                          array_push($output, $where2 );
                        }
                   }
            }
                   

                    $newoutput = array();
                    foreach ($output as $key => $value) {
                      $arr['id'] = $value['id'];
                      $arr['user_type'] = $value['user_type']; 
                      if(!in_array($arr, $newoutput)){
                      array_push($newoutput, $arr);
                      }
                    } 

            return $newoutput ; 
}


public function getScheme(){
  $list = ci()->c_model->getAll('dt_scheme',null,array('status'=>'yes'),'id,user_type'); 
  return $list;
}



private function updateinWallet($userid,$usertype,$amount,$orderId,$comi=false,$surcharge=false,$tds=false,$serviceid=false){ 


if(!$userid){ return false; exit;}
elseif(!$usertype){ return false; exit;}
elseif(!$amount){ return false; exit;}
elseif(!$orderId){ return false; exit;} 
$credit_debit = 'credit';

if( $serviceid == 5){
    $servicename = 'Mobile Recharge';
    $servicename2 = 'mob_rech_comi';
}else if($serviceid == 3){
    $servicename = 'DTH Recharge';
    $servicename2 = 'dth_rech_comi';
}
$subject = $servicename2;

$duplicate = 1;
$checkwt = [];
$wtsave = [];


$checkwt['userid'] = $userid;
$checkwt['subject'] = $subject;
$checkwt['referenceid'] = $orderId;
$duplicate = ci()->c_model->countitem('dt_wallet', $checkwt );

if($duplicate){ return 1; exit; }



      $table = 'dt_wallet';
   
      /* wallet deduction/addition start*/
      $inkey = null;
      $invalue = null;

      $checklast['userid'] = $userid;
      $checklast['usertype'] = $usertype; 
      $inkey = 'credit_debit';
      $invalue = 'credit,debit';


      $lastentry = ci()->c_model->getSingle( $table,$checklast, 'finalamount, beforeamount','id DESC' , 1, $inkey, $invalue );
      $beforeamount = $lastentry['finalamount'];
      $totalamount = $lastentry['finalamount'];


      $execute = false;
      $newtotalamount = false;
      if( $credit_debit == 'credit'){
      $newtotalamount = $totalamount + $comi ; 
      $execute = true;
      }else if( ($credit_debit == 'debit' ) && ($totalamount >= $comi ) &&  $totalamount > 0 ) { 
      $newtotalamount = $totalamount - $comi ; 
      $execute = ($totalamount >= $comi) ? true : false;
      }

      /* wallet deduction/addition end*/




      $post['id'] =  NULL ;
      $post['userid'] = $userid ;
      $post['usertype'] = $usertype ;
      $post['paymode'] = 'wallet';
      $post['transctionid'] = 'NA';
      $post['credit_debit'] = $credit_debit; 
      $post['add_date'] = date('Y-m-d H:i:s') ;
      $post['remark'] = $servicename.' Commission';
      $post['status'] = 'success'; 
      $post['referenceid'] =  $orderId;
      $post['amount'] = twoDecimal($comi);
      $post['finalamount'] = $newtotalamount ;
      $post['subject'] = $subject;
      $post['addby'] = $userid ;
      $post['beforeamount'] = $beforeamount ;

      $post['odr'] = twoDecimal((float)$comi + (float)$tds); 
      $post['comi'] = twoDecimal($comi); 
      $post['tds'] = (float)$tds ; 
      $post['flag'] = 1;   
      
       
      $update = false;
      if( $comi && $userid && $credit_debit && $execute && ($duplicate == 0) ){
       $update = ci()->c_model->saveupdate( $table, $post ); 
      }
   return  $update;

}


} ?>