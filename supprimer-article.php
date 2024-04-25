<?php

include('header-init.php');

$idArticleAsupprimer = $_GET['id'];

$requete = $connexion->prepare("DELETE FROM article WHERE id = :id");

$requete->bindValue('id', $idArticleAsupprimer);

$requete->execute(); //<----

echo '{"message" : "L\'article a bien été supprimé"}';
