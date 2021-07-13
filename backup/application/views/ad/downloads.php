<div class="content">
   <div class="container">
      <section class="common-pad">
         <div class="container">
            <div class="recharge_main">
               <div class="row">
                  <div class="col-md-3 col-lg-3 col-12">
                     <a target="_blank" href="<?=ADMINURL.$folder.'/'?>certificate">
                        <div class="rgr-box cursor">
                           <div class="rgr-img">
                              <img width ="75" src="<?=ADMINURL?>assets/images/certificate.png" alt="">
                           </div>
                           <div class="rgr-head">
                              <h3> My Certificate </h3>
                           </div>
                        </div>
                     </a>
                  </div>

                  <?php foreach($rows as $row): ?>
                     <div class="col-md-3 col-lg-3 col-12">
                        <a target="_blank" <?=$row["is_downloadable"]?"download ":""?>href="<?=$row['url']?>">
                           <div class="rgr-box cursor">
                              <div class="rgr-img">
                                 <img width ="75" src="<?=ADMINAPIURL;?>uploads/<?=$row['image']?>" alt="">
                              </div>
                              <div class="rgr-head">
                                 <h3> <?=$row["title"]?></h3>
                              </div>
                           </div>
                        </a>
                     </div>
                  <?php endforeach ?>
               </div>
            </div>
         </div>
      </section>
      <br> <br>
   </div>
</div>
<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>

