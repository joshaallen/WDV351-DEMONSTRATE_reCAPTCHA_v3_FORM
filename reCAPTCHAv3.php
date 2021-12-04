<?php
  //Building Data Model
  $errorMessage= "";
  $validFlag=false;
  $formSubmitFlag= false;
  $name="";
  $remoteIP="";
  //validage whether form has been submitted
  if($_SERVER['REQUEST_METHOD']=='POST' && !empty($_POST['fName'])) {
    
    $validFlag = true;
    $name = $_POST['fName'];
    $secret = "secret";
    $responseToken = $_POST["g-recaptcha-response"];
    $remoteIP =  getenv("REMOTE_ADDR");
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$responseToken&remoteip=$remoteIP";
    //the response is a JSON String
    $reCAPTCHAResponse = File_get_contents($url);
    echo $reCAPTCHAResponse;
    //turned JSON String into PHP object for further processing
    $reCAPTCHAResponse = json_decode($reCAPTCHAResponse);
    //Validation to determine if reCAPTCHAResponse object success property is true or not
     if($reCAPTCHAResponse->success==1 && $reCAPTCHAResponse->score >= 0.5 && $reCAPTCHAResponse->action == "submit" ) {
       echo "Hello World";
       
     }
     else {
       echo "Didn't work";
     }
  }
  elseif($_SERVER['REQUEST_METHOD']=='POST' && empty($_POST['fName'])){
    $errorMessage = "Please fill in first name!";
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="keywords" content="reCAPTCHA, Web Form, Validation">
  <meta name="description" content="This is Web Form will use reCAPTCHA v3 to validate users.">
  <meta name="author" content="Joshua Allen">
  <title>reCAPTCHA v3 WebForm</title>
  <script src="https://www.google.com/recaptcha/api.js"></script>
  <script>
   function onSubmit(token) {
     document.getElementById("form1").submit();
   }
 </script>
 <link rel="stylesheet" href="reCAPTCHAv2.css">
</head>
<body>
  <div class="container">
  <header>WDV351 Ecommerce Store</header>
  <main>
  <form action="reCAPTCHAv3.php" method="post" id="form1">
    <div>
    <label for="fName">First Name:</label>
    <input type="text" name="fName" id="fName" placeholder="Enter your name" required autofocus>
    <?php echo $errorMessage;?>
    </div>
    <button class="g-recaptcha" data-sitekey="siteKey" data-callback="onSubmit" data-action="submit">Submit</button>
  </form>
  <?php if($validFlag) {echo "<div>Your are Verified!!!</div><div>Your Name is:$name</div><div>Your IP address is:$remoteIP</div>";header( "refresh:10; url=reCAPTCHAv3.php" );} ?>
  </main>
  <footer>WDV351 Ecommerce Store</footer>
  </div>
</body>
</html>