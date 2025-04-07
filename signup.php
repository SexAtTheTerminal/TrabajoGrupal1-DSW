<?php
include 'conexion.php';

if(isset($_POST["registrar"])){
    
    // Validación del lado del servidor

    $name = trim($_POST["name"]);
    $username = trim($_POST["username"]);
    $mail = trim($_POST["email"]);
    $password = $_POST["password"];
    $passwordcon = $_POST["passwordcon"];
    
    if (empty($name) || empty($username) || empty($mail) || empty($password) || empty($passwordcon)) {
        echo "<script>
                alert('Por favor, completa todos los campos');
                window.location = './signup.php';
            </script>";
        exit;
    }

    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
                alert('El correo no es válido');
                window.location = './signup.php';
            </script>";
        exit;
    }

    if ($password !== $passwordcon) {
        echo "<script>
                alert('Las contraseñas no coinciden');
                window.location = './signup.php';
            </script>";
        exit;
    }
    
    //------------------------------------------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------------------------------------

    //Validacion de Cliente

    $name = mysqli_real_escape_string($conexion, $_POST["name"]);
    $username = mysqli_real_escape_string($conexion, $_POST["username"]);
    $mail = mysqli_real_escape_string($conexion, $_POST["email"]);
    $password = mysqli_real_escape_string($conexion, $_POST["password"]);
    $passwordcon = mysqli_real_escape_string($conexion, $_POST["passwordcon"]);

    $password_encripted = sha1($password);
    $fecha_actual = date('Y-m-d');
    $hora_actual = date('H:i:s'); 
    $sqluser = "SELECT UsrId FROM usuarios WHERE UsrLogin = '$username'";

    $resultadouser = $conexion->query($sqluser);
    $filas = $resultadouser->num_rows;
    if($filas > 0){
        echo "<script>
                alert('El nombre de usuario ya existe');
                window.location = './login.php';
        </script>";
    }else{
        $sqlusuario = "INSERT INTO usuarios (UsrName, UsrLogin, UsrPassword, UsrEmail, UsrDateBegin, UsrTimeBegin, UsrDateEnd, UsrTimeEnd,UsrStatus) 
        VALUES ('$name', '$username', '$password_encripted', '$mail', '$fecha_actual', '$hora_actual', '$fecha_actual', '$hora_actual', 1)";
        $resultadouser = $conexion->query($sqlusuario);
        if($resultadouser > 0){
            echo "<script>
                    alert('Usuario registrado correctamente');
                    window.location = './login.php';
            </script>";
    }else{
        echo "<script>
                alert('Error al registrar el usuario');
                window.location = './signup.php';
            </script>";
        }   
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="./style.css">
</head>

<body>
    <section>
        <form action="<?php $_SERVER["PHP_SELF"]; ?>" method="POST">
            <h1>Sign Up</h1>
            <div class="inputbox">
                <ion-icon name="person-outline"></ion-icon>
                <input class = "form-control" type="text" id="name" name="name" required>
                <label for="">Name</label>
            </div>
            <div class="inputbox">
                <ion-icon name="person-outline"></ion-icon>
                <input class = "form-control" type="text" id="username" name="username" required>
                <label for="">Username</label>
            </div>
            <div class="inputbox">
                <ion-icon name="mail-outline"></ion-icon>
                <input class = "form-control" type="email" id="email" name="email" required>
                <label for="">Email</label>
            </div>

            <div class="inputbox">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input class = "form-control" type="password" id="password" name="password" required>
                <label for="">Password</label>
            </div>
            <div class="inputbox">
                <ion-icon name="lock-closed-outline"></ion-icon>
                <input class = "form-control" type="password" id="passwordcon" name="passwordcon" required>
                <label for="">Confirm Password</label>
            </div>
            <button id="submit" type="submit" name ="registrar" >Sign Up</button>
            
            <div class="register">
                <p>Already have an account? <a href="./login.php">Log In</a></p>
            </div>
        </form>
    </section>
</body>

</html>

<script>
    document.querySelector("form").addEventListener("submit", function(e) {
        const pass = document.getElementById("password").value;
        const passcon = document.getElementById("passwordcon").value;
        const email = document.getElementById("email").value;

        if (pass !== passcon) {
            e.preventDefault();
            alert("Las contraseñas no coinciden.");
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            e.preventDefault();
            alert("El correo no es válido.");
        }

    });
</script>
</html>
