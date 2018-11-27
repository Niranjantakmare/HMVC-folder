<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/font-awesome.min.css"/>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	  
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
</style>
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
						<!--<ul class="nav navbar-nav">
                        <li class="active"><a href="<?php echo base_url()."performer/request-songs.html/877EB49B-4310-46BB-1160-DFFAE150EF15"; ?>">Requests Song</a></li>
                        <li><a href="#"></a></li>
                        <li><a href="#"></a></li>
                        </ul>-->
                  </div>
               </nav>
            </div>
            <!--col-12 col-md-12 col-sm-6 custommaintopsection end here-->
            <div class="col-12 col-md-12 col-sm-12 tabbingmainsection">
               <!-- Nav tabs -->
               <div class="card">
                  <ul class="nav nav-tabs" role="tablist">
                     <li id="searchSongs" class="active"  role="presentation"><a href="#searchSongsList" aria-controls="Search Song" role="tab" data-toggle="tab">Search</a></li>
                     <li  id="completeList" role="presentation" ><a href="#completeSongsList" aria-controls="Completed Song List" role="tab" data-toggle="tab">Complete List</a></li>
                     <li id="favoriteSongs" role="presentation"><a href="#favoriteSongsList" aria-controls="Favorite songs" role="tab" data-toggle="tab">Favorite Songs</a></li>
                  </ul>
                  <!-- Tab panes -->
                  <div class="tab-content">
				
				  <div role="tabpanel" class="tab-pane active" id="searchSongsList">
				    <span  class="tab-content-subtitle">List of all songs the performer knows</span>
                        <div class ="col-12 col-md-12 col-sm-12 songscustommain">
                           <div class ="songsmainsection">
                              <div class="songsinner">
                                 <div class="search-container">
                                    <form action="/action_page.php">
                                       <input type="text" id="searchStr" placeholder="Everyday" name="search">
                                       <button type="submit"><i class="fa fa-search"></i></button>
                                    </form>
                                 </div>
                              </div>
                           </div>
                           <div class="showingsection">
                              <span style="display:none" id="noOfresult">Showing 3 of 3 results</span>
							  <input type="hidden" id="totalCount" value="0" name="totalCount">
							  <input type="hidden" id="CompletedSongstotalCount" value="0" name="totalCount">
							  <input type="hidden" id="favoriteSongtotalCount" value="0" name="totalCount">
                           </div>
						  <div class=" table-wrapper-scroll-y">
                           <table  class="tableSection table table-striped">
							 <tbody id="searchSongsTable">
								<div id="loadingSearchSongsInnerDiv" class="ajax_table_load text-center" >
									<p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading more songs</p>
								</div>
							</tbody>
							</table>
							<div   class="ajax_table_load text-center loadingSearchSongs" style="display:none">
								<p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading more songs</p>
								</div>
						   	</div>
                        </div>
				 </div>
				<div role="tabpanel" class="tab-pane "  id="completeSongsList"> 
				    <span class="tab-content-subtitle">List of completed songs the performer knows</span>
					 <div  style="        max-height: 360px;" class=" table-wrapper-scroll-y">
					<table  class="tableSection table table-striped">
						<tbody id="completedSongsListTable">
						  <div  class="ajax_table_load text-center loadingCompletedSongs" >
							<p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading more completed songs</p>
							</div>
						</tbody>
						
						
					</table>
					<div  class="ajax_table_load text-center loadingCompletedSongs" style="display:none" >
							<p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading more completed songs</p>
					</div>
					</div>
				</div>
				<div role="tabpanel" class="tab-pane" id="favoriteSongsList"> 
				    <span class="tab-content-subtitle">List of favorite songs the performer knows</span>
					 <div style="        max-height: 360px;" class=" table-wrapper-scroll-y">
					<table  class="tableSection table table-striped">
						<tbody id="favoriteSongsListTable">
							<div  class="ajax_table_load text-center loadingFavoritesSongs" >
							<p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading more favorite songs</p>
							</div>
						 </tbody>
					</table>
					<div  class="ajax_table_load text-center loadingFavoritesSongs" style="display:none" >
							<p><img src="http://demo.itsolutionstuff.com/plugin/loader.gif">Loading more favorite songs</p>
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
			var URL="http://localhost:8080/CodeIgniter-HMVC-master/webservices/songs/performerSongsList/"+page+"/10/";
	}else{
		var URL="http://localhost:8080/CodeIgniter-HMVC-master/webservices/songs/performerSongsList/"+page+"/10/";
	}
	loadMoreData(URL,1);
	page++;
	$("#noOfSong").val(page);
	$("#completeList").click(function(){
		    CompletedSongstotalCount=$("#CompletedSongstotalCount").val();
			var noOfCompletedSongs = $("#noOfCompletedSongs").val();
			var totalItems=noOfCompletedSongs*limit;
			if (CompletedSongstotalCount <= totalItems) {
				var URL="http://localhost:8080/CodeIgniter-HMVC-master/webservices/songs/performerSongsList/"+noOfCompletedSongs+"/10/";
				loadMoreData(URL,1);
			}
	});
	$("#favoriteSongs").click(function(){
			favoriteSongtotalCount=$("#favoriteSongtotalCount").val();
			var noOfSongFav = $("#noOfSongFav").val();
			var totalItems=noOfSongFav*limit;
			if (favoriteSongtotalCount <= totalItems) {
				var URL="http://localhost:8080/CodeIgniter-HMVC-master/webservices/songs/performerFavoriteSongsList/"+noOfSongFav+"/10/";
				loadMoreData(URL,1);
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
				if($(window).scrollTop() + $(window).height() >= $(document).height()) {
					$(".loadingSearchSongs").show();
					page++;
					$("#noOfSong").val(page);
					var searchString=$.trim($("#searchStr").val());
					if(searchString!=""){
							var URL="http://localhost:8080/CodeIgniter-HMVC-master/webservices/songs/performerSongsList/"+totalItems+"/10/".searchString;
					}else{
						var URL="http://localhost:8080/CodeIgniter-HMVC-master/webservices/songs/performerSongsList/"+totalItems+"/10/";
					}
					loadMoreData(URL,0);
				}
			}
		});
		
		 $( "#completedSongsListTable" ).scroll(function() {
			CompletedSongstotalCount=$("#CompletedSongstotalCount").val();
			var noOfSongFav = $("#noOfSongFav").val();
			var totalItems=noOfSongFav*limit;
			if (CompletedSongstotalCount > totalItems) {
				if($(window).scrollTop() + $(window).height() >= $(document).height()) {
					$(".loadingCompletedSongs").show();
					noOfSongFav++;
					$("#noOfSongFav").val(noOfSongFav);
					var URL="http://localhost:8080/CodeIgniter-HMVC-master/webservices/songs/performerSongsList/"+totalItems+"/10/";
					loadMoreData(URL,0);
				}
			}
		});
		$( "#favoriteSongsListTable" ).scroll(function() {
			favtotalCount=$("#favoriteSongtotalCount").val();
				var noOfCompletedSongs = $("#noOfCompletedSongs").val();
			var totalItems=noOfCompletedSongs*limit;
			if (favtotalCount > totalItems) {
				if($(window).scrollTop() + $(window).height() >= $(document).height()) {
					$(".loadingFavoritesSongs").hide();
					var URL="http://localhost:8080/CodeIgniter-HMVC-master/webservices/songs/performerFavoriteSongsList/"+totalItems+"/10/";
				
					loadMoreData(URL,0);
				}
			}
		});
		
	});
	$("#searchStr").change(function(){
			var page = 0;
			var limit=10;
			var searchString=$.trim($("#searchStr").val());
			var URL="http://localhost:8080/CodeIgniter-HMVC-master/webservices/songs/performerSongsList/"+page+"/10/"+searchString;
			loadMoreData(URL,1);
			page++;
	});
	

	
	
	function loadMoreData(URL,defaultLoad){
		console.log(URL);
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
			var tabledata=response.data;
			console.log(response);
			if($("#searchSongs").hasClass( "active" )){
				if(defaultLoad==1){
					$("#searchSongsTable").html("");
					$("#loadingSearchSongsInnerDiv").hide();
				}
				$("#totalCount").val(response.total_count);
				for(i = 0; i < tabledata.length; i++){
					$("#searchSongsTable").append('<tr><td><span>'+tabledata[i].name+'</span><span class="musicsubtitle">'+tabledata[i].artist+'</span></td></tr>');
				}
				var rowCount = $('#searchSongsTable tr').length;
				$("#noOfresult").html("Showing "+rowCount+" of "+response.total_count+" results");
				$(".loadingSearchSongs").hide();
				$("#noOfresult").show();
			}else if($("#completeSongsList").hasClass( "active" )){
				$("#CompletedSongstotalCount").val(response.total_count);
				if(defaultLoad==1){
					$("#completedSongsListTable").html("");
				}
				for(i = 0; i < tabledata.length; i++){
					$("#completedSongsListTable").append('<tr><td><span>'+tabledata[i].name+'</span><span class="musicsubtitle">'+tabledata[i].artist+'</span></td></tr>');
				}
				$(".loadingCompletedSongs").hide();
					var noOfCompletedSongs=$("#noOfCompletedSongs").val();
					$("#noOfCompletedSongs").val(noOfCompletedSongs);
				
			}else if($( "#favoriteSongs" ).hasClass( "active" )){
				
				$("#favoriteSongtotalCount").val(response.total_count);
				if(defaultLoad==1){
					$("#favoriteSongsListTable").html(""); 
				}
				for(i = 0; i < tabledata.length; i++){
					$("#favoriteSongsListTable").append('<tr><td><span>'+tabledata[i].name+'</span><span class="musicsubtitle">'+tabledata[i].artist+'</span></td></tr>');

				}
				var noOfSongFav=$("#noOfSongFav").val();
				$("#noOfSongFav").val(noOfSongFav);
				$(".loadingFavoritesSongs").hide();
			}
		}).fail(function(jqXHR, ajaxOptions, thrownError){
			  alert('server not responding...');
		});
	}
</script>
</body>
</html>
