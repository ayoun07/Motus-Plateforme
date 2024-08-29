<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Motus - Connexion et Inscription</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="form-container sign-up-container">
            <form id="signupForm">
                <h1>Créer un compte</h1>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Mot de passe" required />
                <button type="submit">S'inscrire</button>
                <p id="signupError"></p>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form id="loginForm">
                <h1>Connexion</h1>
                <input type="text" name="username" placeholder="Nom d'utilisateur" required />
                <input type="password" name="password" placeholder="Mot de passe" required />
                <button type="submit">Se connecter</button>
                <p id="loginError"></p>
            </form>
        </div>
    </div>
    <script src="scripts.js"></script>
</body>

</html>