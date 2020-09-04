<?php
session_start();
$error="";
if(array_key_exists('logout',$_GET) AND $_SESSION['id'])
{
// Initialize the session.
// If you are using session_name("something"), don't forget it now!
// Unset all of the session variables.
$_SESSION = array();
// If it's desired to kill the session, also delete the session cookie.
// Note: This will destroy the session, and not just the session data!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie("id", '', time() - 42000);
}

// Finally, destroy the session.
session_destroy();
  /*unset($_SESSION);
  setcookie(,"",time()-60*60);
  $_COOKIE['id']="";*/
}
else if((array_key_exists('id',$_COOKIE) AND $_COOKIE['id']) OR (array_key_exists('id',$_SESSION) AND $_SESSION['id']))
{
    header("Location: loggedin.php");
}

if(array_key_exists('submit',$_POST))
{
    
    include("connection.php");
      
  
  if(!$_POST["email"])
  {
      $error.="An email address is required! <br>";
  }
  if(!$_POST["password"])
  {
      $error.="A password is required! <br>";
  }
  if($error!="")
  {
      $error="There are errors in your form :<br>".$error;
  }
  else
  {
      if($_POST['SignUp']=='1')
      {
                    $query = "SELECT `id` FROM `Dairy` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                        
                        $result = mysqli_query($link, $query);
                        
                        if (mysqli_num_rows($result) > 0) {
                            
                            $error= "<p>That email address has already been taken.</p>";
                        }
                        else {
                            
                            $query = "INSERT INTO `Dairy` (`email`, `password`) VALUES ('".mysqli_real_escape_string($link, $_POST['email'])."', '".mysqli_real_escape_string($link,$_POST['password'])."')";
                            
                            if (mysqli_query($link, $query)) {
                                $salt=mysqli_insert_id($link);
                                $query= "Update `Dairy`SET password = '".md5(md5($salt).$_POST['password'])."' where id = '".$salt."'";
                                mysqli_query($link, $query);
                                $_SESSION['id']=$salt;
                                if($_POST['stayloggedin']==1)
                                {
                                    setcookie('id',$salt,time()+60*60*24*365);
                                }
                                header("Location:loggedin.php");
                            }
                            else {
                                
                                $error= "<p>There was a problem signing you up - please try again later.</p>";
                                }
                        }

        }
        else
        {
           $query = "SELECT * FROM `Dairy` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
                        
                        $result = mysqli_query($link, $query);
                        $row=mysqli_fetch_array($result);
                        if(isset($row))
                        {
                            $hashh=md5(md5($row['id']).$_POST['password']);
                            if($hashh==$row['password'])
                            {
                                $_SESSION['id']=$row['id'];
                                if($_POST['stayloggedin']==1)
                                {
                                    setcookie('id',$row['id'],time()+60*60*24*365);
                                }
                                header("Location:loggedin.php");
                            }
                            else
                        {
                            $error="Your email/password is incorrect";
                        }
                        }
                        else
                        {
                            $error="Your email/password is incorrect";
                        }
        }
  }
        
}
?>


<?php include("header.php");?>
  <body>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   <div class="container" id="homePageContainer">
    <h1>Secret Diary!</h1>
    <p><strong>Store your thoughts permanently and securely!</strong></p>
    <div id=error><?php if($error!=""){ echo '<div class="alert alert-danger" role="alert">
  '.$error.'</div>';}?></div>
    <form method="post" id="signUpForm">
    <p>Interested? Sign Up now.</p>
    <div class="form-group">
 <input class="form-control" type="email" name="email" placeholder="Enter your email">
 </div>
 <div class="form-group">
 <input class="form-control" type="password" name="password" placeholder="Enter your password">
 </div>

 <div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Stay logged in!</label>
  </div>
 <div class="form-group">
 <input class="form-control" type="hidden" name="SignUp" value="1">
 </div>
 <div class="form-group">
 <input class="btn btn-success" type="submit" name="submit" value="Sign Up!">
</div>

<p><a class="toggleForm">Log In</a></p>

</form>
<form method="post" id="logInForm">
<p>Log In using your username and password. </p>
 <div class="form-group">
 <input class="form-control"type="email" name="email" placeholder="Enter your email">
  </div>
 <div class="form-group">
 <input class="form-control"type="password" name="password" placeholder="Enter your password">
  </div>
 
 <div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="exampleCheck1">
    <label class="form-check-label" for="exampleCheck1">Stay logged in!</label>
  </div>
  <div class="form-group">
 <input class="form-control"type="hidden" name="SignUp" value="0">
  </div>
 <div class="form-group">
 <input class="btn btn-success"type="submit" name="submit" value="Login!">
 </div>
<p><a class="toggleForm">Sign up</a></p>

   </div>
   <?php include("footer.php");?>


   