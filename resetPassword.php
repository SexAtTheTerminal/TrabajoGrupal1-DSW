<?php
include 'conexion.php';
$mensaje = '';
$tokenValido = false;
$userId = null;

if (isset($_GET['token'])) {
    $token = $_GET['token'];
    // Consulta a la base de datos - tabla resets, si es valido el token
    $stmt = $conexion->prepare("SELECT user_id FROM resets WHERE token = ? AND used = 0 AND created_at >= NOW() - INTERVAL 15 MINUTE");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userId);
        $stmt->fetch();
        $tokenValido = true;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $newPassword = trim($_POST["new_password"]);
            $password_encripted = substr(sha1($newPassword), 0, 10);

            $stmtUpdate = $conexion->prepare("UPDATE usuarios SET UsrPassword = ? WHERE UsrId = ?");
            $stmtUpdate->bind_param("si", $password_encripted, $userId);
            $stmtUpdate->execute();

            $stmtUsed = $conexion->prepare("UPDATE resets SET used = 1 WHERE token = ?");
            $stmtUsed->bind_param("s", $token);
            $stmtUsed->execute();

            $mensaje = "Password updated successfully :)";
            $tokenValido = false;
        }
    } else {
        $mensaje = "Este link ha expirado o no es valido.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <section>
        <form method="POST">
            <h1>Reset Password</h1>

            <?php if ($tokenValido): ?>
                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input name="new_password" id="new_password" type="password" required>
                    <label>New Password</label>
                </div>
                <button type="submit">Update Password</button>
            <?php endif; ?>

            <div class="register">
                <p><a href="./login.php">Back to Login</a></p>
            </div>

            <?php if ($mensaje) echo "<p style='color:white;text-align:center;'>$mensaje</p>"; ?>
        </form>
    </section>
</body>
</html>
