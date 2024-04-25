<?php

include('header-init.php');

$requete = $connexion->query('SELECT * FROM article');

$articles = $requete->fetchAll();

echo json_encode($articles);
