<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection</title>
</head>
<body>
    <?php
        try {
            $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'root', 'root');
        }
        catch (Exception $e) {
            die ('Erreur'. $e->getMessage());
        }

        if (isset($_COOKIE['pseudo']) && isset($_COOKIE['pass'])) {
            $req = $bdd->prepare('SELECT id, pass FROM espace_membre WHERE pseudo= :pseudo');
            $req->execute(array(
                'pseudo' => $_COOKIE['pseudo']
            ));
            $pass = $req->fetch();

            if (!$pass) {
                echo 'Mauvais identifiant ou mot de passe !';
            }
            else {
                if ($_COOKIE['pass'] == $pass['pass']) {
                    session_start();
                    $_SESSION['id'] = $pass['id'];
                    $_SESSION['pseudo'] = $_COOKIE['pseudo'];
                    echo 'Vous etes connecter<br>';
                    echo '<a href="page_test.php">Page de test</a>';
                }
                else {
                    echo 'Mauvais identifiant ou mot de passe !<br>';
                    echo $pass['pass']. '<br>';
                    echo $_COOKIE['pass']. '<br>';
                    echo $_COOKIE['pseudo'].'<br>';
                }
            }
        }
        else {

            if (isset($_POST['pseudo']) && isset($_POST['mdp'])) {

                $req = $bdd->prepare('SELECT id, pass FROM espace_membre WHERE pseudo= :pseudo');
                $req->execute(array(
                    'pseudo' => $_POST['pseudo']
                ));
                $pass = $req->fetch();

                $pass_correct = password_verify($_POST['mdp'], $pass['pass']);

                if (!$pass) {
                    echo 'Mauvais identifiant ou mot de passe !';
                }
                else {
                    if ($pass_correct) {
                        session_start();
                        $_SESSION['id'] = $pass['id'];
                        $_SESSION['pseudo'] = $_POST['pseudo'];
                        echo 'Vous etes connecter<br>';
                        echo '<a href="page_test.php">Page de test</a>';
                        if (isset($_POST['connection_auto'])) {
                            setcookie('pseudo',  $_POST['pseudo'], time() + 365*24*3600, null, null, false, true);
                            setcookie('pass', $pass['pass'], time() + 365*24*3600, null, null, false, true);
                        }
                    }
                    else {
                        echo 'Mauvais identifiant ou mot de passe !';
                    }
                }
            }
            else {
                ?>

                <form method="post">
                    <label for="pseudo">Pseudo :</label>
                    <input type="text" name="pseudo" id="pseudo"><br>
                    <label for="mdp">Mot de passe :</label>
                    <input type="password" name="mdp" id="mdp"><br>
                    <label for="connection_auto">Connection automatique :</label>
                    <input type="checkbox" name="connection_auto" id="connection_auto"><br>
                    <input type="submit" value="Se connecter">
                </form>
                <?php
            }

        }
        ?>
    
</body>
</html>