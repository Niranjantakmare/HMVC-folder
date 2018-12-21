<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/font-awesome.min.css"/>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.min.js"></script>
<title>Login Page</title>

<script type="text/javascript">
	if(sessionStorage.getItem('token')){
		var base_url="<?php echo base_url();?>";
		window.location.href = base_url+"home.html";
	}
</script>
</head>

<body>
<div class="container">
  <div class="row">
    <div class="col-12 col-md-12 col-sm-12 custommaintopsection">
      <div class="header">
        <div class"logosection"> 
          <!--<h1>TRL <span class="innerspan">LOGO</span></h1>--> 
          <img src="<?php echo base_url(); ?>assets/images/TRL-logo.png" class="img-responsive" alt="trl-logo"  /> </div>
      </div>
    </div>
    <!--col-12 col-md-12 col-sm-6 custommaintopsection end here-->
    
    <div class="col-12 col-md-12 col-sm-12 formsection">
      <form id="loginForm" >
	    <span class="form_input_errors" id="comman_error_message"></span>
        <div class="form-group">
          <input type="email" class="form-control runningborder"  autocomplete="off" name="inputEmail" id="inputEmail" aria-describedby="emailHelp" placeholder="briansmith@trl.com" />
		 <span class="focus-border"></span>

		  <span  class="form_input_errors" id="email_input_error"></span>
        </div>
        <div class="form-group"> <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
          <input type="password" class="form-control runningborder" name="inputPassword" id="inputPassword" placeholder="*******************" />
		  <span class="focus-border"></span>
		  <span class="  form_input_errors"  id="password_input_error"></span>
          <span class="rightsection">Forgot password?</span> </div>
        </br>
        </br>
        <button type="button" id="loginFormSubmit" class="btn btn-primary customfullbutton">LOGIN</button>
      </form>
    </div>
    <!--formsection end here-->
    <div class="col-12 col-md-12 col-sm-12 footet-top"></div>
    <div class="col-12 col-md-12 col-sm-12 footersetion"> <span class="bottomsectioncenter">New prefomer? Create profile</span> </div>
  </div>
  <!--row end here--> 
</div>
<!--Container end here-->
</body>
<script type="text/javascript">

	if(sessionStorage.getItem('token')){
		var base_url="<?php echo base_url();?>";
		window.location.href = base_url+"home.html";
	}
	
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
			}else if(email_lenght<5 || email_lenght>25){
				$("#email_input_error").html("email id must be greater than 5 and less than 25 characters.");
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
			
		}else if(email_lenght<5 || email_lenght>25){
			$("#email_input_error").html("email id must be greater than 5 and less than 25 characters.");
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
			   url: base_url+'auth/login?email_id='+email_id+'&password='+password,
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
					data_arr=JSON.parse(data);
					console.log(data_arr);
					if(data_arr.status==200){
						sessionStorage.setItem("token", data_arr.token);
						sessionStorage.setItem("id", data_arr.id);
						 window.location.href = base_url+"home.html";
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


