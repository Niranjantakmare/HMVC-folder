<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
     <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/font-awesome.min.css"/>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.min.js"></script>
<title>Request Details</title>	  
   </head>
   <body>
      <div class="container">
         <div class="row">
            <div class="col-12 col-md-12 col-sm-12 custommaintopsectioninner">
               <nav class="navbar navbar-inverse customnav">
                  <div class="navbar-header customheader">
                     <button type="button" class="navbar-toggle customtoggle" data-toggle="collapse" data-target="#myNavbar">
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>
                     <span class="icon-bar"></span>                        
                     </button>
                     <a class="navbar-brand custombrand" href="#">Total Requests Live</a>
                  </div>
                  <div class="collapse navbar-collapse" id="myNavbar">
                     <!-- <ul class="nav navbar-nav">
                        <li class="active"><a href="#"></a></li>
                        <li><a href="#"></a></li>
                        <li><a href="#"></a></li>
                        </ul>-->
                  </div>
               </nav>
            </div>
            <!--col-12 col-md-12 col-sm-12 end here-->
			
			<div class="col-12 col-md-12 col-sm-12">
			<div class="requesttitles">
			<h1>Hurry Up!</h1>
			<span>Highest bidder goes up next</span>
			</div>
			<div class="requsetbotton">
			<div class="input-group mb-3">
			  <input type="text" class="form-control" placeholder="Baby One More Time" aria-label="Baby One More Time" aria-describedby="basic-addon2"/>
			  <span class="inputplaceholder">Britney Spears</span>
			  <div class="input-group-append">
				<button class="btn btn-outline-secondary" type="button"><span>Change</span>SONG</button>
			  </div>
			</div>
			</div>
			
			
			<form class="well form-horizontal requstform" action=" " method="post"  id="contact_form">
				<fieldset>
				<!-- Text input-->

				<div class="form-group">
				  <div class="col-md-12 inputGroupContainer">
				  <div class="input-group img-input">
				  <span class="input-group-addon ">
				  <img src="<?php echo base_url();?>assets/images/Request-Details.png" class="img-responsive" alt="request"  /></span>
				  <input  name="first_name" placeholder="$    100.00" class="form-control" id="searchinput" type="text">
				  <span id="searchclear" class="glyphicon glyphicon-remove-sign"></span>
					</div>
				  </div>
				</div>

				<!-- Text input-->

				<div class="form-group"> 
					<div class="col-md-12 inputGroupContainer">
					<div class="input-group img-input">
					<span class="input-group-addon"><img src="<?php echo base_url();?>assets/images/user.png" class="img-responsive" alt="user"  /></span>
					<input name="last_name" placeholder="Enter your name" class="form-control"  type="text">
					</div>
				  </div>
				</div>

				<div class="form-group">
					<div class="col-md-12 inputGroupContainer">
					<div class="input-group img-input">
					<span class="input-group-addon">
						<img src="<?php echo base_url();?>assets/images/Request-Detailscomment.png" class="img-responsive" alt="detailscomment"  /><span>
							<textarea class="form-control" name="comment" placeholder="Comments"></textarea>
				  </div>
				  </div>
				</div>
				<!-- Button -->
				
				<div class="form-group col-md-12 bottomcustombuttons">
				  <div class="col-md-6 innerbuttoncustomfirst">
					<button type="submit" class="btn btn-warning" >Cancle</button>
				  </div>
				  <div class="col-md-6 innerbuttoncustomsecond">
					<button type="submit" class="btn btn-warning" >Done</button>
				  </div>
				</div>
				
				</fieldset>
				</form>
			
			
			
			</div><!--col-12 col-md-12 col-sm-12 end here-->
			
            
         </div>
      </div>
	  <script>
	  $("#searchclear").click(function(){
       $("#searchinput").val('');
        });
	  </script>
   </body>
</html>