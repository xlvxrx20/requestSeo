<?php
    session_start();
    
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['markedSearches'])) {
        $selectedItems = json_decode($_POST['markedSearches'], true);

        $fileName = $_SESSION["search"];
        header('Content-Type: text/csv');
        $filename = isset($_SESSION["search"]) ? $_SESSION["search"] . '.csv' : 'resultados.csv';
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen("php://output", "w");

        foreach ($selectedItems as $item) {
            fputcsv($output, is_array($item) ? $item : [$item]);
        }

        fclose($output);
        exit;
    } else {
        $_SESSION["error"] = "Error, no se seleccionaron elementos para exportar.";
        header("Location: ./");
    }