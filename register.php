
<?php include 'server.php';?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    <!-- css link  -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    
    <?php if(isset($message)): ?>
        
            <div class="message" onclick="this.remove()">
                <?php echo $message ?>
            </div>
           
        <?php endif;?>
    <div class="form-container">
    
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
            <h3>Registeration</h3>
            <?php if(isset($errors)): ?>
        <?php foreach  ($errors as $error): ?>

            <div class="error" onclick="this.remove()">
                <?php echo $error ?>
            </div>
            <?php endforeach; ?>
        <?php endif;?>
            <input type="text" name="name" require class="box" placeholder="enter username">
            <input type="email" name="email" require class="box" placeholder="enter email">
            <input type="password" name="password" require class="box" placeholder="enter password">
            <input type="password" name="cpassword" require class="box" placeholder="confirm passeword">
            <input type="submit" name="register" class="btn" value="register">
            <p>Already have an account? <a href="login.php">Login</a></p>
        
        </form>

    </div>
    
</body>
</html>