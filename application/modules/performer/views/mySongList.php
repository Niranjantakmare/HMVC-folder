<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/font-awesome.min.css"/>
		<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
		<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

		<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
		<title>My Songs List</title>
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
   <body class="songslist">
      <div class="dvLoading"></div>
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
                        <li class="active"><a href="#">Home</a></li>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Home</a></li>
                        </ul>-->
                  </div>
               </nav>
            </div>
            <!--col-12 col-md-12 col-sm-6 custommaintopsection end here-->
            <div class="col-12 col-md-12 col-sm-12 tabbingmainsection">
              
				 
                        <div class ="col-12 col-md-12 col-sm-12 songscustommain">
                           <div class ="songsmainsection">
								<div class="songsinner">
                                 <div class="search-container">
                                     <input type="text" id="searchStr" autocomplete="off" placeholder="Search" name="search">
								    <button onclick="search_song()" type="button"><i class="fa fa-search"></i></button>
                                 </div>
                              </div>
                           </div>
                         
					<div class="row innercustommyshow">
					<div class="col-12 col-md-6 col-sm-6 col-xs-6 tableovrleftsec">
					<div class="showingsection">
                              <span style="display:none" id="noOfresult">Showing 3 of 3 results</span>
							  <input type="hidden" id="totalCount" value="0" name="totalCount">
                           </div>
						   </div>
						<div class="col-12 col-md-6 col-sm-6 col-xs-6 tableovrrightsec"><span>Favorites</span></div>
						</div>
						 
						 <div class="table-wrapper-scroll-y">
                           <table  class="tableSection table table-striped">
							 <tbody id="searchSongsTable">
									<div  class="ajax_table_load text-center loadingSearchSongs " >
									<p><img src="<?php echo base_url()?>assets/images/loader.gif">Loading more songs</p>
									</div>
							</tbody>
							</table>
							<div   class="ajax_table_load text-center loadingSearchSongs" style="display:none">
								<p><img src="<?php echo base_url()?>assets/images/loader.gif">Loading more songs</p>
								</div>
						   	</div>
						
				<div class="addmyshowsection">
				<img src="<?php echo base_url(); ?>assets/images/iconfinder.png" class="imgshow" id="" title="">
				<span class="addshowspan">Remove from my song list</span>
				</div>
				</div>

            </div>
         </div>
      </div>
	    <input type="hidden" id="noOfSong" value="0" name="noOfSong">
   </body>
   
   	<script type="text/javascript">
	if(!sessionStorage.getItem('token')){
			var base_url="<?php echo base_url();?>";
			window.location.href = base_url+"index.php/login";
			
		}
	var page = $("#noOfSong").val();
	var limit=10;
	var searchString=$.trim($("#searchStr").val());
	if(searchString!=""){
			var URL="<?php echo base_url(); ?>index.php/webservices/performers/mySongsList/"+page+"/10/";
	}else{
		var URL="<?php echo base_url(); ?>index.php/webservices/performers/mySongsList/"+page+"/10/";
	}
	
	loadMoreData(URL,1);
	page++;
	$("#noOfSong").val(page);
	
	$(document).ready(function(){
		$( ".table-wrapper-scroll-y" ).scroll(function() {
			totalCount=$("#totalCount").val();
			var page = $("#noOfSong").val();
			var totalItems=page*limit;
			console.log(totalItems);
			console.log(page);
			console.log(totalCount);
			if (totalCount > totalItems) {
			if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
					$(".loadingSearchSongs").show();
					var searchString=$.trim($("#searchStr").val());
					if(searchString!=""){
							var URL="<?php echo base_url(); ?>index.php/webservices/performers/mySongsList/"+totalItems+"/10";
					}else{
						var URL="<?php echo base_url(); ?>index.php/webservices/performers/mySongsList/"+totalItems+"/10/";
					}
					loadMoreData(URL,0);
				}
			}
		});		
	});
	
	var live_timer;    
$("#searchStr").keyup(function (e){
    clearTimeout(live_timer);
   // var user_name = $(this).val();
    live_timer = setTimeout(function(){
		var searchString=$.trim($("#searchStr").val());
       // simple jQuery callback_function();
	   
		search_song();
	   
    }, 500);
});

	$("#searchStr").change(function(){
			var page = 0;
			var limit=10;
			var searchString=$.trim($("#searchStr").val());
			console.log(searchString);
			if(searchString==""){
				var URL="<?php echo base_url(); ?>index.php/webservices/performers/mySongsList/"+page+"/10/"+searchString;
				loadMoreData(URL,1);
				
			}
	});
	

	function search_song(){
			var page = 0;
			var limit=10;
				var searchString=$.trim($("#searchStr").val());
			if(searchString==""){
				//$("#searchSongsTable").html('<tr class="nosongs"><td>Please enter song name</td></tr>');
				//$("#noOfresult").hide();
				//return false;
			}
			if(/^[a-zA-Z0-9\ \-]*$/.test(searchString) == false) {
				//alert('Your search string contains illegal characters.');
				//return false;
			}

			var URL="<?php echo base_url(); ?>index.php/webservices/performers/mySongsList/"+page+"/10";
			var URL1=escape(URL);
			loadMoreData(URL,1);
			
			
	}
	
	function loadMoreData(URL,defaultLoad){
		console.log(URL);
		var searchString=$.trim($("#searchStr").val());
		userdata={
				'searchString': searchString
				};
				console.log("calssss");
		var base_url="<?php echo base_url();?>";
		var token=sessionStorage.getItem('token');
		var TotalShowsBidAmount=0;
		var user_id=sessionStorage.getItem('user_id');
			
		$.ajax({
			url: URL,
			type: "POST",
			data: userdata,
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
			
				if(defaultLoad==1){
					$("#searchSongsTable").html("");
					$("#loadingSearchSongsInnerDiv").hide();
					$('.dvLoading').fadeOut(300);
					$(".container").show();
				}
				$("#totalCount").val(response.total_count);
				if(tabledata.length>0){
				for(i = 0; i < tabledata.length; i++){
					//$("#searchSongsTable").append('<tr><td><span>'+tabledata[i].name+'</span><span class="musicsubtitle">'+tabledata[i].artist+'</span></td></tr>');
					$("#searchSongsTable").append("<tr><td><span>"+tabledata[i].name+"</span><span class='musicsubtitle'>"+tabledata[i].artist+"</span><div class='favoriteimg'><img src='<?php echo base_url();?>assets/images/heart.png' SongID="+tabledata[i].songID+"  class='imgshow' id='' title=''></div></td></tr>");
					
					      
				}
				var rowCount = $('#searchSongsTable tr').length; 
				$("#noOfresult").html("Showing "+rowCount+" of "+response.total_count+" songs");
				$("#noOfresult").show();
				}else{
					var searchString=$.trim($("#searchStr").val());
					if(searchString!=""){ 
						$("#searchSongsTable").append("<tr class='nosongs'><td>Sorry, not song matches that name.</td></tr>");
					}else{
						$("#searchSongsTable").append('<tr class="nosongs"><td>No any songs found</td></tr>');
					}
					$("#noOfresult").hide();
				}
				
				$(".loadingSearchSongs").hide();
					var page = $("#noOfSong").val();
					page++;
					console.log("this page");
					$("#noOfSong").val(page);
			$('.dvLoading').fadeOut(300);
			$(".container").show();
			
		
		}).fail(function(jqXHR, ajaxOptions, thrownError){
			  alert('server not responding...');
		});
	}
</script>


</html>