<?php
@include 'config.php';

if(isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $cpass = password_hash($_POST['cpassword'], PASSWORD_DEFAULT);

    $role = 'customer'; // Set default role as customer

    $select = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $select);

    if(mysqli_num_rows($result) > 0) {
        $error[] = 'User already exists!';
    } else {
        if($_POST['password'] != $_POST['cpassword']) {
            $error[] = 'Passwords do not match!';
        } else {
            $insert = "INSERT INTO user(name, email, password, role) VALUES('$name','$email','$pass','$role')";
            mysqli_query($conn, $insert);
            header('location:login_form.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Form</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<div class="form-container">
   <form action="" method="post">
      <h3>Register Now</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="text" name="name" required placeholder="Enter your name">
      <input type="email" name="email" required placeholder="Enter your email">
      <input type="password" name="password" required placeholder="Enter your password">
      <input type="password" name="cpassword" required placeholder="Confirm your password">
      <input type="submit" name="submit" value="Register Now" class="form-btn">
      <p>Already have an account? <a href="login_form.php">Login now</a></p>
   </form>
</div>

</body>
</html>
