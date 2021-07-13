<div class="content">
      <div class="container">
         <section class="common-pad">
            <div class="container">

               <div class="notification-panel">
                         <div class="row">
                            <div class="col-md-6 col-lg-6 col-12">
                               <div class="notification_news">
                                  <div class="noti_head">
                                   <h3>  Notification Panel </h3>
                                  </div>
                                   <div class="noti-news">

                                       <marquee width="100%" behavior="scroll" direction="up" scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();">
                                        <?php foreach ($notifications as $noti): ?>
                                          <p><?=$noti["content"]?></p>
                                        <?php endforeach ?>
                                      </marquee>
                                      
                                    </div>
                               </div>
                            </div>
                            <div class="col-md-6 col-lg-6 col-12">
                             <div class="slider-area">
                            <div id="news-slide" class="carousel slide" data-ride="carousel" data-interval="3000">

                                 <!-- Indicators -->
                                 <ul class="carousel-indicators">
                                  <?php $i=0; foreach($banner as $ban): ?>
                                   <li data-target="#news-slide" data-slide-to="<?=$i?>" class="<?=$i==0?'active':''?>"></li>
                                  <?php $i++; endforeach ?>
                                 </ul>
                               
                                 <!-- The slideshow -->
                                 <div class="carousel-inner">
                                  <?php $i=0; foreach($banner as $ban): ?>
                                   <div class="carousel-item<?=$i==0?' active':''?>">
                                       <img src="<?=ADMINAPIURL?>uploads/<?=$ban['image']?>" alt="">
                                   </div>
                                  <?php $i=1; endforeach ?>
                                 </div>
                               
                                 <!-- Left and right controls -->
                                 <a class="carousel-control-prev" href="#news-slide" data-slide="prev">
                                   <span class="carousel-control-prev-icon"></span>
                                 </a>
                                 <a class="carousel-control-next" href="#news-slide" data-slide="next">
                                   <span class="carousel-control-next-icon"></span>
                                 </a>
                               
                         </div>
                    </div>
               </div>
            </div>
         </div>


   
                
            
            </div>
         </section>

         <br/> <br/>  <br/> <br/>

      </div>
   </div>

<?php $this->load->view('includes/footer');?>
<?php $this->load->view('includes/alljs');?>
<script type="text/javascript">
  function goto(data){
window.location.href='<?=ADMINURL.'ag/';?>'+data;
  }
</script> 