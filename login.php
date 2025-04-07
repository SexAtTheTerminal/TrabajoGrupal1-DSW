<?php
include 'conexion.php';

if (!empty($_POST)) {
    $username = mysqli_real_escape_string($conexion, $_POST["username"]);
    $password = mysqli_real_escape_string($conexion, $_POST["password"]);
    $password_encripted = substr(sha1($password), 0, 10);

    $sql = "SELECT UsrId FROM usuarios WHERE UsrLogin = '$username' AND UsrPassword = '$password_encripted'";
    echo "<script>
                alert('Hasta aca ha servido1');
        </script>";
    $resultado = $conexion->query($sql);
    echo "<script>
                alert('Hasta aca ha servido2');
        </script>";
    $filas = $resultado->num_rows;
    echo "<script>
                alert('Hasta aca ha servido $filas');
        </script>";

    if($filas > 0){
        $fila = $resultado->fetch_assoc();
        echo "<script>
                alert('Hasta aca ha servido3');
        </script>";
        $_SESSION['id_usuario'] = $fila["UsrId"];
        $_SESSION['username'] = $username;
        header("Location: postLogin.php");
        exit();
    } else {
        echo "<script>
                alert('El username o password son incorrectos');
                window.location = './login.php';
        </script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="./style.css">
    <script src="./signup.js" defer></script>
</head>

<body>

    <body>
        <section>

            <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST">
                <h1>Login</h1>
                <div class="inputbox">
                    <ion-icon name="email-outline"></ion-icon>
                    <input class= "form-control" name="username" id="username" type="text" required>
                    <label for="">Username</label>
                </div>
                <div class="inputbox">
                    <ion-icon name="lock-closed-outline"></ion-icon>
                    <input class = "form-control" name="password" id="password" type = "password" required>
                    <label for="">Password</label>
                </div>
                <div class="register">
                    <p><a href="./recovery.php">Forget Password ?</a></p>
                </div>
                <button type="submit" name = "login" >Log in</button>
                <div class="register">
                    <p>Don't have a account ?<a href="./signup.php"> Register!</a></p>
                </div>
            </form>
        </section>
    </body>
</body>

</html>