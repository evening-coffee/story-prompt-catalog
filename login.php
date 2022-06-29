<?php 

include_once 'connect.php';

function redirect_to($otherplace) {
    header("Location: {$otherplace}");
    exit;
  }

if (isset($_SESSION['user'])){ //Checks to see if there's already an open session
    redirect_to('index.php'); //If so, the user will be kicked out of the login page, and redirected back to the index.
} else {

}


?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Prompt Catalog</title>
        <link rel="stylesheet" href="stylesheet.css">
        <link rel="icon" sizes="any" type="image/svg+xml" href="pen-nib-logo.svg">
    </head>
    <body id="login-body"> 
        <main>
            <form action="login-process.php" class="login-form" method="post">
            <h1 class="login-logo"><img src="pen-nib-logo.svg"><span class="login-logo-text">Prompt Catalog</span></h1>
            <p id="login-subheader">Please log in to continue</p>
            <?php
              if (isset($_GET['success'])){
                echo '<p id="login-error">Incorrect username or password</p>';
                }
            ?>
                <input class="login-textbox" type="text" name="username" id="username" placeholder="Enter your username" value="">
                <input class="login-textbox" type="password" name="password" id="password" placeholder="Enter your password" value="">
                <input class="login-submit" id="login-submit" type="submit" name="login-submit" value="Log In">
                <p class="login-question">Don't have an account? <a class="login-question-link" href="create-account.php">Create one!</a></p>
            </form>
    
        </main>

    </body>
</html>