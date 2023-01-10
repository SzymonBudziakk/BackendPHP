<!DOCTYPE html>
<html lang="pl">

<?php include "includes/head.inc.php"; ?>

<body>
    <div class="container">
        <?php include "includes/navbar.inc.php"; ?>
    
        <h1>Zalogowano</h1><br>

        <h2>Dane użytkownika</h2><br>

        <b>Email:</b> <?= $user['email'] ?> <br>
        <b>Login:</b> <?= $user['login'] ?> <br><br>

        <a href="/logout"><input class="button" type="submit" value="Wyloguj się" name="submit"></a>

    </div>
    
    <?php include "includes/footer.inc.php"; ?>
</body>

</html>