<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/font-awesome.min.css"/>

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/validationEngine.jquery.css" type="text/css"/>
	
	<script src="<?php echo base_url(); ?>assets/js/jquery-1.8.2.min.js" type="text/javascript">
	</script>
	<script src="<?php echo base_url(); ?>assets/js/languages/jquery.validationEngine-en.js" type="text/javascript" charset="utf-8">
	</script>
	<script src="<?php echo base_url(); ?>assets/js/jquery.validationEngine.js" type="text/javascript" charset="utf-8">
	</script>
	
	
	  <title>Request Details</title>
	  <style>
		.formError.inline {
			position: relative;
			top: 0;
			left: 0;
			display: inline-block !important;
		}

		.formError .formErrorContent {
			width: 100%;
			background: none;
			position: relative;
			color: #ef1a1a;
			}
			
		
		</style>

   </head>
   <body class="requestdetails">
     <div class="dvLoading"></div>
      <div class="container" style="display:none">
         <div class="row">
            <div class="col-12 col-md-12 col-sm-12 custommaintopsectioninner">
               <!-- <nav class="navbar navbar-inverse customnav">
                  <div class="navbar-header customheader">
                     <button type="button" class="navbar-toggle customtoggle" data-toggle="collapse" data-target="#myNavbar">
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>                        
                     </button>
                     <a class="navbar-brand custombrand" href="#">Total Requests Live</a>
                  </div>
                  <div class="collapse navbar-collapse" id="myNavbar">
						<!--<ul class="nav navbar-nav">
                        <li class="active"><a href="<?php echo base_url()."performer/request-songs.html/877EB49B-4310-46BB-1160-DFFAE150EF15"; ?>">Requests Song</a></li>
                        <li><a href="#"></a></li>
                        <li><a href="#"></a></li>
                        </ul>
                  </div>
               </nav>-->
			   <div class="header">
					  <div class"logosection"> 
				  <!--<h1>TRL <span class="innerspan">LOGO</span></h1>--> 
				  <img src="<?php echo base_url(); ?>assets/images/logo.png" class="img-responsive" alt="trl-logo"  /> </div>
				  <!--<img src="<?php echo base_url(); ?>assets/images/TRL-logo.png" class="img-responsive" alt="trl-logo"  /> </div>-->
             </div>
            </div>
            <!--col-12 col-md-12 col-sm-12 end here-->
			
			<div class="col-12 col-md-12 col-sm-12">
			<div class="requesttitles">
			<h1>Hurry Up !</h1>
			<span>Highest bidder goes up next</span>
			</div>
			<form class="well form-horizontal requstform" action=" " method="post"  id="contact_form">
			<div class="requsetbotton">
			<div class="input-group mb-3">
			 <input type="text" class="form-control" id="DisplaySongName" name="DisplaySongName" value="Baby One More Time" placeholder="Baby One More Time" aria-label="Baby One More Time" aria-describedby="basic-addon2" readonly/>
			 <span id="DisplayArtistName" class="inputplaceholder">Britney Spears</span>
			<a id="changeSongBtn"  style="cursor:pointer;" href="<?php base_url();?>requestsongs"><div class="input-group-append"><button class="btn btn-outline-secondary changesong"  type="button"><span>Change</span>Song</button></div></a>
			</div>
			</div>
			
			

				<fieldset>
				<!-- Text input-->
	<input type="hidden" name="songID" id="songID">
				<div class="form-group">
				  <div class="col-md-12 inputGroupContainer">
				  <div class="input-group img-input flex">
				  <span class="input-group-addon ">
				  <img src="<?php echo base_url();?>assets/images/Request-Details.png" class="img-responsive" alt="request"  /></span>
				  
				  
            <span class="currency">$</span>
           <input  name="" placeholder="Enter tip amount" style="height: 46px !important;" value="" min="0" max="5" step="1.00" data-number-to-fixed="2" data-number-stepfactor="100" class="form-control currency_input"   id="bidAmount" type="number">
      
	  
					</div>
					
						<span style="padding-left: 20%;" class="form_input_errors" id="email_input_error"></span>
					
						
				  </div>
				</div>

				<!-- Text input-->

				<div class="form-group"> 
					<div class="col-md-12 inputGroupContainer">
					<div class="input-group img-input">
					<span class="input-group-addon"><img src="<?php echo base_url();?>assets/images/user.png" class="img-responsive" alt="user"  /></span>
					<input name="last_name" placeholder="Enter your name"  autocomplete="off" class="form-control validate[required,custom[onlyLetterNumberWithSpace],minSize[1],maxSize[25]]"  data-errormessage-custom-error="Please enter only letters" id="customerName" data-errormessage-value-missing="Your name is required!"  type="text">
					</div>
				  </div>
				</div>

				<div class="form-group">
					<div class="col-md-12 inputGroupContainer">
					<div class="input-group img-input">
					<span class="input-group-addon">
						<img src="<?php echo base_url();?>assets/images/Request-Detailscomment.png" class="img-responsive" alt="detailscomment"  /></span>
							<textarea  autocomplete="off" class="validate[custom[onlyLetterSomeSpecialChars],minSize[2],maxSize[100]]" data-errormessage-custom-error="Please enter only letters or numbers with some special characters only  " name="comment"  data-errormessage-value-missing="Comment is required!"  id="customerComments" placeholder="   Comments"></textarea>
				  </div>
				  </div>
				</div>
				
				
				<!-- Button -->
				
				<div class="form-group col-md-12 bottomcustombuttons">
				  <div class="col-md-6 innerbuttoncustomfirst">
					<a style="cursor:pointer;" id="cancelBtn" href="<?php base_url();?>requestsongs"><button  type="button" class="btn btn-warning" >Cancel</button></a>
				  </div>
				  <div class="col-md-6 innerbuttoncustomsecond">
					<button onclick="" id="submitRequestBtn" type="button" class="btn btn-warning" >Done</button>
				  </div>
				</div>
				
				</fieldset>
				</form>
			</div>
		</div>
      </div>
	  
	  <script type="text/javascript">
	  	jQuery(document).ready(function(){
			var status=jQuery("#contact_form").validationEngine({promptPosition: 'inline'});
		});
			
			if(sessionStorage.getItem('bidAmount')){
				var bidAmount=sessionStorage.getItem('bidAmount');
				$("#bidAmount").val(bidAmount);
			}
			if(sessionStorage.getItem('customerName')){
				var customerName=sessionStorage.getItem('customerName');
				$("#customerName").val(customerName);
			}
			
			if(sessionStorage.getItem('customerComment')){
				var customerComment=sessionStorage.getItem('customerComment');
				$("#customerComments").val(customerComment);
			}
			
			
			$('#bidAmount').bind('blur change', function(){
				var bidAmount=$("#bidAmount").val();
				var bidAmount_lenght=bidAmount.length;	
				if(bidAmount==""){
					$("#email_input_error").html("Tip amount is required");
					return false;
				}else if(bidAmount_lenght>8){
					$("#email_input_error").html("Tip amount must be less than 8 characters.");
					return false;
				}
			});
	
	
		$("#submitRequestBtn").click(function(){
			var status=jQuery("#contact_form").validationEngine("validate");
			var bidAmount=$("#bidAmount").val();
						var bidAmount_lenght=bidAmount.length;	
				if(bidAmount==""){
					$("#email_input_error").html("Tip amount is required");
					return false;
				}else if(bidAmount_lenght>8){
					$("#email_input_error").html("Tip amount must be less than 8 characters.");
					return false;
				}
			var base_url="<?php echo base_url();?>";
			if(status){
				$("#submitRequestBtn").prop("disabled",true);
				$("#cancelBtn").css("pointer-events","none");
				$("#changeSongBtn").css("pointer-events","none");
				$('.dvLoading').show();
				$('.container').css("opacity","0.6"); 
				var DisplaySongName=$("#DisplaySongName").val();
				var songID=$("#songID").val();
				var customerName=$("#customerName").val();
				var bidAmount=$("#bidAmount").val();
				if(bidAmount==""){
					$("#email_input_error").html("Tip amount is required");
					return false;
				}else if(bidAmount_lenght>8){
					$("#email_input_error").html("Tip amount must be less than 8 characters.");
					return false;
				}
				var customerComments=$("#customerComments").val();
				userdata={
				'songName': DisplaySongName,
				'tip': bidAmount, 
				'songID':songID,
				'customerName': customerName,
				'customerComment':customerComments
				};
				
				$.ajax({
				   url: base_url+'index.php/webservices/request/createRequest',
				   type: 'POST',
				    data: userdata,
				   headers: {
					'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
					'Auth-Key':'<?php echo AUTH_KEY;?>',
					'ShowId':'877EB49B-4310-46BB-1160-DFFAE150EF15'
				   },
				   error: function() {
					  $("#comman_error_message").html("Something is wrong...please try after some time");
					  $("#submitRequestBtn").prop("disabled",false);
					  $("#cancelBtn").css("pointer-events","unset");
					  $("#changeSongBtn").css("pointer-events","unset");
				   },
				   success: function(data) {
					    $('.dvLoading').fadeOut(300);
						$('.container').css("opacity","none");
						console.log(data);
						if(data.status){
							sessionStorage.setItem("bidAmount",bidAmount);
							sessionStorage.setItem("customerName",customerName);
							sessionStorage.setItem("customerComment",customerComments);
							sessionStorage.setItem("payment_success", true);
							window.location.href = base_url+"index.php/paymentsuccess";
						}else{
							sessionStorage.setItem("payment_success", false);
							window.location.href = base_url+"index.php/paymentsuccess";
						}
						
					}
				});
			}
		});	
		$( document ).ready(function() {
			var SongID=sessionStorage.getItem("SongID");
			var SongArtist=sessionStorage.getItem("SongArtist");
			var SongName=sessionStorage.getItem("SongName");
			console.log(SongArtist);
			$("#songID").val(SongID);
			$("#DisplaySongName").val(SongName);
			$("#DisplayArtistName").html(SongArtist);
			console.log(SongName);
			$('.dvLoading').fadeOut(300);
			$(".container").show();
		});
	
	</script>
   </body>
</html>