        <?php

        include('header-init.php');

        try {

            // Prend les données brutes de la requête
            //$json = file_get_contents('php://input');

            $json = $_POST['article'];

            // Le convertit en objet PHP
            $article = json_decode($json);


            if (!isset($_GET['id'])) {
                echo '{"message" : "L\'URL doit contenir l\id de l\article"}';
                http_response_code(400);
                exit;
            }

            $idArticle = $_GET['id'];

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
                "UPDATE article SET nom = :nom, description = :description, prix = :prix, image = :image WHERE id = :id"
            );

            $requete->bindValue('nom', $article->nom);
            $requete->bindValue('description', $article->description);
            $requete->bindValue('prix', $article->prix);
            $requete->bindValue('image', $nouveauNomFichier);
            $requete->bindValue('id', $idArticle);

            $requete->execute();

            echo '{"message" : "Article modifié"}';
            http_response_code(200);
        } catch (Exception $erreur) {
            echo '{"message" : "' . $erreur->getMessage() . '" }';
            http_response_code(500);
        }
