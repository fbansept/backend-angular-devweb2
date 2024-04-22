<?php

header('Access-Control-Allow-Origin: *');

$connexion = new PDO('mysql:host=localhost;dbname=angular_devweb2', 'root', '');

$requete = $connexion->query('SELECT * FROM article');

$articles = $requete->fetchAll();


echo json_encode($articles);
