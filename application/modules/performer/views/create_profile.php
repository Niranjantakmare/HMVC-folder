<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
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
	<title>Create Performer Profile</title>
	 <style>
#dvLoading
{
	background: url(../assets/images/processing.gif) no-repeat center center;
    height: 100px;
    width: 100px;
    position: fixed;
    z-index: 1000;
    left: 45%;
    top: 45%;
    margin: -25px 0 0 -25px;
}
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
   <body class="create-profile">
        <div id="dvLoading"></div>
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
			<div class="paysuccesstitle">
			<h4>Create Profile</h4>
		
			</div>
			
		
			</div><!--col-12 col-md-12 col-sm-12 end here-->
			
		<div class="col-12 col-md-12 col-sm-12 paymentsuccess profilecreate">
		 <form id="CreateProfileForm" method="post" enctype="multipart/form-data">
         <!--img src="images/Micheal.png" class="img-responsive" alt="micheal-img"/> -->
		 <div class="picture-container">
        <div class="picture">
            <img src="<?php echo base_url();?>assets/images/Micheal.png" class="picture-src" id="wizardPicturePreview" title="">
            <input type="file" id="Changeprofilephoto" name="Changeprofilephoto" class="">
        </div>
			<span class="form_input_errors" style="padding-left:0px" id="image_error_message"></span>
         <h6 class="">Choose Picture</h6>
	
    </div>
		  <span class="form_input_errors" id="comman_error_message"></span>
		
			  <div class="form-group">
					<input type="text" class="form-control validate[required,maxSize[25],custom[onlyLetterSomeSpecialChars]]" id="profileFirstName" placeholder="Enter First Name" autocomplete="off"  data-errormessage-custom-error="Please enter letters or numbers with some special characters."  id="customerName" data-errormessage-value-missing="First name is required..!" >
			  </div>
			  <div class="form-group">
					<input type="text" class="form-control validate[required,maxSize[25],custom[onlyLetterSomeSpecialChars]]" id="profileLastName" placeholder="Enter Last Name" autocomplete="off" id="customerName" data-errormessage-value-missing="Last name is required..!" data-errormessage-custom-error="Please enter letters or numbers."   >
			  </div>
			   <div class="form-group">
					<input type="text" class="form-control validate[required,maxSize[50],custom[onlyLetterSomeSpecialChars]]" id="profileStageName" placeholder="Enter Stage Name"   autocomplete="off" id="customerName" data-errormessage-value-missing="Stage name is required..!" >
			  </div>
			  
			  <div class="form-group">
					<input type="email" class="form-control validate[required,maxSize[255],custom[email]]" id="profileEmail" aria-describedby="emailHelp" autocomplete="off" placeholder="Enter Email ID" data-errormessage-custom-error="Please enter valid email address" id="customerName" data-errormessage-value-missing="Email address is required!" >
			  </div>
			 
			  <div class="form-group">
					<input type="password" autocomplete="off" class="form-control validate[required,custom[onlyLetterSomeSpecialChars],minSize[6],maxSize[20]]" id="profilePassword" placeholder="Set Password" data-errormessage-equals-error="Please enter only letters"  id="customerName" data-errormessage-value-missing="Password is required!" >
			  </div>
			  <div class="form-group">
					<input type="password" class="form-control validate[required,custom[onlyLetterSomeSpecialChars],equals[profilePassword],minSize[6],maxSize[20]]" id="profileConfirmPassword" placeholder="Confirm Password" autocomplete="off" data-errormessage-equals-error="Please enter only letters" id="customerName" data-errormessage-value-missing="Confirm password is required!" >
			  </div>
			  <div class="form-group"> 
					<input type="text" autocomplete="off" class="form-control validate[required,custom[onlyLetterSomeSpecialChars],maxSize[500]]" id="profileMessage" placeholder="Message" data-errormessage-custom-error="Please enter letters or numbers with some special characters only" id="customerName" data-errormessage-value-missing="Message is required!" >
			  </div>
			  <div class="row">
				<div class="col-12 col-md-6 col-sm-6 col-xs-6">
				  <button type="button" id="createProfileSubmitBtn" class="btn btn-primary custompaddingbtn createcustbtn">Create</button>
				  </div>
				  <div class="col-12 col-md-6 col-sm-6 col-xs-6">
				  <a id="cancelBtn" href="<?php echo base_url();?>index.php/login"><button type="button" class="btn btn-primary custompaddingbtn canclerightbtn">Cancel</button></a>
				  </div>
			</div>
		</form>
		 
        </div>
			
			
         </div><!--row-->
		 
      </div><!--container-->
   </body>
</html>

