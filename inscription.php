<?php

include('header-init.php');

$json = file_get_contents('php://input');

$utilisateur = json_decode($json);

$passwordHash = password_hash($utilisateur->password, PASSWORD_DEFAULT);

$requete = $connexion->prepare("INSERT INTO utilisateur (email, password) VALUES (:email, :password)");

$requete->bindValue("email", $utilisateur->email);
$requete->bindValue("password", $passwordHash);

$requete->execute();

echo '{"message" : "inscription réussie"}';
