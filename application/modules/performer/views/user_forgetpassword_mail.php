<!doctype html>
<html>
	<head>
	<title>Forgot Password</title>
	</head>
	<body>
	<div style="border-left: 1px solid #ddd;min-height: 52px;border-right: 1px solid #ddd;border-top: 1px solid #ddd;color: #ffffff;margin: 0 auto;
    padding: 11px 0 10px 12px;background-color: #4b2d8a"><div class="adM"> 
    </div>

    <h2>Total Request Live</h2>
</div>



<div style="border:1px solid #ddd;color:#666;margin:0 auto;padding:0 20px 20px">
    <div style="color:#666">
           
            <h4 style="font-family:arial!important"> Hi <?php
              if(isset($firstname))
              {
                  echo ucfirst($firstname);
              }
            ?>,</h4>

            <div style="font-size:12px;line-height:20px;font-family:arial!important">
            <p>We have received a request from you to your Username and password.</p>
            <p>Here is below your username and password.</p>
			<p>Username : <?php  if(isset($email)){echo $email; }?></p>
			<p>Password : <?php  if(isset($password)){echo $password; }?></p>
			
             <br/>
			
            <p>If you have not made any such request, please contact us immediately on <a href="mailto:support@trl.com">support@trl.com</a></p>
            <br/>
            <p> Thank you for using TRL.com...!</p>
            <p>Regards,<br/>
            <label style="font-weight:bold;font-size:14px;" >TRL team.
            </label>


            </div>
        </div>
       </div>
    


	<div style="background-color:rgb(237,240,245)!important;margin:0px auto;padding:10px 20px;min-height:50px;clear:both">
        <div style="width:70%;float:left;color:#666;padding-top:10px;font-size:12px">Mail us at - <a target="_blank" href="" style="color:#666!important">sales@<span class="il">TRL</span>.com</a><span style="margin:0 10px">OR</span> <b>SMS</b>  to 58888

        </div>
	</div>

	</body>
</html>