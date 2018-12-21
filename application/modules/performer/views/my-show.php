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


	<title>My Shows</title>
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
   <body class="myshow">
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
			<div style="display:none" id="comman_success_message" class="alert alert-success">

		</div>

			<div class="col-12 col-md-12 col-sm-12 myshowmain">
			<div class="timedatesection">
			<span class="datesection"> Date: <?php echo date('d/m/Y');?></span>
			<span class="timesection"> Time: <?php echo date('H:i');?></span>
			</div>
		
			<div class="paysuccesstitle">
			<h4>My Shows</h4>
		
			</div>
			<div class="row innercustommyshow">
			<div class="col-12 col-md-6 col-sm-6 col-xs-6 tableovrleftsec"><span id="totalShowCount">Total Shows:20</span></div>
			<div class="col-12 col-md-6 col-sm-6 col-xs-6 tableovrrightsec"><span id="totalShowsTip">Tips:$2035</span></div>
			</div>
			  <input type="hidden" id="totalCount" value="0" name="totalCount">
			<div class="table-wrapper-scroll-y">
			   <table class="table table-striped">
				  <tbody id="searchSongsTable">
							<div id="loadingSearchSongsInnerDiv" class="ajax_table_load text-center loadingSearchSongs" >
									<p><img src="<?php echo base_url()?>assets/images/loader.gif">Loading more songs</p>
								</div>
				 </tbody>
			   </table>
			   <div   class="ajax_table_load text-center loadingSearchSongs" style="display:none">
									<p><img src="<?php echo base_url()?>assets/images/loader.gif">Loading more songs</p>
								</div>
			</div>
						   
			<div class="addmyshowsection">
				<img src="<?php echo base_url();?>assets/images/Addshow.png" class="imgshow" id="" title="">
				<span class="addshowspan">Add Show</span>
			</div>
			</div><!--col-12 col-md-12 col-sm-12 end here-->
			</div><!--row-->
		</div><!--container-->
		  <input type="hidden" id="noOfSong" value="0" name="noOfSong">
   </body>
   
   <script type="text/javascript">
   if(!sessionStorage.getItem('token')){
		var base_url="<?php echo base_url();?>";
		window.location.href = base_url+"index.php/login";
	//	return false;
	}else{
		var page = $("#noOfSong").val();
		var limit=10;
		var searchString=$.trim($("#searchStr").val());
		var URL="<?php echo base_url(); ?>index.php/webservices/performers/getAllPerformerShows/"+page+"/10/2";
		loadMoreData(URL,1);
		page++;
		$("#noOfSong").val(page);
		
		
		$("#comman_success_message").hide();
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
			var page = $("#noOfSong").val();
			var totalItems=page*limit;
			console.log(totalItems);
			console.log(page);
			console.log(totalCount);
			if (totalCount > totalItems) {
				if($(window).scrollTop() + $(window).height() >= $(".table-wrapper-scroll-y").height()) {
					$(".loadingSearchSongs").show();
					page++;
					$("#noOfSong").val(page);
					var URL="<?php echo base_url(); ?>index.php/webservices/performers/getAllPerformerShows/"+totalItems+"/10/2";
					loadMoreData(URL,0);
				}
			}
		});
		
		 
	});
	
	
	
	function loadMoreData(URL,defaultLoad){
		var TotalShowsBidAmount=0;
		console.log(URL);
		var token=sessionStorage.getItem('token');
		var user_id=sessionStorage.getItem('user_id');
		
		$.ajax({
			url: URL,
			type: "get",
			headers: {
			'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
			'Auth-Key':'<?php echo AUTH_KEY;?>',
			'Authorization':token,
			'User-ID':user_id
			}, 
			beforeSend: function(){
				$('.ajax-load').show();
			}
		}).done(function(response){
			var tabledata=response.data;
			console.log(response);
			if(response.status==401){
						sessionStorage.setItem("sessionExpiryMsg", response.message);
						sessionStorage.removeItem('token');
						var base_url="<?php echo base_url();?>";
						window.location.href = base_url+"index.php/login";
						return false;
					}
				if(defaultLoad==1){
					$("#searchSongsTable").html("");
					$("#loadingSearchSongsInnerDiv").hide();
				}
				$("#totalCount").val(response.total_count);
				if(tabledata.length>0){
				for(i = 0; i < tabledata.length; i++){
							var showstatus="";
							if(tabledata[i].status=="S"){
								showstatus=" Scheduled";
							}else if(tabledata[i].status=="L"){
								showstatus=" Live";
							}else{
								showstatus=" Ended";
							}
							var tipAmount=0;
							if(tabledata[i].tipAmount=="" ||  tabledata[i].tipAmount==null ){
							}else{
							tipAmount=tabledata[i].tipAmount;
							}
							TotalShowsBidAmount=parseFloat(TotalShowsBidAmount)+parseFloat(tipAmount);
							appendString=" <tr  showID="+tabledata[i].unique_id+" class='myshowtr'>    <td>	<div class='tdleftsection'><span>"+tabledata[i].showname+"</span><span class='musicsubtitle showrowspan'>Date: "+tabledata[i].showDate+"</span><span class='musicsubtitle showrowspan'>Venue: "+tabledata[i].location+" </span><span class='musicsubtitle showrowspan'>Time: "+tabledata[i].showTime+"</span><span>Show Status:"+showstatus+"</span></div>	  <div class='tdrightsection'><div class='imgandtipsec'>       <span class='showrowspan'><img src='<?php echo base_url();?>assets/images/02-Search-Song.png' class='img-responsive' alt='request'  /></span><span class='showrowspantitle'><br/>Songs: "+tabledata[i].songCount+"</span></div><span class='showrowspan'><img src='<?php echo base_url();?>assets/images/Request-Details.png' class='img-responsive' alt='request'  /></span><span class='showrowspantitle'><br/>Tips: $"+tipAmount+"</span></div></td></tr>";
								$("#searchSongsTable").append(appendString);
				}
				
				var rowCount = $('#searchSongsTable tr').length; 
				$("#totalShowCount").html("Total Shows : "+rowCount);
				$("#totalShowsTip").html("Tips: $"+TotalShowsBidAmount);
				}else{
					$("#totalShowCount").html("Total Shows : 0");
					$("#totalShowsTip").html("Tips: $0");
					$("#searchSongsTable").append('<tr class="nosongs"><td>There is no show found</td></tr>');
					$("#noOfresult").hide();
				}
				
				$(".loadingSearchSongs").hide();
				
			$('.dvLoading').fadeOut(300);
    $(".container").show();
			
			
			$('.table-striped tr').click(function(){
					var link="<?php echo base_url()."index.php/showdetails";?>";
					showID = $(this).attr('showID'); 
					sessionStorage.setItem("showID", showID);
					window.location.href = link;
			});
		
		}).fail(function(jqXHR, ajaxOptions, thrownError){
			 // alert('server not responding...');
		});
	}
</script>

</html>