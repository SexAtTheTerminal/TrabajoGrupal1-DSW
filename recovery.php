<?php
include 'conexion.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Recover Password</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="./style.css">
    <script src="./forgot-password.js" defer></script>
</head>

<body>

    <section>
        <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST">
            <h1>Recover Password</h1>
            <div class="dialog-row">
                <label th:if="${param.error}" th:text="${session['SPRING_SECURITY_LAST_EXCEPTION'].message}" class="text-center redText"></label>
            </div>
            <div class="inputbox">
                <ion-icon name="mail-open-outline"></ion-icon>
                <input name="email" id="email" type="email" required>
                <label for="">Email Address</label>
            </div>
            <div class="register">
                <p>Enter your email to receive a password reset link.</p>
            </div>
            <button>Send Reset Link</button>
            <div class="register">
                <p><a href="./login.php">Back to Login</a></p>
            </div>
        </form>
    </section>

</body>

</html>
