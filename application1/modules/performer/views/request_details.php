<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/font-awesome.min.css"/>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  <title>Request Details</title>
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

</style>
   </head>
   <body class="requestdetails">
     <div id="dvLoading"></div>
      <div class="container" style="display:none">
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
			<h1>Hurry Up !</h1>
			<span>Highest bidder goes up next</span>
			</div>
			<div class="requsetbotton">
			<div class="input-group mb-3">
			 <input type="text" class="form-control" id="DisplaySongName" name="DisplaySongName" value="Baby One More Time" placeholder="Baby One More Time" aria-label="Baby One More Time" aria-describedby="basic-addon2" readonly/>
			 <span id="DisplayArtistName" class="inputplaceholder">Britney Spears</span>
			<a style="cursor:pointer;" href="<?php base_url();?>songs-list.html"><div class="input-group-append">
				<button class="btn btn-outline-secondary changesong" type="button"><span>Change</span>Song</button>
			  </div></a>
			</div>
			</div>
			
			
			<form class="well form-horizontal requstform" action=" " method="post"  id="contact_form">
				<fieldset>
				<!-- Text input-->
	<input type="hidden" name="songID" id="songID">
				<div class="form-group">
				  <div class="col-md-12 inputGroupContainer">
				  <div class="input-group img-input">
				  <span class="input-group-addon ">
				  <img src="<?php echo base_url();?>assets/images/Request-Details.png" class="img-responsive" alt="request"  /></span>
				  <input  name="" placeholder="$    100.00" class="form-control" id="searchinput" type="text">
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
					<a style="cursor:pointer;" href="<?php base_url();?>songs-list.html"><button type="button" class="btn btn-warning" >Cancel</button></a>
				  </div>
				  <div class="col-md-6 innerbuttoncustomsecond">
					<button type="button" class="btn btn-warning" >Done</button>
				  </div>
				</div>
				
				</fieldset>
				</form>
			
			
			
			</div><!--col-12 col-md-12 col-sm-12 end here-->
			
            
         </div>
      </div>
	  <script type="text/javascript">
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
   </body>
</html>