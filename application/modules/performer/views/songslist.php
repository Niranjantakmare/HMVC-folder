<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css"/>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/bootstrap/font-awesome.min.css"/>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap/bootstrap.min.js"></script>
<title>Song List</title>

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
    </div><!--col-12 col-md-12 col-sm-6 custommaintopsection end here-->
  <div class ="col-12 col-md-12 col-sm-12 songscustommain">
  
  <div class ="songsmainsection">
   <div class="songsinner">
   <div class="search-container">
    <form action="/action_page.php">
      <input type="text" placeholder="Everyday" name="search">
      <button type="submit"><i class="fa fa-search"></i></button>
    </form>
  </div>
   
   </div>
  </div>
  
  <div class="showingsection">
  <span>Showing 3 of 3 results</span>
  </div>
  
  <!--<div class="innersonglist">
  <div class ="songsmainsecondsection">
   <div class="musicicoonsection">
   <img src="images/02-Search-Song.png"/>
   </div>
   <div class="musicicoonsectiontitle">
   <span>Everyday I have the Blues</span>
   <span class="musicsubtitle">Elmore Jones</span>
   </div>
   
   </div>
   
   <div class="songsmainsecondsection">
   <div class="musicicoonsection">
   <img src="images/02-Search-Song.png"/>
   </div>
   <div class="musicicoonsectiontitle">
   <span>Everyday is Sunshine</span>
   <span class="musicsubtitle">John Doe</span>
   </div>
   
   </div>
   
   <div class="songsmainsecondsection">
   <div class="musicicoonsection">
   <img src="images/02-Search-Song.png"/>
   </div>
   <div class="musicicoonsectiontitle">
   <span>Everyday I Love You</span>
   <span class="musicsubtitle">Boyzone</span>
   </div>
   
   </div>
  </div> -->
  
  <table class="table table-striped">
    <tbody>
        <tr>
            <td><span>Everyday is Sunshine</span>
   <span class="musicsubtitle">John Doe</span></td>
            
        </tr>
        <tr>
            <td><span>Everyday is Sunshine</span>
   <span class="musicsubtitle">John Doe</span></td>
            
        </tr>
        <tr>
            <td><span>Everyday is Sunshine</span>
   <span class="musicsubtitle">John Doe</span></td>
            
        </tr>
    </tbody>
</table>
  
  </div>
   
   
</div><!--row end here-->
</div><!--Container end here-->

</body>
</html>
