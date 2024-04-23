        <?php

        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, OPTIONS");

        // Répondre immédiatement aux requêtes OPTIONS
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            // Aucun contenu n'est nécessaire, juste une réponse 204 (No Content)
            header("HTTP/1.1 204 No Content");
            exit;
        }

        try {

            // Prend les données brutes de la requête
            $json = file_get_contents('php://input');

            // Le convertit en objet PHP
            $article = json_decode($json);


            if (
                strlen($article->nom) < 3
            ) {
                echo '{"message" : "Le nom doit avoir au moins 3 caractères"}';
                http_response_code(400);
                exit;
            }

            if (
                strlen($article->nom) > 100
            ) {
                echo '{"message" : "Le nom doit avoir maximum 100 caractères"}';
                http_response_code(400);
                exit;
            }

            if (
                $article->prix <= 0
            ) {
                echo '{"message" : "Le prix doit être positif"}';
                http_response_code(400);
                exit;
            }

            $connexion = new PDO('mysql:host=localhost;dbname=angular_devweb2', 'root', '');

            $requete = $connexion->prepare(
                "INSERT INTO article (nom, description, prix, image) VALUES (:nom, :description, :prix, '')"
            );

            $requete->bindValue('nom', $article->nom);
            $requete->bindValue('description', $article->description);
            $requete->bindValue('prix', $article->prix);

            $requete->execute();

            echo '{"message" : "Article ajouté"}';
            http_response_code(201);
        } catch (Exception $erreur) {
            echo '{"message" : "' . $erreur->getMessage() . '" }';
            http_response_code(500);
        }
