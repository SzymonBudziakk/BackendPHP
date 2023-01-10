<!DOCTYPE html>
<html lang="pl">

<?php include "includes/head.inc.php"; ?>

<body>

    <div class="container">
        <?php include "includes/navbar.inc.php"; ?>
        
        <h1>Dodawanie zdjęcia</h1>
        
        <form method="post" enctype="multipart/form-data">
            <label for="forUpload">Wybierz zdjęcie:</label>
            <input type="file" name="forUpload" id="forUpload" required><br>

            <label for="author">Autor:</label>
            <input type="text" id="author" name="author" value="<?= $author ?>" required><br>

            <label for="title">Tytuł:</label>
            <input type="text" id="title" name="title" value="" required><br>

            <label for="watermark">Watermark:</label>
            <input type="text" id="watermark" name="watermark" value="" required><br>

            <input class="button" type="submit" value="Wyślij zdjęcie" name="submit">
            <br><br>

            <?php include "includes/messages.inc.php"; ?>
        </form>

        <a href="/gallery"><input class="button" type="submit" value="Galeria"></a>
    
    </div>
    

    <?php include "includes/footer.inc.php"; ?>
</body>

</html>