<!DOCTYPE html>
<html lang="pl">

<?php include "includes/head.inc.php"; ?>

<body>
  <div class="container">
    <?php include "includes/navbar.inc.php"; ?>

    <h1>Zapisane zdjęcia</h1><br>
    
    <form action="/collection/del" method="post" enctype="multipart/form-data">
      <?php foreach ($images as $image) { ?>
        <div>
          <div>
            <a target="_blank" href="/images/watermark/<?= $image['fileName'] ?>">
              <img src="/images/miniature/<?= $image['fileName'] ?>" alt="<?= $image['title'] ?>">
            </a>
            <div>
              <em>Nazwa:</em> <?= $image['fileName'] ?> <br>
              <em>Tytuł:</em> <?= $image['title'] ?> <br>
              <em>Autor:</em> <?= $image['author'] ?> <br>
              <em>Id:</em> <?= $image['ImageId'] ?> <br>
              <em>Oznacz: </em> <input type="checkbox" name="checkbox_<?= $image['ImageId'] ?>">
            </div>
          </div>
        </div>
      <?php }  ?>

      <input class="button" type="submit" value="Usuń z zapisanych">
    </form>

    <br>
    <?php for ($page = 1; $page <= $pages; $page++) { ?>
        <span class="page_link"><a href="/collection?page=<?= $page ?>"><?= $page ?></a></span>
    <?php }  ?>
    <br><br>

    <a href="/gallery"><input class="button" type="submit" value="Galeria"></a>
  </div>

  <?php include "includes/footer.inc.php"; ?>
</body>

</html>