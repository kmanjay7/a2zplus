<?php if(!empty($trans_list)){ $i = 1;
    foreach($trans_list as $key=>$value ){?>

    <tr id="rowid-<?=$i;?>" >
        <td>
            <p> <?=$i;?></p>
             <p> <?=$value['referenceid'];?></p>
    
        </td>
        <td>
            <p class="name"><?=$value['cname'];?></p>
            <p>/<?=$value['cuid'];?></p>
            <p>/<?=$value['cusertype'];?></p>
    
    
        </td>
        <td>
            <p class="name"><?=$value['dname'];?></p>
            <p>/<?=$value['duid'];?></p>
            <p>/<?=$value['dusertype'];?></p>
        </td>
        <td>
            <p><?=$value['paymode'];?></p>
    
            <p><?=$value['amount'];?></p>
    
        <td>
            <p><?=date('d-M-Y',strtotime($value['add_date']));?></p>
            <p><?=date('h:i A',strtotime($value['add_date']));?></p>
    
    
        </td>
        <td>
            <p><?=$value['beforeamount'];?></p>
            <p>/<?=$value['finalamount'];?></p>
        </td>
        <td class="messg">
        <a href="#" data-toggle="tooltip" data-placement="right"
                title="<?=$value['remark'];?>">Show Remarks</a>  
        </td>
        <td><a href="javascript:void(0)" id="vid<?=$i;?>"><button class="update-btn" onclick="return confirm('Are You Sure?'),debit_Amount('<?=md5($value['id']);?>','<?=$i;?>')"> Debit </button></a> </td>
    
    </tr>
                                            
   <?php $i++; } }else{ echo '<tr><td colspan="7"><center>No Record in Our Database</center></td></tr>';} ?>