<script type="text/javascript">
	  	jQuery(document).ready(function(){
			var status=jQuery("#CreateProfileForm").validationEngine({promptPosition: 'inline'});
			});
			

			
		$(document).on('change','#Changeprofilephoto', function(){
			 var Changeprofilephoto=$('#Changeprofilephoto').val();
			  var ext=Changeprofilephoto.replace(/^.*\./, '');
			  arr = [ "jpeg","jpg","png","gif","PNG","JPEG","GIF","JPG"];
			  var file = this.files[0];
			  var x = document.getElementById("Changeprofilephoto");
				if( x.files[0] ) {
					if( x.files[0].size > 10000000 ) {
						$("#image_error_message").html(" File size should be less than 10MB");
						 return false;
					 }
					if( $.inArray( ext, arr ) < 0 ) {
						  $("#image_error_message").html("Invalid file type only jpeg,jpg & png files allowed");
						  return false;
					 }else{
						 $("#image_error_message").html("");
						 var reader = new FileReader();
						 reader.onload = imageIsLoaded;
						 reader.readAsDataURL(this.files[0]);
					 }  
				}
		});
                  
		function imageIsLoaded(e) {
			$("#file").css("color","green");
			$('#image_preview').css("display", "block");
			$('#wizardPicturePreview').attr('src', e.target.result);
			$('#wizardPicturePreview').attr('height', '106x');
		}
			 
			
		function checkEmailAddress(field, rules, i, options){
			/*if (field.val() != "niranjan@gsss.com") {
				return options.allrules.validate2fields.alertText;
			}
			*/
			var email_address=field.val() ;
	
			var base_url="<?php echo base_url();?>";
			$.ajax({
			url: base_url+'index.php/webservices/performers/CheckEmailAddressAlreadyExist',
			type: 'GET',
			headers: {
				'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
				'Auth-Key':'<?php echo AUTH_KEY;?>',
				'Email_address': email_address
			},
			error: function() {
				return options.allrules.validate2fields.alertText;
			},
			success: function(data) {
				//var addd=data.parse();
				
				return options.allrules.validate2fields.alertText;
			}
		});
		}
		
		$("#createProfileSubmitBtn").click(function(){
			var status=jQuery("#CreateProfileForm").validationEngine("validate");
			var Changeprofilephoto=$('#Changeprofilephoto').val();
			var ext=Changeprofilephoto.replace(/^.*\./, '');
			arr = [ "jpeg", "jpg","png","gif","PNG","JPEG","GIF","JPG" ]; 		
			var x = document.getElementById("Changeprofilephoto");
			if(Changeprofilephoto==""){
				$("#image_error_message").html("Please select profile image to upload");
					return false;
			}else{
				$("#image_error_message").html("");
			}
			if( x.files[0] ) {
				if( x.files[0].size > 10000000 ) {
					$("#image_error_message").html(" File size should be less than 10MB");
					return false;
				}
			if( $.inArray( ext, arr ) < 0 ) {
				$("#image_error_message").html("Invalid file type only jpeg,jpg & png files allowed");
				return false;
				}   
			}
			var file_data = $("#Changeprofilephoto").prop("files")[0]; 
			var base_url="<?php echo base_url();?>";
			if(status){
				$("#createProfileSubmitBtn").prop("disabled",true);
				$("#cancelBtn").css("pointer-events","none");
				$('#dvLoading').show();
				$('.container').css("opacity","0.6"); 
				var profileFirstName=$("#profileFirstName").val();
				var profileLastName=$("#profileLastName").val();
				var Changeprofilephoto=$('#Changeprofilephoto').val();
				var profileStageName=$("#profileStageName").val();
				var profileEmail=$("#profileEmail").val();
				var profilePassword=$("#profilePassword").val();
				var profileConfirmPassword=$("#profileConfirmPassword").val();
				var profileMessage=$("#profileMessage").val();
				userdata={
				'profileFirstName': profileFirstName,
				'changeprofilephoto':file_data,
				'profileLastName': profileLastName, 
				'profileStageName':profileStageName,
				'profileEmail': profileEmail,
				'profilePassword': profilePassword,
				'profileConfirmPassword': profileConfirmPassword,
				'profileMessage':profileMessage
				};
				var form_data = new FormData();                 
				form_data.append("profileFirstName", profileFirstName);           
				form_data.append("changeprofilephoto", file_data);
				form_data.append("profileLastName", profileLastName);
				form_data.append("profileStageName", profileStageName);
				form_data.append("profileEmail", profileEmail);
				form_data.append("profilePassword", profilePassword);
				form_data.append("profileConfirmPassword", profileConfirmPassword);
				form_data.append("profileMessage", profileMessage);
				
				
				$.ajax({
				   url: base_url+'index.php/webservices/performers/create',
				   type: 'POST',
				    data: form_data,
					  cache: false,
                contentType: false,
                processData: false,
				   dataType: "json",
					    mimeType: "multipart/form-data",
				   headers: {
					'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
					'Auth-Key':'<?php echo AUTH_KEY;?>',
					},
				   error: function(data) {
					   console.log(data);
					  $("#comman_error_message").html("Something is wrong...please try after some time");
					  $("#createProfileSubmitBtn").prop("disabled",true);
				   },
				   success: function(data) {
					   console.log(data);
					if(data.status==200){
						var  isEmailValidate=data.isEmailValidate;
						
						if(isEmailValidate==1){
							sessionStorage.setItem("registration_message", "You have been sucessfully register...please login with email and password");
							$('#dvLoading').fadeOut(300);
							$('.container').css("opacity",3.2);
							$("#createProfileSubmitBtn").prop("disabled",true);
							  $("#cancelBtn").css("pointer-events","none");
							window.location.href = base_url+"index.php/login";
						}else{
							$('#dvLoading').fadeOut(300);
							$('.container').css("opacity",3.2);
							$("#createProfileSubmitBtn").prop("disabled",false);
							  $("#cancelBtn").css("pointer-events","unset");
							message=data.message;
							$('#profileEmail').validationEngine('showPrompt', message, 'inline');
						}
					}else{
						message=data.message;
						$('#comman_error_message').html(message);
						$('#dvLoading').fadeOut(300);
						$('.container').css("opacity",3.2);
						$("#createProfileSubmitBtn").prop("disabled",false);
						$("#cancelBtn").css("pointer-events","unset");
						
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
			$('#dvLoading').fadeOut(300);
			$(".container").show();
	});
	
</script>
