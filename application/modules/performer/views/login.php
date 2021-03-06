<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/font-awesome.min.css"/>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<title>Login Page</title>

<script type="text/javascript">
	if(sessionStorage.getItem('token')){
		var base_url="<?php echo base_url();?>";
		window.location.href = base_url+"index.php/myshow";
	}
</script>
<style>
.hand_cursor{
	  cursor: pointer;
	  text-decoration: none;
}
a.hand_cursor:hover {
    color: #0056b3;
    text-decoration: none;
}

</style>
</head>

<body>
  <div class="dvLoading" style="display:none"></div>
  <div class="container"  >
  <div class="row">
    <div class="col-12 col-md-12 col-sm-12 custommaintopsectioninner">
      <div class="header">
        <div class"logosection"> 
          <!--<h1>TRL <span class="innerspan">LOGO</span></h1>--> 
          <img src="<?php echo base_url(); ?>assets/images/logo.png" class="img-responsive" alt="trl-logo"  /> </div>
		  <!--<img src="<?php echo base_url(); ?>assets/images/TRL-logo.png" class="img-responsive" alt="trl-logo"  /> </div>-->
      </div>
    </div>

    <!--col-12 col-md-12 col-sm-6 custommaintopsection end here-->
    
    <div class="col-12 col-md-12 col-sm-12 formsection">
      <form id="loginForm" >
	  
		<div style="display:none" id="comman_success_message" class="alert alert-success">

		</div>

<div style="display:none" id="comman_errormsg_message" class="alert alert-danger">

		</div>

	    <span class="form_input_errors" id="comman_error_message"></span>
        <div class="form-group">
          <input type="email" class="form-control runningborder"  autocomplete="off" name="inputEmail" id="inputEmail" aria-describedby="emailHelp" placeholder="User Name" />
		 <span class="focus-border"></span>

		  <span  class="form_input_errors" id="email_input_error"></span>
        </div>
        <div class="form-group"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
          <input type="password" class="form-control runningborder" name="inputPassword" id="inputPassword" placeholder="Password" />
		  <span class="focus-border"></span>
		  <span class="  form_input_errors"  id="password_input_error"></span>
          <a href="<?php echo base_url();?>index.php/forgotpassword"><span class="rightsection">Forgot password?</span></a></div>
        </br>
        </br>
        <button type="button" id="loginFormSubmit" class="btn btn-primary customfullbutton">LOGIN</button>
      </form>
    </div>
    <!--formsection end here-->
    <div class="main-footer"> 
    <div class="col-12 col-md-12 col-sm-12 footet-top"></div>
	
    <div class="col-12 col-md-12 col-sm-12 footersetion"> <span class="bottomsectioncenter">New prefomer?<a class="hand_cursor" href="<?php echo base_url()?>index.php/createprofile"> Create profile</a></span> </div>
	</div>
  </div>
  <!--row end here--> 
