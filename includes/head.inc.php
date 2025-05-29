<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            <?php
                if (!isset($_SESSION["apiKey"])) {
                    echo "Iniciar sesión - RequestSEO";
                } else {
                    if (isset($_SESSION["search"])) {
                        echo "Resultados " . $_SESSION["search"] . " - RequestSEO";
                    } else {
                        echo "Inicio - RequestSEO";
                    }
                }
            ?>
        </title>
        <meta name="author" content="Álvaro Martí">
        <meta name="description" content="Web creada por Álvaro Martí para la agencia Monllor SEO">
        <link rel="stylesheet" href="./style/style.css">
        <link rel="icon" type="image/png" href="./img/favicon.png">
    </head>