<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/font-awesome.min.css"/>

<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<title>Request Songs</title>
</head>
<style>
#dvLoading
{
	background: url(../../assets/images/processing.gif) no-repeat center center;
    height: 100px;
    width: 100px;
    position: fixed;
    z-index: 1000;
    left: 45%;
    top: 45%;
    margin: -25px 0 0 -25px;
}

</style>
<body>
<div id="dvLoading"></div>
<div class="container" style="display:none">
  <div class="row">
    <div class="col-12 col-md-12 col-sm-12 custommaintopsection">
      <div class="header">
        <div class="logosection"> 
          <img src="<?php echo base_url(); ?>assets/images/logo.png" class="img-responsive" alt="trl-logo"  /> </div>
      </div>
    </div>
    
    <div class="col-12 col-md-12 col-sm-12 formsection requestsongmain">
     <img src="<?php echo base_url(); ?>assets/images/Micheal.png" class="performerlogo" class="img-responsive" alt="micheal-img"/> 
	 <div class="mainperformer">
	 <div class="performertitle">Michael</div>
	 <span>Performing Tonight</span>
	 </div>
	 
	 <div class="performerdetails">Loremipsumdolor sit amet, consectetur adiplising elit. Nunc maximus, nulla ut cmmodosagittis, sapien dui mattis dui, non pulvinar loremfelis nec erac...</div>
    </div>
    <!--formsection end here-->
	<div class="main-footer">
    <div class="col-12 col-md-12 col-sm-12 footet-top"></div>
    <div class="col-12 col-md-12 col-sm-12 footersetion"><a href="<?php echo base_url()."index.php/requestsongs";
	?>"><button class="btn btn-primary customfullbutton">Request A Song</button></a></div>
	<!-- <div class="col-12 col-md-12 col-sm-12 footet-top"></div>
    <div class="col-12 col-md-12 col-sm-12 footersetion"> <button class="btn btn-primary customfullbutton">Request A Song</button> </div>-->
	</div>
	
	
	
  </div>
  <!--row end here--> 
</div>
<!--Container end here-->
<script type="text/javascript">
	$( document ).ready(function() {
		var base_url="<?php echo base_url();?>";
		$.ajax({
			url: base_url+'index.php/performers/detail/<?php echo $show_id;?>',
			type: 'GET',
			headers: {
				'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
				'Auth-Key':'<?php echo AUTH_KEY;?>'
			},
			error: function() {
				$(".container").show();
			},
			success: function(data) {
				$('#dvLoading').fadeOut(300);
				$(".container").show();
				$(".performertitle").html(data.firstname+" "+data.lastname);
				$(".performerlogo").html();
				$('.performerlogo').attr("src",'<?php echo base_url(); ?>assets/images/'+data.image);
				$(".performerdetails").html(data.message);
			}
		});
	});
</script>

</body>
</html>
