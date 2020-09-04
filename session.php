<?php
   session_start();
  if($_SESSION['email'])
  {
   echo $_SESSION['email']." has been logined!";
  }
  else
  header("Location: index.php");
?>