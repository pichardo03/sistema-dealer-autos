<?php

class AuthController {

    public function login() {
        require_once __DIR__ . "/../views/login.php";
    }

    public function validar() {

        $usuario = $_POST['usuario'] ?? '';
        $password = $_POST['password'] ?? '';

        if($usuario === "admin" && $password === "1234") {
            $_SESSION['usuario'] = $usuario;
            header("Location: index.php?controller=dashboard");
        } else {
            echo "<script>alert('Usuario o contraseña incorrectos'); window.location='index.php';</script>";
        }
    }

    public function logout() {
        session_destroy();
        header("Location: index.php");
    }
}