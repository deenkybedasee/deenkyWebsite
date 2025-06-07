<?php
session_start();
@include 'config.php';

if(isset($_POST['submit'])) {
    if(isset($_GET['register'])) {
        // Registration logic
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $cpass = password_hash($_POST['cpassword'], PASSWORD_DEFAULT);
        $role = 'customer'; // Force customer role during registration


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
                header('location:login_form.php?success=registered');
                exit();
            }
        }
    } else {
        // Login logic
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = $_POST['password'];

        $select = "SELECT * FROM user WHERE email = '$email'";
        $result = mysqli_query($conn, $select);

        if(mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            if(password_verify($password, $row['password'])) {
              
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                $_SESSION['user_email'] = $row['email'];
                $_SESSION['user_role'] = $row['role'];
                $_SESSION['created_at'] = $row['created_at'];

                if($row['role'] == 'admin') {
                    header('location:admin_page.php');
                } else {
                    header('location:index.php');
                }
                exit();
            } else {
                $error[] = 'Incorrect password!';
            }
        } else {
            $error[] = 'User not found!';
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
   <title>Login & Register</title>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<div class="form-container">
   <form action="" method="post">
      <h3><?php echo isset($_GET['register']) ? 'Register Now' : 'Login Now'; ?></h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         }
      }
      if(isset($_GET['success']) && $_GET['success'] == 'registered') {
         echo '<span class="success-msg">Registration successful! Please login.</span>';
      }
      ?>
      <?php if(isset($_GET['register'])): ?>
      <input type="text" name="name" required placeholder="Enter your name">
      <?php endif; ?>
      <input type="email" name="email" required placeholder="Enter your email">
      <input type="password" name="password" required placeholder="Enter your password">
      <?php if(isset($_GET['register'])): ?>
      <input type="password" name="cpassword" required placeholder="Confirm your password">
      
      <?php endif; ?>
      <input type="submit" name="submit" value="<?php echo isset($_GET['register']) ? 'Register Now' : 'Login Now'; ?>" class="form-btn">
      <?php if(isset($_GET['register'])): ?>
      <p>Already have an account? <a href="login_form.php">Login now</a></p>
      <?php else: ?>
      <p>Don't have an account? <a href="login_form.php?register=1">Register now</a></p>
      <?php endif; ?>
   </form>
</div>

</body>
</html>
