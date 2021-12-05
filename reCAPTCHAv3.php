<?php
  //Building Data Model
  $successMessage="";
  $errorMessage= "";
  $validFlag=false;
  $formSubmitFlag= false;
  $email="";
  $remoteIP="";
  //functions
  function emailValidate($value) {
      $incorrectEmail = false;
      if(strlen($value)!= 0) {
          global $errorMessage;
          $errorMessage = "Missing @";
        if(str_contains($value, '@')) {
          global $errorMessage; 
          $errorMessage = "Missng . operator ";
          if(str_contains($value, '.')) {
            $incorrectEmail = true;
            global $errorMessage;
            $errorMessage = "";
          }
        }
        
      }
      return $incorrectEmail;
  }

  //validage whether form has been submitted
  if($_SERVER['REQUEST_METHOD']=='POST' && emailValidate($_POST['email'])) {
    
    $validFlag = true;
    $email = $_POST['email'];
    $secret = "secret";
    $responseToken = $_POST["g-recaptcha-response"];
    $remoteIP =  getenv("REMOTE_ADDR");
    $url = "https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$responseToken&remoteip=$remoteIP";
    //the response is a JSON String
    $reCAPTCHAResponse = File_get_contents($url);
    //turned JSON String into PHP object for further processing
    $phpReCAPTCHAResponse = json_decode($reCAPTCHAResponse);
     $arrayPHPReCAPTCHAResponse = (array)$phpReCAPTCHAResponse;
    //Validation to determine if reCAPTCHAResponse object success property is true or not
     if($phpReCAPTCHAResponse->success==1 && $phpReCAPTCHAResponse->score >= 0.5 && $phpReCAPTCHAResponse->action == "submit" ) {
      $successMessage = "Congratulations you are Not a robot";
       
     }
     else {
      $errorMessage =  "Something went wrong";
     }
  }
  elseif($_SERVER['REQUEST_METHOD']=='POST' && empty($_POST['email'])){
    $errorMessage = "Please enter a email address!";
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
     document.getElementById("reCAPTCHAv3_form").submit();
   }
 </script>
 <link rel="stylesheet" href="reCAPTCHAv3.css">
</head>
<body>
  <div class="container">
  <header>WDV351 Ecommerce Store</header>
  <main>
  <form action="reCAPTCHAv3.php" method="post" id="reCAPTCHAv3_form">
    <div class="vertical--center">
      <div>
      <label for="email">Enter Email:</label>
      </div>
      <div>
      <input type="email" name="email" id="email" placeholder="john@abc.com" size="30" maxlength="64" minlength="3" required autofocus>
      </div>
      <span class="error-message"><?php echo $errorMessage;?></span>
      <span class="success-message"><?php echo $successMessage;?></span>
    </div>
    <button class="g-recaptcha" data-sitekey="siteKey" data-callback="onSubmit" data-action="submit">Signup</button>
    <input type="reset" value="Reset">
   
    
  </form>
  <?php if($validFlag) {
      $tablehead = [];
      $tableData = [];
      foreach($arrayPHPReCAPTCHAResponse as $key => $value) {
          array_push($tablehead, $key);
          array_push($tableData, $value);
       }
  ?>
    <table>
      <caption>reCAPTCHAv3 Results</caption>
      <thead><tr><th><?php echo $tablehead[0];?></th><th><?php echo $tablehead[1];?></th><th><?php echo $tablehead[2];?></th><th><?php echo $tablehead[3];?></th></tr></thead>
      <tbody><tr><td><?php echo $tableData[0];?></td><td><?php echo $tableData[1];?></td><td><?php echo $tableData[2];?></td><td><?php echo $tableData[3];?></td></tr></tbody>
    </table>
  <?php 
  } 
  ?>
  </main>
  <footer>WDV351 Ecommerce Store</footer>
  </div><!--end of container-->
</body>
</html>