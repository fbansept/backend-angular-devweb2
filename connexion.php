<?php

include('header-init.php');

$json = file_get_contents('php://input');

$utilisateur = json_decode($json);

$requete = $connexion->prepare("SELECT * FROM utilisateur WHERE email = :email AND password = :password");

$requete->bindValue("email", $utilisateur->email);
$requete->bindValue("password", $utilisateur->password);

$requete->execute();

$utilisateurBdd = $requete->fetch();

//si l'utilisateur n'existe pas
if (!$utilisateurBdd) {

    echo '{"message":"Login ou mot de passe incorect"}';
    http_response_code(403);
    exit();
}

function base64UrlEncode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);


$payload = json_encode([
    'id' => $utilisateurBdd['id'],
    'admin' => $utilisateurBdd['admin'],
    'email' => $utilisateurBdd['email'],
]);


// Encoder en Base64 URL-safe
$base64UrlHeader = base64UrlEncode($header);
$base64UrlPayload = base64UrlEncode($payload);

// Créer la signature
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'votre_cle_secrete', true);
$base64UrlSignature = base64UrlEncode($signature);

// Assembler le token
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

echo '{"jwt" : "' . $jwt . '"}';
