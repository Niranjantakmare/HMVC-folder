<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/font-awesome.min.css"/>
		<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
		<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

		<title>Payment Success</title>
   </head>
   <style>
		
::placeholder {
    color: red;
    opacity: 1; /* Firefox */
}

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
.text-center {
    text-align: center;
}
.ajax_table_load{
	background: #ffffff;
	width: 100%;

}
tr.nosongs{
background: none;
}
.table-wrapper-scroll-y{
    margin-bottom: 30px
}

/*.bottomline {
    position: absolute;
    bottom: 5px;
    right: 0px;
    font-size: 10px;
}*/
	</style>
   <body>
     <div id="dvLoading"></div>
      <div class="container" style="display:none" >
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
			<div class="paysuccesstitle" id="paymentSuccess" style="display:none">
			<h4>Thank you for your payment...!</h4>
			<span>Your request has been submitted sucessfully.</span>
			</div>
			
			<div class="paysuccesstitle" id="paymentError" style="display:none">
			<h4>Your payment has been failed...!</h4>
			<span>Your request has been failed to process.<br.>Please try again</span>
			</div>
		
			</div><!--col-12 col-md-12 col-sm-12 end here-->
			
		<div class="col-12 col-md-12 col-sm-12 paymentsuccess">
         <img src="<?php echo base_url();?>assets/images/Micheal.png" class="img-responsive" alt="micheal-img"/> 
		 <div class="song-queue">Song Queue</div>
        </div>
		</div><!--row-->
		 
		  <div class="row">
		  
		  <div class="col-sm-12 col-md-6 col-xs-12 paymentsuccessrow">
					
				    <input type="hidden" id="totalCount" value="0" name="totalCount">
				   <div class=" table-wrapper-scroll-paymentsuc  ">
                           <table  class="tableSection table table-striped showdetailsspan">
							 <tbody id="searchSongsTable">
								<div id="loadingSearchSongsInnerDiv" class="ajax_table_load text-center" >
									<p><img src="<?php echo base_url()?>assets/images/loader.gif">Loading more songs</p>
								</div>
							</tbody>
							</table>
							<div   class="ajax_table_load text-center loadingSearchSongs" style="display:none">
								<p><img src="<?php echo base_url()?>assets/images/loader.gif">Loading more songs</p>
								</div>
						   	</div>
			</div>
		  </div>
		  <div class="row">
		    <div class="col-sm-12 col-md-6 col-xs-12">
		  <div class="bottomline">Click on any song to add more tip</div>
		  </div>
		  <div class="col-sm-12 col-md-12 col-xs-12">
			<div class="form-group">
				<a id="changeSongBtn"  style="cursor:pointer;" href="<?php echo base_url();?>index.php/requestsongs"><button type="button" class="btn btn-success customfullbtn">Request Another Song</button></a>
				<input type="hidden" id="noOfSong" value="0" name="noOfSong">
			</div>
		 </div>
		  </div>
	  </div><!--container-->
	     </body>
</html>
	<script type="text/javascript">
		$(document).ready(function(){
			var payment_success=sessionStorage.getItem("payment_success");
			if(payment_success){
				$("#paymentSuccess").show();
				$("#paymentError").hide();
			}else{
				$("#paymentError").show();
				$("#paymentSuccess").show();
			}
		});
	
	var page = $("#noOfSong").val();
	var limit=10;
	var searchString=$.trim($("#searchStr").val());
	var URL="<?php echo base_url(); ?>index.php/webservices/request/getAllCurrentShowRequests/"+page+"/10/";
	loadMoreData(URL,1);
	page++;
	$("#noOfSong").val(page);
	
	
	$(document).ready(function(){
		$( ".table-wrapper-scroll-paymentsuc" ).scroll(function() {
			totalCount=$("#totalCount").val();
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
					var URL="<?php echo base_url(); ?>index.php/webservices/request/getAllCurrentShowRequests/"+totalItems+"/10";
					loadMoreData(URL,0);
					
				}
			}
		});
	});
	

	
	
	function loadMoreData(URL,defaultLoad){
		$.ajax({
			url: URL,
			type: "get",
			headers: {
			'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
			'Auth-Key':'<?php echo AUTH_KEY;?>',
			'ShowId':'877EB49B-4310-46BB-1160-DFFAE150EF15'
			},
			beforeSend: function(){
				$('.ajax-load').show();
			}
		}).done(function(response){
			console.log(response);
			var tabledata=response.data;
			console.log(response);
			if(defaultLoad==1){
				$("#searchSongsTable").html("");
				$("#loadingSearchSongsInnerDiv").hide();
			}
			$("#totalCount").val(response.total_count);
			var page = $("#noOfSong").val();
			if(tabledata.length>0){
			for(i = 0; i < tabledata.length; i++){
				//$("#searchSongsTable").append('<tr><td><span>'+tabledata[i].name+'</span><span class="musicsubtitle">'+tabledata[i].artist+'</span></td></tr>');
				
				if(i==0 && page==1){
					$("#searchSongsTable").append("<tr  SongID="+tabledata[i].songID+" class='differntimg'><td><span class='musicName boldtitle'>"+tabledata[i].name+"</span><span class='musicsubtitle'>"+tabledata[i].artist+"</span><span class='pricesection'>$"+tabledata[i].totalBidAmount+"</span></td></tr>");
				}else{
					$("#searchSongsTable").append("<tr SongID="+tabledata[i].songID+"><td><span class='musicName'>"+tabledata[i].name+"</span><span class='musicsubtitle'>"+tabledata[i].artist+"</span><span class='pricesection'>$"+tabledata[i].totalBidAmount+"</span></td></tr>");
				}
				 
			}
			var rowCount = $('#searchSongsTable tr').length; 
			$("#noOfresult").html("Showing "+rowCount+" of "+response.total_count+" results");
			$("#noOfresult").show();
			}else{
				var searchString=$.trim($("#searchStr").val());
				if(searchString!=""){ 
					$("#searchSongsTable").append("<tr class='nosongs'><td>No songs found for '"+searchString+"'</td></tr>");
				}else{
					$("#searchSongsTable").append('<tr class="nosongs"><td>No any songs found</td></tr>');
				}
				$("#noOfresult").hide();
			}
			$(".loadingSearchSongs").hide();
			if(defaultLoad==1){
				$('#dvLoading').fadeOut(300);
				$(".container").show();
			}
			$('.tableSection tr').click(function(){
			var link="<?php echo base_url()."index.php/requestdetails";?>";
			SongID = $(this).attr('SongID'); 
			SongArtist = $(this).find("span.musicsubtitle")[0].innerText;
			SongName = $(this).find("span.musicName")[0].innerText;
			sessionStorage.setItem("SongID", SongID);
			sessionStorage.setItem("SongArtist", SongArtist);
			sessionStorage.setItem("SongName", SongName);
			window.location.href = link;
			});
		}).fail(function(jqXHR, ajaxOptions, thrownError){
			//  alert('server not responding...');
		});
	}
	
	$('#searchSongsTable tr').click(function(){
			var link="<?php echo base_url()."index.php/requestdetails";?>";
			SongID = $(this).attr('SongID'); 
			console.log($(this));
			
			//window.location.href = link;
		});
		
</script>

