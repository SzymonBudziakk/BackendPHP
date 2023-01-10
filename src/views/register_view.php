<!DOCTYPE html>
<html lang="pl">

<?php include "includes/head.inc.php"; ?>

<body>

    <div class="container">
        <?php include "includes/navbar.inc.php"; ?>
        <h1>Rejestracja</h1><br>

        <form method="post" enctype="multipart/form-data">
            <label for="email"> Adres e-mail:</label>
            <input type="text" id="email" name="email" value="" required><br>

            <label for="login"> Login:</label>
            <input type="text" id="login" name="login" value="" required><br>

            <label for="password"> Hasło:</label>
            <input type="password" id="password" name="password" value="" required><br>

            <label for="password2"> Powtórz hasło:</label>
            <input type="password" id="password2" name="password2" value="" required><br><br>

            <input class="button" type="submit" value="Zarejestruj" name="submit"><br><br>

            <?php include "includes/messages.inc.php"; ?>
        </form>

        <a href="/login"><input class="button" type="submit" value="Zaloguj się" name="submit"></a>

    </div>

    <?php include "includes/footer.inc.php"; ?>
</body>

</html>