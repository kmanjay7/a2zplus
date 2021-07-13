<?php

defined("BASEPATH") OR exit("No Direct Access Allowed!");

class Recharge_pending_refund_check extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Creditdebit_model', 'cr_model');
    }

    public function index() {

        header("Pragma: no-cache");
        header("Cache-Control: no-cache");
        header("Expires: 0");
        ini_set('memory_limit', '-1');
        
        $newRefArr=array();
        $add_date='2021-05-17';
        $checkRechargeHis=$this->db->query("SELECT dw.* from dt_wallet dw LEFT JOIN dt_rech_history rh on dw.referenceid=rh.reqid WHERE dw.credit_debit='debit' and dw.subject='mob_rech' and date(dw.add_date)>='$add_date' and rh.id IS NULL");
        echo $checkRechargeHis->num_rows(); die();
        if($checkRechargeHis->num_rows())
        {
            foreach($checkRechargeHis->result() as $drow)
            {
                $referenceid=$drow->referenceid;
                $transctionid=$drow->transctionid;
                $userid=$drow->userid;
                $credit_debit=$drow->credit_debit;
                $beforeamount=$drow->beforeamount;
                $amount=$drow->amount;
                $finalamount=$drow->finalamount;
                $addby=$drow->addby;
                $usertype=$drow->usertype;
                $odr=$drow->odr;
                $add_date=$drow->add_date;
                
                $newRefArr[]=$referenceid;
//                $rechLog=$this->db->query("SELECT * FROM dt_rech_history WHERE reqid='$referenceid' and status !='success'");
                $rechLog=$this->db->query("SELECT * FROM dt_rech_history WHERE reqid='$referenceid'");
                if(!$rechLog->num_rows())
                {
                    
                     $Qry= $this->db->query("INSERT INTO `dt_rech_history` (`id`, `user_id`, `apiid`, `serviceid`, `reqid`, `status`, `remark`, `balaftrech`, `amount`, `mobileno`, `cust_account_no`, `field1`, `field2`, `ec`, `apirefid`, `op_transaction_id`, `operatorid`, `operatorname`, `circleid`, `add_date`, `sur_charge`, `commission`, `tds`, `admin_comi`, `admin_tds`, `bp_comi`, `bp_tds`, `md_comi`, `md_tds`, `ad_comi`, `ad_tds`, `ag_comi`, `ag_tds`, `final_status`, `status_update`, `complaint`, `apptype`, `cons_name`, `billno`, `duedate`, `nextupdate`, `noofround`) VALUES (NULL, '$userid', '5', '5', '$referenceid', 'FAILURE', 'Payment Failure', '0.00', '$amount', 'NA', '', 'NA', 'NA', '', 'NA', '', '0', 'NA', '0', '$add_date', '0.000', '0.000', '0.000', '0.000', '0.000', '0.000', '0.000', '0.000', '0.000', '0.000', '0.000', '0.000', '0.000', '', NULL, '', NULL, '', '', NULL, NULL, NULL)");
                    /*$checkDebit=$this->db->query("SELECT * FROM dt_wallet WHERE referenceid='$referenceid' and userid='$userid' and credit_debit='credit' and status ='success'");
                    if(!$checkDebit->num_rows())
                    {
                        $orderid=$referenceid;
                        $wtorderid = str_replace("MOB","RFND",$orderid);
                        $credit_debit = '';
                        
                        if($usertype=='AGENT'){
                            $remark = 'Refund for order ID: '.$orderid;
                            $subject = 'mob_rech_refund';
                            $wtorderid = 'RFND'.filter_var($orderid,FILTER_SANITIZE_NUMBER_INT );
                            $credit_debit = 'credit';

                            if($credit_debit)
                            {
                                $beforeamountnew=$beforeamount-$amount;
                                $amountnew=$amount;
                                $finalamountnew=$finalamount+$amount;

                                $saveref=array(
                                    'userid'=>$userid,
                                    'usertype'=>'AGENT',
                                    'paymode'=>'wallet',
                                    'credit_debit'=>'credit',
                                    'transctionid'=>$wtorderid,
                                    'remark'=>$remark,
                                    'subject'=>'rech_mobile_refund',
                                    'referenceid'=>$orderid,
                                    'add_date'=>date('Y-m-d H:i:s'),
                                    'addby'=>$addby,
                                    'beforeamount'=>$beforeamountnew,
                                    'amount'=>$amountnew,
                                    'finalamount'=>$finalamountnew,
                                    'status'=>'success',
                                );

    //                                $this->c_model->saveupdate($table2,$saveref,null, null );
                                echo '<pre>';print_r($drow); print_r($saveref); die();
                                
                            }
                        }
                        
                    }*/
                    
                }
            }  
            echo '<pre>'; print_r($newRefArr); die();
            
        }
        
        /*
        $table='rech_history';
        $orderby=null;
        $where['status !=']='success';
        $keys='*';
        $limit=null;
        $history=$this->c_model->getAll($table, $orderby, $where,$keys,$limit );
        if(!empty($history))
        {
            foreach($history as $hrow)
            {
                $user_id=$hrow['user_id'];
                $reqid=$hrow['reqid'];
                
                
                $table2='wallet';
                $orderby2=null;
                $limit2=null;
                $where2['userid']=$user_id;
                $where2['transctionid']=$reqid;                
                $where2['paymode']='wallet';
                $where2['credit_debit']='credit';
                $where2['status']='success';
                $keys2='*';                
                
                $debithistory=$this->c_model->getAll($table2, $orderby2, $where2,$keys2,$limit2 );
                
                if(!empty($debithistory))
                {         
                    $dhrow=$debithistory[0];

                    $finalamount=$dhrow['finalamount'];
                    $amount=$dhrow['amount'];
                    $beforeamount=$dhrow['beforeamount'];
                    $addby=$dhrow['addby'];
                    
                    
                    $where3['userid']=$user_id;
                    $where3['transctionid']=$reqid;   
                    $where3['status']='success';
                    $where3['subject']='rech_mobile_refund';
                    $where3['paymode']='wallet';
                    $where3['credit_debit']='credit';
                    $keys2='*';
                    
                    
                    $credithistory=$this->c_model->getAll($table2, $orderby2, $where3,$keys2,$limit2 );
                    if(empty($credithistory))
                    {
                        
                        $orderid=$reqid;
                        $wtorderid = '';
                        $credit_debit = '';

                        if($hrow['serviceid'] == 5){
                             $remark = '';
                             $subject = ''; 

                            if($dhrow['usertype']=='AGENT'){
                                $remark = 'Refund for order ID: '.$orderid;
                                $subject = 'mob_rech_refund';
                                $wtorderid = 'RFND'.filter_var($orderid,FILTER_SANITIZE_NUMBER_INT );
                                $credit_debit = 'credit';
                                
                                if($credit_debit)
                                {
                                    $beforeamountnew=$beforeamount-$amount;
                                    $amountnew=$amount;
                                    $finalamountnew=$finalamount+$amount;

                                    $saveref=array(
                                        'userid'=>$user_id,
                                        'usertype'=>'AGENT',
                                        'paymode'=>'wallet',
                                        'credit_debit'=>'credit',
                                        'transctionid'=>$orderid,
                                        'remark'=>$remark,
                                        'subject'=>'rech_mobile_refund',
                                        'referenceid'=>$orderid,
                                        'add_date'=>date('Y-m-d H:i:s'),
                                        'addby'=>$addby,
                                        'beforeamount'=>$beforeamountnew,
                                        'amount'=>$amountnew,
                                        'finalamount'=>$finalamountnew,
                                        'status'=>'success',
                                    );

    //                                $this->c_model->saveupdate($table2,$saveref,null, null );
                                    echo '<pre>';print_r($dhrow); print_r($saveref); die();
                                    $save_rch['status'] = 'FAILURE';
                                    $save_rch['remark'] = 'Amount Refunded Successfully';
                                    $where_rch['reqid'] =  $orderid;
//                                    $this->c_model->saveupdate('dt_rech_history',$save_rch,null, $where_rch );
                                }
                            }
                             
                            
                        }
                         
                        
                    }
//                    echo '<pre>';print_r($dhrow); die();
                }
                
            }
        }
        */
    }

}

?>