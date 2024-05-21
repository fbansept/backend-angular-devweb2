<?php

include('header-init.php');

include('extraction-jwt.php');

$requete = $connexion->query('SELECT * FROM article');

$articles = $requete->fetchAll();

echo json_encode($articles);
