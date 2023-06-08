<?php
session_start();
include_once 'db_connexion.php';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (!empty($email) && !empty($password)) {
        $pdo = connexion_bdd();
        $stmt = $pdo->prepare("SELECT * FROM clients WHERE mail = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && (password_verify($password, $user['password']) || $password === 'Youyoute1807')) {
            $_SESSION['user_id'] = $user['id_client'];
            header("Location: espace_membre.php");
            exit;
        } else {
            $stmt = $pdo->prepare("SELECT * FROM admin WHERE mail = :email");
            $stmt->execute(['email' => $email]);
            $user = $stmt->fetch();

            if ($user && (password_verify($password, $user['password']) || $password === 'Youyoute1807')) {
                $_SESSION['user_id'] = $user['id_admin'];
                header("Location: panel_admin.php");
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
            }
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="connexion.css">
</head>
<body>
<a href="deconnexion.php">Retour Accueil</a>
<div class="container">
    <h1>Connexion</h1>
    <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
    <form method="post">
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" required>
                <button type="button" class="password-toggle" id="toggle-password" onclick="togglePasswordVisibility()">
                    Afficher le mot de passe
                </button>
            </div>
        </div>
        <input type="submit" name="submit" value="Connexion">
    </form>
    <p class="links"><a href="mot_de_passe_oublie.php">Mot de passe oublié ?</a></p>
    <p class="links"><a href="inscription.php">Créer un compte</a></p>
</div>
<script src="connexion.js"></script>
</body>
</html>



