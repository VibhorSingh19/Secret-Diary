<?php
    session_start();
 if($_SESSION['id'])
  {

  }
  else
  {
      header("location:index.php");
  }
  
        include("connection.php");
        
        $query = "UPDATE `Dairy` SET `mydairy` = '".mysqli_real_escape_string($link, $_POST['DIARY'])."' WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
        
        mysqli_query($link, $query);
       header("location:index.php");
?>

