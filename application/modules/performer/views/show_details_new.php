<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/font-awesome.min.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	<title>Shows details</title>
	   <style>
.dvLoading
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
   <?php 
   
   date_default_timezone_set('Asia/Calcutta');
   ?>
   <body>
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
			<div style="display:none" id="comman_errormsg_message" class="alert alert-danger">

		</div>
		<div style="display:none" id="comman_success_message" class="alert alert-success">

		</div>
			
			<div class="col-12 col-md-12 col-sm-12 myshowmain">
			<div class="timedatesection">
			<span class="datesection"> Date: <?php echo date('d/m/Y');?></span>
			<span class="timesection"> Time: <?php echo date('H:i');?></span>
			</div>
		    <div class="toprightstatussec">
			<span id="show_status">Show Status:Scheduled</span>
			</div>
			<div   class="paysuccesstitle mycustcurrenttitle">
			<h4 id="show_name">Show:Show Name</h4>
			<div class="showdescriptionsection">
			<span class="innershowtitle">Show Description:</span><span class="innerdesripleft" id="show_description">Lorem Ipsum is simply dummy text of the printing and typesetting industry</span>
			</div>
			</div>
			 <h5 id="showSongQueue" style="display:none" class="h5songtitle">Song Queue</h5>
			 <input type="hidden" id="noOfSong" value="0" name="noOfSong">
			 <input type="hidden" id="totalCount" value="0" name="totalCount">
			 <div  style="display:none" id="showSongQueueList">
					<div class="row innercustommyshow">
					<div class="col-12 col-md-6 col-sm-6 col-xs-6 tableovrleftsec"><span id="totalShowCount" >Total Songs:</span></div>
					<div class="col-12 col-md-6 col-sm-6 col-xs-6 tableovrrightsec"><span id="totalShowsTip">Tips:</span></div>
					</div>
					<div class="table-wrapper-scroll-y">
					   
					<table class="table table-striped custcurrentshow showdetailsspan">
					  <tbody id="searchSongsTable">
						<div id="loadingSearchSongsInnerDiv" class="ajax_table_load text-center" >
									<p><img src="<?php echo base_url();?>assets/images/loader.gif">Loading more songs</p>
								</div>
					   </tbody>
					</table>
					   <div   class="ajax_table_load text-center loadingSearchSongs" style="display:none">
								<p><img src="<?php echo base_url();?>assets/images/loader.gif">Loading more songs</p>
								</div>
					</div>
				</div>
		
		
						<div  style="display:none" id="scheduleShowQueue" class="row editshowmainsection">
						
						   <div class="currentshow col-12 col-md-4 col-sm-4 col-xs-4">
						   <img src="<?php echo base_url();?>assets/images/right-arrow-circular-button.png" class="deleteimg" id="startPerformerShow" title="">
						   <span class="addshowspan">Start Show</span>
						   </div>
						   
						   <div class="currentshowright col-12 col-md-4 col-sm-4 col-xs-4" style="text-align: center;">
						   <img src="<?php echo base_url();?>assets/images/icon-edit-property.png" class="imgshow" id="" title="">
						   <span class="addshowspan">Edit Show</span>
						   
						   </div>
						   
						   <div class="currentshowright col-12 col-md-4 col-sm-4 col-xs-4">
						     <a href="<?php echo base_url()."index.php/myshow";?>"><img src="<?php echo base_url();?>assets/images/Addshow.png" class="imgshow" id="" title=""></a>
						   <span class="addshowspan">My Shows</span>
						   
						   </div>
					   </div>
						 
						<div  style="display:none" id="completedSongsDiv" class="row editshowmainsection">
						   <div class="currentshow col-12 col-md-6 col-sm-6 col-xs-6">
						   <img src="<?php echo base_url();?>assets/images/delete.png" class="deleteimg" id="" title="">
						   <span class="addshowspan">Delete Show</span>
						   
						   </div>
						   
						   <div class="currentshowright col-12 col-md-6 col-sm-6 col-xs-6">
						     <a href="<?php echo base_url()."index.php/myshow";?>"><img src="<?php echo base_url();?>assets/images/Addshow.png" class="imgshow" id="" title=""></a>
						   <span class="addshowspan">My Shows</span>
						   
						   </div>
						</div>  
						<div  style="display:none" id="CurrentShowDiv" class="row editshowmainsection">
						   <div class="currentshow col-12 col-md-6 col-sm-6 col-xs-6">
