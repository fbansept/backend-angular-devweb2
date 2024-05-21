        <?php

        include('header-init.php');

        include('extraction-jwt.php');

        if (!$utilisateur->admin) {

            echo '{"message" : "vous n\'etes pas administrateur"}';
            http_response_code(403);
            exit;
        }

        try {

            // Prend les données brutes de la requête
            //$json = file_get_contents('php://input');

            $json = $_POST['article'];

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

            $nouveauNomFichier = '';


            if (isset($_FILES['image'])) {
                $image = $_FILES['image'];

                $nomFichier = $image['name'];

                $extension = strtolower(pathinfo($nomFichier, PATHINFO_EXTENSION));

                if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    echo '{"message" : "L\'extension du fichier doit être jpg, jpeg, ou png"}';
                    http_response_code(400);
                    exit;
                }

                $nouveauNomFichier = date('Y-m-d-H-i-s') . '-' . $nomFichier;

                move_uploaded_file($image['tmp_name'], 'uploads/' . $nouveauNomFichier);
            }


            $requete = $connexion->prepare(
                "INSERT INTO article (nom, description, prix, image) VALUES (:nom, :description, :prix, :image)"
            );

            $requete->bindValue('nom', $article->nom);
            $requete->bindValue('description', $article->description);
            $requete->bindValue('prix', $article->prix);
            $requete->bindValue('image', $nouveauNomFichier);

            $requete->execute();



            echo '{"message" : "Article ajouté"}';
            http_response_code(201);
        } catch (Exception $erreur) {
            echo '{"message" : "' . $erreur->getMessage() . '" }';
            http_response_code(500);
        }
