<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Sign up</title>
   </head>
   <body>
      <form action="index.php" method="POST">
         <?php 

            if(isset($_GET['first'])) {
               $first = $_GET['first'];
               echo '<div><input type="text" name="first" placeholder="Firstname" value="'.$first.'"></div>';
            } else {
               echo '<div><input type="text" name="first" placeholder="Firstname"></div>';
            }
            if(isset($_GET['last'])) {
               $last = $_GET['last'];
               echo '<div><input type="text" name="last" placeholder="Lastname" value="'.$last.'"></div>';
            } else {
               echo '<div><input type="text" name="last" placeholder="Lastname"></div>';
            }
            

         ?>
         <div>
            <input type="text" name="email" placeholder="Email">
         </div>
         <?php 
            if(isset($_GET['uid'])) {
               $uid = $_GET['uid'];
               echo '<div><input type="text" name="uid" placeholder="Username" value="'.$uid.'"></div>';
            } else {
               echo '<div><input type="text" name="uid" placeholder="Username"></div>';
            }
         ?>
         <div>
            <input type="password" name="pwd" placeholder="Password">
         </div>
         <button type="submit" name="signup">Sign up</button>
      </form>

      <?php 
         // Method 1
         // $fullUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; // get url which generated this 
         // if (strpos($fullUrl, "signup=empty") == true) {
         //       echo "<p class='error'>You did not fill in all fields!</p>";
         //       exit();
         // }   
         // elseif (strpos($fullUrl, "signup=invalid") == true) {
         //    echo "<p class='error'>You used invalid characters!</p>";
         //    exit();
         // }
         // elseif (strpos($fullUrl, "signup=email") == true) {
         //    echo "<p class='error'>You used invalid email!</p>";
         //    exit();
         // }
         // elseif (strpos($fullUrl, "signup=success") == true) {
         //    echo "<p class='success'>You have been signed up!</p>";
         //    exit();
         // }  

         // Method 2

         if (!isset($_GET['signup'])) {
            exit();
         }
         else {
            $signupCheck = $_GET['signup']; // we get the string that comes after signup = ...

            if ($signupCheck == "empty") {
               echo "<p class='error'>You did not fill in all fields!</p>";
               exit();
            } elseif ($signupCheck == "invalid") {
               echo "<p class='error'>You used invalid characters!</p>";
               exit();
            } elseif ($signupCheck == "email") {
               echo "<p class='error'>You used invalid email!</p>";
               exit();
            } elseif ($signupCheck == "success") {
               echo "<p class='success'>You have been signed up!</p>";
               exit();
            }
         }


      ?>

   </body>
</html>