<?php 
    session_start();

    if (isset($_SESSION["search"])) {
        unset($_SESSION["search"]);
    }

    if (isset($_SESSION["recommendedResults"])) {
        unset($_SESSION["recommendedResults"]);
    }

    include "./includes/head.inc.php";
?>
    <body>
        <main>
            <?php if (isset($_SESSION["apiKey"])) { ?>
                <h1><strong>RequestSEO</strong>, una potente herramienta de <strong>SEO</strong> para buscar palabras clave</h1>
                <form action="./apiConection.php" method="post" autocomplete="off">
                    <img src="./img/favicon.png" alt="Icono de la web">
                    <input type="text" name="search" required autofocus>
                    <input type="submit" style="display: none;">
                </form>
                <?php if (isset($_SESSION["error"])) { ?>
                    <p style="color: red;"><?php echo $_SESSION["error"] ?></p>
                    <?php if ($_SESSION["error"] !== "Error al interpretar la respuesta, vuelva a probar.") { ?>
                        <br>
                        <p style="color: red;">Si el error persiste puede buscar ayuda <a href='https://help.openai.com/en/collections/3742473-chatgpt'>aquí</a>.</p>
                    <?php } ?>
                    <?php unset($_SESSION["error"]) ?>
                <?php } ?>
            <?php } else { ?>
                <h1><strong>RequestSEO</strong></h1>
                <p style="color: grey;">Ponga su clave API de ChatGPT para poder hacer uso de esta aplicación...</p>
                <form action="./startSession.php" method="post" autocomplete="off">
                    <input type="text" name="apiKey" placeholder="sk-..." required autofocus>
                    <input type="submit" style="display: none;">
                </form>
            <?php } ?>
        </main>
        <?php include "./includes/footer.inc.php"; ?>
    </body>
</html>