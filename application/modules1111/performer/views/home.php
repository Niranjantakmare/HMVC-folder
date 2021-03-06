<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">WebSiteName</a>
    </div>
    <ul class="nav navbar-nav">
      <li class="active"><a href="#">Home</a></li>
      <li><a href="#">Link</a></li>
      <li><a href="#">Link</a></li>
    </ul>
    <button onclick="logOut()" class="btn btn-danger navbar-btn">Log Out</button>
  </div>
</nav>

<div class="container">
  <h2>Navbar Button</h2>
  <p>Use the navbar-btn class on a button to vertically align (same padding as links) it inside the navbar.</p>
</div>

</body>

<script>
	function logOut(){
		var base_url="<?php echo base_url();?>";
		sessionStorage.removeItem('token');
		sessionStorage.removeItem('id');
		window.location.href = base_url+"login.html";
	}
</script>

</html>
