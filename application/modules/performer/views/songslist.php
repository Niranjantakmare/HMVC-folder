<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
 <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css">
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
	  
<title>Song List</title>
<style>

::placeholder {
    color: red;
    opacity: 1; /* Firefox */
}

.dvLoading
{
	background: url(../../assets/images/processing.gif) no-repeat center center;
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
#completeList{
	display:none;
}
</style>
</head>

<body class="songslist">

  <div class="container">
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
            <!--col-12 col-md-12 col-sm-6 custommaintopsection end here-->
            <div class="col-12 col-md-12 col-sm-12 tabbingmainsection">
			<h4 class="selectsongtitle">Select your song</h4>
               <!-- Nav tabs -->
               <div class="card">
                  <ul class="nav nav-tabs" role="tablist">
                     <li id="searchSongs" class="active"  role="presentation"><a href="#searchSongsList" aria-controls="Search Song" role="tab" data-toggle="tab">Song List</a></li>
                     <li  id="completeList" role="presentation" ><a href="#completeSongsList" aria-controls="Completed Song List" role="tab" data-toggle="tab">Complete List</a></li>
                     <li id="favoriteSongs" role="presentation"><a href="#favoriteSongsList" aria-controls="Favorite songs" role="tab" data-toggle="tab">Performer Favorites</a></li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
				
				  <div role="tabpanel" class="tab-pane active" id="searchSongsList">
				    <span  class="tab-content-subtitle">List of all songs the performer knows</span>
                        <div class ="col-12 col-md-12 col-sm-12 songscustommain">
                           <div class ="songsmainsection">
                              <div class="songsinner">
                                 <div class="search-container">
                                     <input type="text" id="searchStr" autocomplete="off" placeholder="Search" name="search">
								    <button onclick="search_song()" type="button"><i class="fa fa-search"></i></button>
                                 </div>
                              </div>
                           </div>
                           <div class="showingsection">
                              <span style="display:none" id="noOfresult">Showing 3 of 3 results</span>
							  <input type="hidden" id="totalCount" value="0" name="totalCount">
							  <input type="hidden" id="CompletedSongstotalCount" value="0" name="totalCount">
							  <input type="hidden" id="favoriteSongtotalCount" value="0" name="totalCount">
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
                        </div>
				 </div>
				<div role="tabpanel" class="tab-pane "  id="completeSongsList"> 
				    <span class="tab-content-subtitle">List of completed songs the performer knows</span>
					 <div class="table-wrapper-scroll-completed_songs">
					<table  class="tableSection table table-striped">
						<tbody id="completedSongsListTable">
						  <div  class="ajax_table_load text-center loadingCompletedSongs" >
							<p><img src="<?php echo base_url()?>assets/images/loader.gif">Loading more completed songs</p>
							</div>
						</tbody>
						
						
					</table>
					<div  class="ajax_table_load text-center loadingCompletedSongs" style="display:none" >
							<p><img src="<?php echo base_url()?>assets/images/loader.gif">Loading more completed songs</p>
					</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="favoriteSongsList"> 
				    <span class="tab-content-subtitle">List of favorite songs the performer knows</span>
					 <div class="table-wrapper-scroll-favorite">
					<table  class="tableSection table table-striped">
						<tbody id="favoriteSongsListTable">
							<div  class="ajax_table_load text-center loadingFavoritesSongs" >
							<p><img src="<?php echo base_url()?>assets/images/loader.gif">Loading more favorite songs</p>
							</div>
						 </tbody>
					</table>
					<div  class="ajax_table_load text-center loadingFavoritesSongs" style="display:none" >
							<p><img src="<?php echo base_url()?>assets/images/loader.gif">Loading more favorite songs</p>
							</div>
							</div>
				</div>
                     
				</div>
               </div>
            </div>
         </div>
      </div>
	  <input type="hidden" id="noOfSong" value="0" name="noOfSong">
	  <input type="hidden" id="noOfSongFav" value="0" name="noOfSongFav">
	    <input type="hidden" id="noOfCompletedSongs" value="0" name="noOfCompletedSongs">
	<script type="text/javascript">
	var page = $("#noOfSong").val();
	var limit=10;
	var searchString=$.trim($("#searchStr").val());
	if(searchString!=""){
			var URL="<?php echo base_url(); ?>index.php/webservices/songs/performerSongsList/"+page+"/10/";
	}else{
		var URL="<?php echo base_url(); ?>index.php/webservices/songs/performerSongsList/"+page+"/10/";
	}
	
	loadMoreData(URL,1,"searchSongs");
	page++;
	$("#noOfSong").val(page);
	//$("#searchSongsTable").append('<tr class="nosongs"><td>Please search your song</td></tr>');
	$("#completeList").click(function(){
		    CompletedSongstotalCount=$("#CompletedSongstotalCount").val();
			var noOfCompletedSongs = $("#noOfCompletedSongs").val();
			var totalItems=noOfCompletedSongs*limit;
			if (CompletedSongstotalCount <= totalItems) {
				var URL="<?php echo base_url(); ?>index.php/webservices/songs/performerCompletedSongsList/"+noOfCompletedSongs+"/10/";
				loadMoreData(URL,1,"completeSongsList");
			}
	});
	$("#favoriteSongs").click(function(){
			favoriteSongtotalCount=$("#favoriteSongtotalCount").val();
			var noOfSongFav = $("#noOfSongFav").val();
			var totalItems=noOfSongFav*limit;
			if (favoriteSongtotalCount <= totalItems) {
				var URL="<?php echo base_url(); ?>index.php/webservices/songs/performerFavoriteSongsList/"+noOfSongFav+"/10/";
				loadMoreData(URL,1,"favoriteSongs");
			}
		
	});
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
					page++;
					console.log("this page");
					$("#noOfSong").val(page);
					var searchString=$.trim($("#searchStr").val());
					if(searchString!=""){
							var URL="<?php echo base_url(); ?>index.php/webservices/songs/performerSongsList/"+totalItems+"/10";
					}else{
						var URL="<?php echo base_url(); ?>index.php/webservices/songs/performerSongsList/"+totalItems+"/10/";
					}
					loadMoreData(URL,0,"searchSongs");
				}
			}
		});
		
		 $( "#completedSongsListTable" ).scroll(function() {
			CompletedSongstotalCount=$("#CompletedSongstotalCount").val();
			var noOfSongFav = $("#noOfSongFav").val();
			var totalItems=noOfSongFav*limit;
			if (CompletedSongstotalCount > totalItems) {
				if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
					$(".loadingCompletedSongs").show();
					noOfSongFav++;
					$("#noOfSongFav").val(noOfSongFav);
					var URL="<?php echo base_url(); ?>index.php/webservices/songs/performerCompletedSongsList/"+totalItems+"/10/";
					loadMoreData(URL,0,"completeSongsList");
				}
			}
		});
		$( "#favoriteSongsListTable" ).scroll(function() {
			favtotalCount=$("#favoriteSongtotalCount").val();
				var noOfCompletedSongs = $("#noOfCompletedSongs").val();
			var totalItems=noOfCompletedSongs*limit;
			if (favtotalCount > totalItems) {
				if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
					$(".loadingFavoritesSongs").hide();
					var URL="<?php echo base_url(); ?>index.php/webservices/songs/performerFavoriteSongsList/"+totalItems+"/10/";
				
					loadMoreData(URL,0,"favoriteSongs");
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
				var URL="<?php echo base_url(); ?>index.php/webservices/songs/performerSongsList/"+page+"/10/"+searchString;
				loadMoreData(URL,1,"searchSongs");
				page++;
				$("#noOfSong").val(page);
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

			var URL="<?php echo base_url(); ?>index.php/webservices/songs/performerSongsList/"+page+"/10";
			var URL1=escape(URL);
			loadMoreData(URL,1,"searchSongs");
			page++;
			$("#noOfSong").val(page)
			
	}
	
	function loadMoreData(URL,defaultLoad,tabName){
		console.log(URL);
		var searchString=$.trim($("#searchStr").val());
		userdata={
				'searchString': searchString
				};
		$.ajax({
			url: URL,
			type: "POST",
			data: userdata,
			headers: {
			'Client-Service':'<?php echo CLIENT_SECRETE_KEY;?>',
			'Auth-Key':'<?php echo AUTH_KEY;?>',
			'ShowId':'877EB49B-4310-46BB-1160-DFFAE150EF15'
			},
			beforeSend: function(){
				$('.ajax-load').show();
			}
		}).done(function(response){
			var tabledata=response.data;
			console.log(response);
			if(tabName=="searchSongs"){ 
				if(defaultLoad==1){
					$("#searchSongsTable").html("");
					$("#loadingSearchSongsInnerDiv").hide();
				}
				$("#totalCount").val(response.total_count);
				if(tabledata.length>0){
				for(i = 0; i < tabledata.length; i++){
					//$("#searchSongsTable").append('<tr><td><span>'+tabledata[i].name+'</span><span class="musicsubtitle">'+tabledata[i].artist+'</span></td></tr>');
					$("#searchSongsTable").append("<tr SongID="+tabledata[i].songID+" ><td><span  class='musicName'>"+tabledata[i].name+"</span><span class='musicsubtitle'>"+tabledata[i].artist+"</span></td></tr>");
				}
				var rowCount = $('#searchSongsTable tr').length; 
				$("#noOfresult").html("Showing "+rowCount+" of "+response.total_count+" results");
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
				
			}else if(tabName=="completeSongsList"){
				$("#CompletedSongstotalCount").val(response.total_count);
				if(defaultLoad==1){
					$("#completedSongsListTable").html("");
				}
				if(tabledata.length>0){
					for(i = 0; i < tabledata.length; i++){
						//$("#completedSongsListTable").append('<tr><td><span>'+tabledata[i].name+'</span><span class="musicsubtitle">'+tabledata[i].artist+'</span></td></tr>');
								$("#completedSongsListTable").append("<tr SongID="+tabledata[i].songID+" ><td><span  class='musicName'>"+tabledata[i].name+"</span><span class='musicsubtitle'>"+tabledata[i].artist+"</span></td></tr>");
					}
				}else{
					$("#completedSongsListTable").append('<tr class="nosongs"><td>No any songs found</td></tr>');
				}
				$(".loadingCompletedSongs").hide();
					var noOfCompletedSongs=$("#noOfCompletedSongs").val();
					$("#noOfCompletedSongs").val(noOfCompletedSongs);
				
			}else if(tabName=="favoriteSongs"){ 
				
				$("#favoriteSongtotalCount").val(response.total_count);
				if(defaultLoad==1){
					$("#favoriteSongsListTable").html(""); 
				}
				if(tabledata.length>0){
					for(i = 0; i < tabledata.length; i++){
						//$("#favoriteSongsListTable").append('<tr><td><span>'+tabledata[i].name+'</span><span class="musicsubtitle">'+tabledata[i].artist+'</span></td></tr>');
						
						$("#favoriteSongsListTable").append("<tr SongID="+tabledata[i].songID+" ><td><span  class='musicName'>"+tabledata[i].name+"</span><span class='musicsubtitle'>"+tabledata[i].artist+"</span></td></tr>");

					}
				}else{
					$("#favoriteSongsListTable").append('<tr class="nosongs"><td>No any songs found</td></tr>');
				}
				var noOfSongFav=$("#noOfSongFav").val();
				$("#noOfSongFav").val(noOfSongFav);
				$(".loadingFavoritesSongs").hide();
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
			  alert('server not responding...');
		});
	}
</script>
</body>
</html>
