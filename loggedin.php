<?php
   session_start();
   
   //$diaryContent = "":
   if(array_key_exists('id',$_COOKIE))
   {
       $_SESSION['id']=$_COOKIE['id'];
   }
    if(array_key_exists('id',$_SESSION))
   {
       
   
       include("connection.php");
      
      $query = "SELECT mydairy FROM `Dairy` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
      $row = mysqli_fetch_array(mysqli_query($link, $query));
      
      $diaryContent = $row['mydairy'];
      
   }
   else
   {
       header("Location:index.php");
   }
   
   
   include("header.php");
   ?>
   <nav class="navbar navbar-light bg-light navbar-fixed-top">
  <a class="navbar-brand">Secret Diary</a>
  
  <div class="form-inline" id="logbutton">
     <a href='index.php?logout=1'><button  class="btn btn-outline-success my-2 my-sm-0" >Log out</button></a>
  </div>

   <form action="updateDatabase.php"method="POST">
   <div class="form-inline" id="updatebtn">
     <button  class="btn btn-outline-success my-2 my-sm-0" type="Submit" name="update">Update</button>
  </div>
  </nav>
   <div class="cotainer-fluid" id="containerLoggedInPage">
   <textarea id="DIARY" name="DIARY" class="form-control"><?php echo $diaryContent; ?></textarea>
   </div>
  
   
   
  </form>
   <?php
   include("footer.php");
?>