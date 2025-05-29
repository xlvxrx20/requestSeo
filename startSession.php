<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["apiKey"])) {
        $_SESSION["apiKey"] = $_POST["apiKey"];
    }

    header("Location: ./");