</div>
<!--Container end here-->
</body>
<script type="text/javascript">

	if(sessionStorage.getItem('token')){
		var base_url="<?php echo base_url();?>";
		window.location.href = base_url+"index.php/myshow";
	}
	
	$(document).ready(function(){
				$("#comman_success_message").hide();
			if(sessionStorage.getItem('registration_message')){
					var registration_successfully_message=sessionStorage.getItem('registration_message');
					console.log(registration_successfully_message);
					$("#comman_success_message").html(registration_successfully_message);
					sessionStorage.removeItem('registration_message');
					window.setTimeout(function() {
					$("#comman_success_message").fadeTo(8000, 0).slideUp(5000,function(){
					$(this).remove(); 
					});
					}, 5000);
					$("#comman_success_message").show();
			}
			
			$("#comman_errormsg_message").hide();
			if(sessionStorage.getItem('sessionExpiryMsg')){
					var sessionExpiryMsg=sessionStorage.getItem('sessionExpiryMsg');
					console.log(sessionExpiryMsg);
					$("#comman_errormsg_message").html(sessionExpiryMsg);
					sessionStorage.removeItem('sessionExpiryMsg');
					window.setTimeout(function() {
					$("#comman_errormsg_message").fadeTo(8000, 0).slideUp(5000,function(){
					$(this).remove(); 
					});
					}, 5000);
					$("#comman_errormsg_message").show();
			}
			
			
	});
	

	function checkValidation(){
		$("#email_input_error").html("");
		$("#password_input_error").html("");

		var email = $("#inputEmail").val();
		var password = $("#inputPassword").val();
		var email_id=$.trim(email);
		var password=$.trim(password);
		var email_lenght=email_id.length;
		var password_lenght=password.length;
		var field_errors=0;
		if(email_id==""){
				$("#email_input_error").html("You must provide a email id");
				field_errors=1;
			}else if(email_lenght<5 || email_lenght>255){
				$("#email_input_error").html("email id must be greater than 0 and less than 255 characters.");
				field_errors=1;
			}
			if(password==""){
				$("#password_input_error").html("You must provide a password.");
				 field_errors=1;
			}else if(password_lenght<6 || password_lenght>20){
				$("#password_input_error").html("Password must be greater than 6 and less than 20 characters.");
				 field_errors=1;
			}
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
			if (!emailReg.test(email_id)) {
				$("#email_input_error").html("You must provide valid email id");
				 field_errors=1;
			}
		if(field_errors==1){
			setTimeout(function(){
			//	$("#email_input_error").html("");
				//$("#password_input_error").html("");
			}, 5000) // time in millisecond for as long as you like
		}
		
		return field_errors;
	}
	
	$('#inputEmail').bind('blur change', function(){
		$("#email_input_error").html("");
		var email = $("#inputEmail").val();
		var email_id=$.trim(email);
		var email_lenght=email_id.length;
		var email_error=0;
		if(email_id==""){
			$("#email_input_error").html("You must provide a email id");
			email_error=1;
			
		}else if(email_lenght<5 || email_lenght>255){
			$("#email_input_error").html("email id must be greater than 0 and less than 255 characters.");
			email_error=1;
		}
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		if (!emailReg.test(email_id)) {
			$("#email_input_error").html("You must provide valid email id");
			email_error=1;
		}
		if(email_error==1){
			setTimeout(function(){
			//	$("#email_input_error").html("");
			}, 5000) // time in millisecond for as long as you like
		}
	});
	
	$('#inputPassword').bind('blur change', function() {
		var password_error=0;
		$("#password_input_error").html("");	
		var password = $("#inputPassword").val();
		var password_lenght=password.length;
		if(password==""){
			$("#password_input_error").html("You must provide a password.");
			password_error=1;
		}else if(password_lenght<6 || password_lenght>20){
			$("#password_input_error").html("Password must be greater than 6 and less than 20 characters.");
			password_error=1;
		}
		
		if(password_error==1){
			setTimeout(function(){
				//$("#password_input_error").html("");
			}, 5000) // time in millisecond for as long as you like
		}	
	});

	$("#loginFormSubmit").click(function(){
		$('.dvLoading').show();
				$('.container').css("opacity","0.6"); 
		var email = $("#inputEmail").val();
		var base_url="<?php echo base_url();?>";
		var password = $("#inputPassword").val();
		var email_id=$.trim(email);
		var password=$.trim(password);
		field_errors=checkValidation();
		console.log(field_errors);
		if(field_errors==1){
			return false;
		}
					
		if(field_errors==0){
			$.ajax({
			   url: base_url+'index.php/auth/login?email_id='+email_id+'&password='+password,
			   type: 'POST',
			   headers: {
				'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
				'Auth-Key':'<?php echo AUTH_KEY;?>',
				'Content-Type':'application/x-www-form-urlencoded'
			   },
			   error: function() {
				  $("#comman_error_message").html("Something is wrong...please try after some time");
			   },
			   success: function(data) {
				    $('.dvLoading').fadeOut(300);
					$('.container').css("opacity","none");
					data_arr=JSON.parse(data);
					console.log(data_arr);
					if(data_arr.status==200){
						sessionStorage.setItem("token", data_arr.token);
						sessionStorage.setItem("user_id", data_arr.user_id);
						 window.location.href = base_url+"index.php/myshow";
					}else{
						message_arr=data_arr.message.split(',');
						message_var=message_arr.join("<br/>");
						console.log(message_var);
						$("#comman_error_message").html(message_var);
					}
			   }
			});
		}
	});
	
</script>
</html>


