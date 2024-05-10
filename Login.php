
<?php
require 'Functions/Connect.php'; //Loads DB connection
session_start();
if(isset($_SESSION["ID"])) {
    echo "stop";
    header('Location: index.php');
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="Style/Login.css">

    <link rel="stylesheet" href="Style/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#loginform').submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                $.ajax({
                    type: "POST",
                    url: "Functions/LoginHandler.php",
                    data: $(this).serialize(),
                    success: function(response) {
                        console.log(response);
                        if (response === "1") {
                            window.location.href = "index.php"; // Redirect on success
                        } else if (response == "2") {
                            $('#ErrorMsg').text("Invalid credentials.");
                            resetErrorMessage();
                        } else {
                            $('#ErrorMsg').text("An error occurred.");
                            resetErrorMessage();
                        }
                    }
                });
            });
        });
        function resetErrorMessage() { //Resets error message for better UI experience
            setTimeout(function() {
                document.getElementById("ErrorMsg").textContent = "Log In";
            }, 5000);
        }
    </script>
</head>
<body class="Standard-Form">
<div class="bg-gradient"></div>
<div class="login-card">
    <div class="LightHeading"> Sign in</div>
    <form id="loginform" method="post" class="Standard-Form">
        <input class="Standard-Form" name="Email" id="Email" type="text" placeholder="Enter Email">
        <input class="Standard-Form" name="Password" id="Password" type="password" placeholder="Enter password">
        <button class="Standard-Form" id="ErrorMsg" type="submit">Login</button>
        <span class="SubText">Dont have an account? <a class="Register" href="Register.php">Register Now!</a></span>

    </form>
</div>
</body>
</html>