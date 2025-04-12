<?php
include 'conexion.php';
$mensaje = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);

    $stmt = $conexion->prepare("SELECT UsrId FROM usuarios WHERE UsrEmail = ? AND UsrStatus = '1'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($usrId);
        $stmt->fetch();

        $token = bin2hex(random_bytes(10));

        $stmtReset = $conexion->prepare("INSERT INTO resets (user_id, token) VALUES (?, ?)");
        $stmtReset->bind_param("is", $usrId, $token);
        $stmtReset->execute();

        $enlace = "http://localhost/TrabajoGrupal1-DSW/resetPassword.php?token=$token";
        $mensaje = "A link has been generated:<br><a href='$enlace'>Click here to reset your password</a>";
    } else {
        $mensaje = "Invalid email or inactive account.";
    }
}
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
            <?php if ($mensaje) echo "<p style='color:white; text-align:center;'>$mensaje</p>"; ?>
        </form>
    </section>

</body>

</html>
