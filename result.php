<?php 
    session_start();
    include "./includes/head.inc.php";
?>
    <body>
        <main>
            <h2>Resultados para: "<em><?php echo $_SESSION["search"] ?></em>"</h2>
            <p>Marque en rojo las respuestas que no le parecen apropiadas y vuelva a hacer la busqueda con el botón de abajo si lo desea.</p>
            <p></p>
            <table>
                <thead>
                    <tr>
                        <th>¿Mantener?</th>
                        <th>Busqueda</th>
                        <th>¿Intención de compra?</th>
                    </tr>
                </thead>
                <?php
                    for ($i = 0; $i < count($_SESSION["recommendedResults"]); $i++) {
                        $recomendedResult = $_SESSION["recommendedResults"][$i];
                ?>
                    <tr>
                        <td><input type="checkbox" checked></td>
                        <td class="tableText"><?php echo ($i+1) . ". " . $recomendedResult[0]; ?></td>
                        <td>
                            <?php if ($recomendedResult[1] == true) { ?>
                                <img src="./img/tick.png" alt="Con intención de compra">
                            <?php } else { ?>
                                <img src="./img/x.png" alt="Sin intención de compra">
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </table>
            
            <div id="botonesBusqueda">
                <form action="index.php" method="get"><input type="submit" value="Volver a inicio" id="volverInicio"></form>
                
                <form action="downloadCSV.php" method="post" id="formExportarCSV">
                    <input type="submit" value="Exportar a archivo .csv" id="exportarCSV">
                    <input type="hidden" name="search" value="<?php echo $_SESSION["search"] ?>">
                </form>
                
                <form action="apiConection.php" method="post" id="formRepetirBusqueda">
                    <input type="submit" value="Repetir la busqueda" id="repetirBusqueda">
                    <div id="numeroNuevaBusqueda">
                        <label for="numberOfSearchs">Resultados nueva búsqueda</label>
                        <input type="number" name="numberOfSearchs" value="25">
                    </div>
                    <input type="hidden" name="search" value="<?php echo $_SESSION["search"] ?>">
                </form>
            </div>
        </main>
        <?php include "./includes/footer.inc.php"; ?>
    </body>
    <script src="./scripts/script.js"></script>
</html>