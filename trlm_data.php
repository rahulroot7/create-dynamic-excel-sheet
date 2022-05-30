<?php

$downloadPassword = "!@#trlm321!@#";
$passwordError = "";

if(isset($_POST['psw'])){
  if(htmlspecialchars($_POST['psw']) == $downloadPassword){
    header("location:http://xeamventures.com/wp-content/themes/xeamventures-child/TSRLM/trlm_data_file.php");
    exit;
  }

  if(htmlspecialchars($_POST['psw']) != $downloadPassword && htmlspecialchars($_POST['psw']) != ""){
    $passwordError = "Wrong Password.";
  }

  if(htmlspecialchars($_POST['psw']) == ""){
    $passwordError = "Password field is required.";
  }
}


?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<div class="container">
 <h2>Enter Password To Access This Page</h2>

<div class="form_pass"> 
 <form action="" method="post">
  <div class="form-group">
      <label for="psw">Password:</label>
      <input type="password" name="psw"  class="form-control" id="psw" Placeholder="Fill Password"> </br>
      <input type="hidden" name="recaptcha" id="recaptcha">
      <input type="submit"  class="btn btn-default sub_btn1" name="submit" Value="Submit"> 
   
	</div>
</form> 


<div class="result text-danger"><?php echo $passwordError; ?></div>
</div>
</div>

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->

<script>         

grecaptcha.ready(function() {
    grecaptcha.execute('{{ "6Le0pMwUAAAAAKRxsNL0vlLdOvW9D6y5qFUJclQ1" }}', {action: 'job_data_mpsegc.php'}).then(function(token) { 
        if (token) {                    
            console.log(token);                  
            document.getElementById('recaptcha').value = token;               
         }            

     });
});
</script>

</div>