<?php

// Récupérer les données de l'image depuis la requête POST
$data = json_decode(file_get_contents("php://input"));

if ($data && isset($data->image)) {
    // Supprimer le préfixe de l'en-tête de données de l'image (par exemple, "data:image/png;base64,")
    $imageData = str_replace('data:image/png;base64,', '', $data->image);

    // Décoder la chaîne base64 en binaire
    $decodedImageData = base64_decode($imageData);

    // Chemin où sauvegarder l'image sur le serveur
    $filePath = 'uploads/' . uniqid() . '.png';

    // Sauvegarder l'image sur le serveur
    file_put_contents($filePath, $decodedImageData);

    // Répondre avec le chemin de l'image sauvegardée
    echo $filePath;  // Peut-être vous avez un problème ici si vous renvoyez un objet
} else {
    // Répondre avec une erreur si les données ne sont pas valides
    echo json_encode(['success' => false, 'message' => 'Invalid image data']);
}
?>
