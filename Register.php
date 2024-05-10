<?php

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
    <title>Register</title>
    <link rel="stylesheet" href="Style/Login.css">
    <link rel="stylesheet" href="Style/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#registrationForm').submit(function(event) {
                event.preventDefault(); // Prevent default form submission
                const password = document.getElementById('Password').value;
                const confirmPassword = document.getElementById('PasswordRe').value;
                if (password !== confirmPassword) {
                    alert("Passwords do not match!");
                } else {
                    $.ajax({
                        type: "POST",
                        url: "Functions/Register.php",
                        data: $(this).serialize(),
                        success: function (response) {
                            console.log(response);
                            if (response === 1) {
                                window.location.href = "index.php"; // Redirect on success
                            } else if (response == 2) {
                                $('#ErrorMsg').text("Please try again later");
                                resetErrorMessage();
                            } else if(response == 3){
                                $('#ErrorMsg').text("Email already exists!");
                                resetErrorMessage();
                            }
                        }
                    });
                }
            });
        });
        function resetErrorMessage() { //Resets error message for better UI experience
            setTimeout(function() {
                document.getElementById("ErrorMsg").textContent = "Register";
            }, 5000);
        }
    </script>


</head>

<body class="Standard-Form">
<div class="bg-gradient"></div>
<div class="login-card">
    <div class="LightHeading"> Register</div>
    <form id="registrationForm" method="post" action="Functions/Register.php" class="Standard-Form">
        <input class="Standard-Form" name="Name" id="Name" type="text" placeholder="Name">
        <input class="Standard-Form" name="Email" id="Email" type="text" placeholder="Email">
        <input class="Standard-Form" name="Password" id="Password" type="password" placeholder="Password">
        <input class="Standard-Form" name="PasswordRe" id="PasswordRe" type="password" placeholder="Re-Enter Password">
        <button class="Standard-Form" id="ErrorMsg" type="submit">Register</button>
        <span class="SubText">Have an account? <a class="Register" href="Login.html">Sign in!</a></span>
    </form>
</div>
</body>






</html>