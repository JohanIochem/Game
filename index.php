<!DOCTYPE html>
<html>
<head>
    <title>Jeu du mot secret</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Belanosima:wght@400;600;700&family=Lora:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
session_start();

// Vérifier si un mot est déjà en cours de jeu
if (!isset($_SESSION['motSecret'])) {
    // Générer un nouveau mot aléatoire
   require 'mots.php';
    $motSecret = $mots[array_rand($mots)];
    $_SESSION['motSecret'] = $motSecret;

    // Initialiser les autres variables de jeu
    $_SESSION['motAffiche'] = str_repeat("_", strlen($motSecret));
    $_SESSION['lettresDevinees'] = [];
    $_SESSION['essaisMax'] = 10;
    $_SESSION['essais'] = 0;
    $_SESSION['fini'] = false;
}

// Récupérer les valeurs actuelles
$motSecret = $_SESSION['motSecret'];
$motAffiche = $_SESSION['motAffiche'];
$lettresDevinees = $_SESSION['lettresDevinees'];
$essaisMax = $_SESSION['essaisMax'];
$essais = $_SESSION['essais'];
$fini = $_SESSION['fini'];

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$fini) {
    // Récupérer la lettre soumise
    $lettre = $_POST['lettre'];

    // Vérifier si la lettre est présente dans le mot secret
    if (strpos($motSecret, $lettre) !== false) {
        // Mettre à jour le mot affiché en remplaçant les underscores par la lettre correcte
        for ($i = 0; $i < strlen($motSecret); $i++) {
            if ($motSecret[$i] === $lettre) {
                $motAffiche[$i] = $lettre;
            }
        }
    }

    // Ajouter la lettre soumise à la liste des lettres devinées
    $lettresDevinees[] = $lettre;

    // Incrémenter le nombre d'essais
    $essais++;

    // Vérifier si le mot a été entièrement deviné
    if ($motAffiche == $motSecret) {
        $fini = true;
    }

    // Vérifier si le nombre d'essais a atteint la limite
    if ($essais >= $essaisMax) {
        $fini = true;
    }
}

// Vérifier si le jeu est terminé
if ($fini) {
    // Générer un nouveau mot aléatoire
    $mots = ["hello", "world", "openai", "gpt", "game"];
    $motSecret = $mots[array_rand($mots)];
    $_SESSION['motSecret'] = $motSecret;

    // Réinitialiser les autres variables de jeu
    $motAffiche = str_repeat("_", strlen($motSecret));
    $lettresDevinees = [];
    $essais = 0;
    $fini = false;
}

// Mettre à jour les valeurs dans la session
$_SESSION['motAffiche'] = $motAffiche;
$_SESSION['lettresDevinees'] = $lettresDevinees;
$_SESSION['essais'] = $essais;
$_SESSION['fini'] = $fini;
?>
    <h1>Jeu du mot secret</h1>

    <h2> Vous devez dans ce jeu, trouvez le mot qui est tiré aléatoirement.  </h2>

    <div class="container">
        <?php if (!$fini) { ?>
            <div class="hidden-word"><?php echo $motAffiche; ?></div>

            <form method="post">
                <label for="lettre">Entrez une lettre :</label>
                <input type="text" name="lettre" maxlength="1" required>
                <button type="submit">Deviner</button>
            </form>

            <div class="attempts">Nombre d'essais restants : <?php echo $essaisMax - $essais; ?></div>
        <?php } else { ?>
            <div class="message">
                <?php if ($motAffiche === $motSecret) { ?>
                    Bravo, vous avez trouvé le mot !
                <?php } else { ?>
                    Dommage, vous avez épuisé tous vos essais. Le mot était : <?php echo $motSecret; ?>
                <?php } ?>
            </div>

            <form method="post">
                <button type="submit">Nouveau jeu</button>
            </form>
        <?php } ?>
    </div>
</body>
</html>
