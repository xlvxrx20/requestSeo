<?php
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["search"])) {
        // Guardamos la busqueda en una variable de sesión
        $_SESSION["search"] = $_POST["search"];

        // Pasos previos a la conexión con la API
        $search = $_POST["search"];
        $apikey = $_SESSION["apiKey"];
        $url = "https://api.openai.com/v1/chat/completions";
        $numberOfSearchs = 25;
        $addedTextToPrompt = "Básate en su cantidad de búsquedas de google ya que quiero estos resultados para posicionar mi web con SEO y al lado un true o false dependiendo si la búsqueda conlleva compra (true) o es informativa (false), es muy importante que no me des explicaciones ni texto adicional, tampoco me des el texto formateado en markdown porque si no no puedo tratar con los datos. Solamente vas a darme el array en formato JSON.";

        if (isset($_POST["numberOfSearchs"])) {
            $numberOfSearchs = $_POST["numberOfSearchs"];
            if($numberOfSearchs < 5) { $numberOfSearchs = 5; }
            if($numberOfSearchs > 50) { $numberOfSearchs = 50; }
        }

        $promt = null;

        if (isset($_SESSION["recommendedResults"])) {
            $markedSearches = null;
            $notMarkedSearches = null;

            if(isset($_POST["markedSearches"]) && isset($_POST["notMarkedSearches"])) {
                $markedSearches = $_POST["markedSearches"];
                $notMarkedSearches = $_POST["notMarkedSearches"];
            } else {
                $_SESSION["error"] = "Error en el script de envio del formulario";
                header("Location: ./");
            }

            if($_POST["markedSearches"] !== null && $_POST["notMarkedSearches"] !== null) {
                $prompt = "Devuélveme únicamente un array bidimensional en JSON con $numberOfSearchs búsquedas similares principalmente a \"$search\" y a esta lista, la cual quiero que mantengas al principio del array: ($markedSearches), también procura evitar búsquedas como las de esta lista: ($notMarkedSearches).";
            } else {
                if($_POST["markedSearches"] !== null) {
                    $prompt = "Devuélveme únicamente un array bidimensional en JSON con $numberOfSearchs búsquedas similares principalmente a \"$search\" y a esta lista, la cual quiero que mantengas al principio del array: ($markedSearches).";
                } else {
                    $prompt = "Devuélveme únicamente un array bidimensional en JSON con $numberOfSearchs búsquedas similares principalmente a \"$search\", también procura evitar búsquedas como las de esta lista: ($notMarkedSearches).";
                }
            }

            unset($_SESSION["recommendedResults"]);
        } else {
            $prompt = "Devuélveme únicamente un array bidimensional en JSON con $numberOfSearchs búsquedas similares a \"$search\"." ;
        }

        // Cuerpo de la petición
        $requestData = [
            "model" => "gpt-4o",
            "messages" => [
                [
                    "role" => "user",
                    "content" => ($prompt . $addedTextToPrompt)
                ]
            ]
        ];

        $json = json_encode($requestData); // Se transforma a JSON para la API

        // Inicializar y configurar opciones del cURL
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Authorization: Bearer $apikey"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

        // Ejecutar la solicitud
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        // Ya con la respuesta dada cerramos el cURL
        curl_close($ch);

        // Manejo de la respuesta
        if ($httpCode === 200) {
            $data = json_decode($response, true); // Decodificamos el JSON a un array asociativo (o array de arrays para entenderlo)
            $content = $data['choices'][0]['message']['content']; // Accedemos al texto del contenido generado (pedido como un json para tratar los datos)
            $convertedContent = preg_replace('/```json|```/', '', $content); // Tratado por si ChatGPT da mal el texto (formateado en markdown)
            $arrayOfTheContent = json_decode($convertedContent, true); // Convertimos el texto generado de json a un array

            if (is_array($arrayOfTheContent)) {
                $_SESSION["recommendedResults"] = $arrayOfTheContent;
                header("Location: ./result.php");
            } else {
                $_SESSION["error"] = "Error al interpretar la respuesta, vuelva a probar.";
                header("Location: ./");
            }
        } else {
            $_SESSION["error"] = "<strong>Error $httpCode</strong>: $response";
            header("Location: ./");
        }
    } else {
        header("Location: ./");
    }