<?php

include('header-init.php');

if(!isset($_GET['id'])) {
    http_response_code(400);
    echo '{"message" : "il manque l\'identifiant dans l\'url"}';
    exit();
}

$idArticle = $_GET['id'];

$requete = $connexion->prepare('SELECT * FROM article WHERE id = :id');

$requete->bindValue('id', $idArticle);

$requete->execute();

$article = $requete->fetch();

if (!$article) {
    http_response_code(404);
    echo '{"message" : "article introuvable"}';
    exit();
}

echo json_encode($article);