<img src="<?php echo base_url();?>assets/images/boximg.png" class="imgshow" id="endPerformerShow" title="">
						   <span class="addshowspan">End Show</span>
						   
						   </div>
						   
						   <div class="currentshowright col-12 col-md-6 col-sm-6 col-xs-6">
						   <a href="<?php echo base_url()."index.php/myshow";?>"><img src="<?php echo base_url();?>assets/images/Addshow.png" class="imgshow" id="" title=""></a>
						   <span class="addshowspan">My Shows</span>
						   	
						   </div>
						</div>   
						
			</div><!--col-12 col-md-12 col-sm-12 end here-->
	    </div><!--row-->
	   </div><!--container-->
	 
   </body>
	<script type="text/javascript">
  
	
  </script> 
  
  <script type="text/javascript">
	$( document ).ready(function() {
		
		
		
		
		
		if(!sessionStorage.getItem('token')){
			var base_url="<?php echo base_url();?>";
			window.location.href = base_url+"index.php/login";
		}else{
			if(sessionStorage.getItem('showID')){
				var showID=sessionStorage.getItem('showID');
			}
			var base_url="<?php echo base_url();?>";
			var token=sessionStorage.getItem('token');
			var TotalShowsBidAmount=0;
			var user_id=sessionStorage.getItem('user_id');
			$.ajax({
				url: base_url+'index.php/webservices/performers/getShowDetails/'+showID,
				type: 'GET',
				headers: {
				'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
				'Auth-Key':'<?php echo AUTH_KEY;?>',
				'Authorization':token,
				'User-ID':user_id
				}, 
				error: function() {
					$(".container").show();
				},
				success: function(response) {
					console.log(response);
					if(response.status==401){
						sessionStorage.setItem("sessionExpiryMsg", "Your session has been expired.");
						var base_url="<?php echo base_url();?>";
						window.location.href = base_url+"index.php/login";
						return false;
					}
					if(response.status==1){
						var showname=response.data.showname;
						var showDescription=response.data.showDescription;
						var status=response.data.status;
						var showdate=response.data.date;
						var showtime=response.data.time;
						var showstatus="";
							if(status=="S"){
								showstatus=" Scheduled";
								$("#scheduleShowQueue").show();
								$("#showSongQueueList").hide();
							}else if(status=="L"){
								showstatus=" Live";
								$("#showSongQueue").show();
								$("#CurrentShowDiv").show();
								$("#showSongQueueList").show();
							}else{
								showstatus=" Ended";
								$("#showSongQueue").show();
								$("#completedSongsDiv").show();
								$("#showSongQueueList").show();
							}
					
						$("#show_status").html("Show Status: "+showstatus);
						$("#show_name").html("Show: "+showname);
						$("#show_description").html("  "+showDescription);
						$(".timesection").html("Time:  "+showtime);
						$(".datesection").html("Date: "+showdate);
					
						if(status!="S"){
							var page = $("#noOfSong").val();
							var limit=10;
							var searchString=$.trim($("#searchStr").val());
							if(status=="L"){
								var URL="<?php echo base_url(); ?>index.php/webservices/request/getShowSonglist/"+page+"/10/";
							}else{
								var URL="<?php echo base_url(); ?>index.php/webservices/request/getShowSonglist/"+page+"/10/S";
							}
							loadMoreData(URL,1);
							page++;
							$("#noOfSong").val(page);
						}else{
							
							$('.dvLoading').fadeOut(300);
							$(".container").show();
						}
	
					}
				}
			});
			
			if(sessionStorage.getItem('success_message')){
				var registration_successfully_message=sessionStorage.getItem('success_message');
				console.log(registration_successfully_message);
				$("#comman_success_message").html(registration_successfully_message);
				sessionStorage.removeItem('success_message');
				window.setTimeout(function() {
				$("#comman_success_message").fadeTo(8000, 0).slideUp(5000,function(){
				$(this).remove(); 
				});
				}, 5000);
				$("#comman_success_message").show();
			}
		
		
		}
		
	
	
	
	$(document).ready(function(){
		$( ".table-wrapper-scroll-y" ).scroll(function() {
			totalCount=$("#totalCount").val();
			var limit=10;
			var page = $("#noOfSong").val();
			var totalItems=page*limit;
			console.log(totalItems);
			console.log(page);
			console.log(totalCount);
			if (totalCount > totalItems) {
					if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
					$(".loadingSearchSongs").show();
					page++;
					$("#noOfSong").val(page);
					//var URL="<?php echo base_url(); ?>index.php/webservices/songs/performerSongsList/"+totalItems+"/10/";
					var URL="<?php echo base_url(); ?>index.php/webservices/request/getAllCurrentShowRequests/"+totalItems+"/10/";
					loadMoreData(URL,0);
					
				}
			}
		});
		
		
		
		$("#startPerformerShow").click(function(){
			$('.dvLoading').show();
			$('.container').css("opacity","0.6"); 
			var base_url="<?php echo base_url();?>";
			if(sessionStorage.getItem('showID')){
				var showID=sessionStorage.getItem('showID');
			}
					var token=sessionStorage.getItem('token');
		var user_id=sessionStorage.getItem('user_id');
			userdata={
					'show_id': showID,
				'unique_id':user_id
				};
			$.ajax({
				url: base_url+'index.php/webservices/performers/startPerformerShow',
				type: 'POST',
				data: userdata,
				headers: {
				'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
				'Auth-Key':'<?php echo AUTH_KEY;?>',
				'Authorization':token,
				'User-ID':user_id
				},
				error: function() {
					$('.dvLoading').fadeOut(300);
					$('.container').css("opacity","none");
				},
				success: function(data) {
					
					if(data.status==401){
						sessionStorage.setItem("sessionExpiryMsg", data.message);
						var base_url="<?php echo base_url();?>";
						window.location.href = base_url+"index.php/login";
						return false;
					}else if(data.status==2){
						$("#comman_errormsg_message").html(data.message);
						window.setTimeout(function() {
						$("#comman_errormsg_message").fadeTo(8000, 0).slideUp(5000,function(){
						$(this).remove(); 
						});
						}, 5000);
						$("#comman_errormsg_message").show();
						
						$('.dvLoading').fadeOut(300);
						$('.container').css("opacity","none");
					}else{
							var base_url="<?php echo base_url();?>";
						sessionStorage.setItem("success_message",data.message);
						window.location.href = base_url+"index.php/myshow";
					}
				}
			});
		
		});
		
		$("#endPerformerShow").click(function(){
			$('.dvLoading').show();
			$('.container').css("opacity","0.6"); 
			var base_url="<?php echo base_url();?>";
			if(sessionStorage.getItem('showID')){
				var showID=sessionStorage.getItem('showID');
			}
				var token=sessionStorage.getItem('token');
		var user_id=sessionStorage.getItem('user_id');
			userdata={
				'show_id': showID,
				'unique_id':user_id
				};
			$.ajax({
				url: base_url+'index.php/webservices/performers/endPerformerShow',
				type: 'POST',
				data: userdata,
				headers: {
					'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
					'Auth-Key':'<?php echo AUTH_KEY;?>',
					'Authorization':token,
					'User-ID':user_id
				},
				error: function() {
					$('.dvLoading').fadeOut(300);
					$('.container').css("opacity","none");
				},
				success: function(data) {
					if(data.status==401){
						sessionStorage.setItem("sessionExpiryMsg", data.message);
						var base_url="<?php echo base_url();?>";
						window.location.href = base_url+"index.php/login";
						return false;
					}else if(data.status==2){
						$("#comman_errormsg_message").html(data.message);
						window.setTimeout(function() {
						$("#comman_errormsg_message").fadeTo(8000, 0).slideUp(5000,function(){
						$(this).remove(); 
						});
						}, 5000);
						$("#comman_errormsg_message").show();
						
						$('.dvLoading').fadeOut(300);
						$('.container').css("opacity","none");
					}else{
							var base_url="<?php echo base_url();?>";
						sessionStorage.setItem("success_message",data.message);
						window.location.href = base_url+"index.php/showdetails";
					}
				}
			});
		
		});
		
		
	});
	

	
	
	function loadMoreData(URL,defaultLoad){
		var TotalShowsBidAmount=0;
		var token=sessionStorage.getItem('token');
		var user_id=sessionStorage.getItem('user_id');
		var showID=sessionStorage.getItem('showID');
		$.ajax({
			url: URL,
			type: "get",
			headers: {
			'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
			'Auth-Key':'<?php echo AUTH_KEY;?>',
			'Authorization':token,
			'User-ID':user_id,
			'ShowId':showID
			},
			beforeSend: function(){
				$('.ajax-load').show();
			}
		}).done(function(response){
			var tabledata=response.data;
			console.log(response);
			if(defaultLoad==1){
				$("#searchSongsTable").html("");
				$("#loadingSearchSongsInnerDiv").hide();
			}
			$("#totalCount").val(response.total_count);
			
			if(tabledata.length>0){
			for(i = 0; i < tabledata.length; i++){
				
				if(tabledata[i].totalBidAmount=="" ||  tabledata[i].totalBidAmount==null ){
					
				}else{
					totalBidAmount=tabledata[i].totalBidAmount;
				}
				TotalShowsBidAmount=parseFloat(TotalShowsBidAmount)+parseFloat(totalBidAmount);
							
							
				if(i==0){
					$("#searchSongsTable").append("<tr SongID="+tabledata[i].songID+" ><td><span>"+tabledata[i].name+"</span><span class='musicsubtitle'>Elmore Jones</span><div class='rightimgtip'><span class='imgtopcount'><img src='<?php echo base_url();?>assets/images/user.png' class='img-responsive' alt='myshow' /><span class='tiptopcount'>"+tabledata[i].totalNoOfCount+"</span></span><span class='spanrighttip'>$"+tabledata[i].totalBidAmount+"</span></div></td></tr>");
					
				}else{
					$("#searchSongsTable").append("<tr SongID="+tabledata[i].songID+" ><td><span>"+tabledata[i].name+"</span><span class='musicsubtitle'>Elmore Jones</span><div class='rightimgtip'><span class='imgtopcount'><img src='<?php echo base_url();?>assets/images/user.png' class='img-responsive' alt='myshow' /><span class='tiptopcount'>"+tabledata[i].totalNoOfCount+"</span></span><span class='spanrighttip'>$"+tabledata[i].totalBidAmount+"</span></div></td></tr>");
				}
				 
			}
			
			}else{
				$("#searchSongsTable").append('<tr class="nosongs"><td>No any songs found</td></tr>');
			}
			$(".loadingSearchSongs").hide();
			
			$("#totalShowCount").html("Total Songs :"+response.total_count);
			$("#totalShowsTip").html("Tips: $ "+TotalShowsBidAmount);
			$('.dvLoading').fadeOut(300);
			$(".container").show();
		}).fail(function(jqXHR, ajaxOptions, thrownError){
			  alert('server not responding...');
		});
	}
	
	});
</script>

</html>