<?php
include 'conexion.php';
date_default_timezone_set('America/Lima');

if (!empty($_POST)) {
    $username = mysqli_real_escape_string($conexion, $_POST["username"]);
    $password = mysqli_real_escape_string($conexion, $_POST["password"]);
    $password_encripted = substr(sha1($password), 0, 10);

    $sql = "SELECT UsrId, UsrLogin, UsrPassword, UsrStatus, UsrBadAttempts, UsrLastTry FROM usuarios WHERE UsrLogin = '$username'";
    $resultado = $conexion->query($sql);
    $filas = $resultado->num_rows;
    if($filas > 0){
        $fila = $resultado->fetch_assoc();
        if($fila["UsrStatus"] == '0'){
            echo "<script>
                    alert('El usuario no esta activo, por favor contacta al administrador');
                    window.location = './login.php';
            </script>";
            exit();
        }
        
        $intentos = (int)$fila['UsrBadAttempts'];
        $ultimoIntento = $fila['UsrLastTry'];

        if ($ultimoIntento !== null) {
            $timestampUltimo = strtotime($ultimoIntento);
            $timestampAhora = time(); // timestamp actual en segundos

            $diferenciaSegundos = $timestampAhora - $timestampUltimo;

            if ($diferenciaSegundos > 600) { // 600 segundos = 10 minutos
                $intentos = 0; // reiniciar intentos
            }
        }

        $diferenciaMinutos = floor($diferenciaSegundos / 60);
        echo "<script>alert('Han pasado $diferenciaMinutos minutos desde el último intento');</script>";
        
        if($fila['UsrPassword'] == $password_encripted){//Logeo exitoso wasaaa
            $stmtUpdate =  $conexion -> prepare ("UPDATE usuarios SET UsrBadAttempts = 0, 
            UsrLastTry = NULL WHERE UsrLogin ='$username'");
            $stmtUpdate -> execute();
            $_SESSION['id_usuario'] = $fila["UsrId"];
            $_SESSION['username'] = $username;
            header("Location: postLogin.php");
            exit(); 
        }else{
            $intentos++;
            if($intentos==5){
                $stmtUpdate2 = $conexion -> prepare("UPDATE usuarios SET UsrStatus = 0, 
                UsrBadAttempts = $intentos, 
                UsrLastTry = NOW() WHERE UsrLogin = '$username'");
                $stmtUpdate2 -> execute();
                echo "<script>
                        alert('Has excedido los intentos. Tu cuenta ha sido bloqueada.');
                        window.location = './login.php';
                </script>";
            }else{
                $stmtUpdate3 = $conexion -> prepare("UPDATE usuarios 
                SET UsrBadAttempts= ?, 
                UsrLastTry= NOW() 
                WHERE UsrLogin = '$username'");
                $stmtUpdate3 -> bind_param("i", $intentos);
                $stmtUpdate3 -> execute();
                echo "<script>
                alert('El username o password son incorrectos.\\nIntentos restantes: " . (5 - $intentos) . ".\\nSe resetea luego de 10 minutos.');
                window.location = './login.php';
                </script>";
            }
        }
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