<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Page d'inscription</title>
</head>

<body>

    <?php
    if (isset($_POST['pseudo']) && isset($_POST['password']) && isset($_POST['password']) && isset($_POST['password2']) && isset($_POST['email'])) {
        if ($_POST['password'] == $_POST['password2']) {

            $_POST['email'] = htmlspecialchars($_POST['email']);
            if (preg_match('#^[a-z0-9-_.]+@[a-z0-9-_.]{2,}\.[a-z]{2,4}$#', $_POST['email'])) {

                try {
                    $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', 'root');
                }
                catch (Exception $e) {
                    die ('Erreur' . $e->getMessage());
                }
                
                $pseudo_valide = $bdd->query('SELECT pseudo FROM espace_membre WHERE pseudo="'.$_POST['pseudo'] .'"');
                $pseudo = $pseudo_valide->fetch();

                if (isset($pseudo['pseudo'])) {
                    
                    if ($_POST['pseudo'] == $pseudo['pseudo']) {
                        echo 'le pseudo '.$_POST['pseudo'].' est deja pris';
                    }
                    $pseudo_valide->closeCursor();
                }
                else {
                    $mdp_crypt = password_hash($_POST['password'], PASSWORD_DEFAULT);

                    $insert = $bdd->prepare('INSERT INTO espace_membre (pseudo, pass, email, date_inscription) VALUES(:pseudo, :pass, :email, CURDATE())');
                    $insert->execute(array(
                        'pseudo' => $_POST['pseudo'],
                        'pass' => $mdp_crypt,
                        'email' => $_POST['email']
                    ));
                    echo 'votre compte as été créer avec succes';
                }
                
            }
            else {
                echo 'ADRESSE E-MAIL INCORRECTE.';
            }

        }
        else {
            echo 'VOS MOTS DE PASSES NE CORRESPONDENT PAS.';
        }
    }
    else {
        echo 'Remplissez tout les champs';
    }
    ?>
    <form method="post">
        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" id="pseudo"><br>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password"><br>
        <label for="password">Retapez votre mot de passe :</label>
        <input type="password" name="password2" id="password2"><br>
        <label for="email">Adresse e-mail :</label>
        <input type="email" name="email" id="email"><br>
        <input type="submit" value="Valider">
    </form>
    <p>Vous avez deja un compte ? <a href="connection.php">Connecter</a> vous</p>

    
</body>

</html>