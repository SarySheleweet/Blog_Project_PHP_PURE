<?php

$pdo = require_once 'database.php';
$statementReadArticle = $pdo->prepare('SELECT * FROM article WHERE id=:id');
$statementCreateArticle = $pdo->prepare('INSERT INTO article (
title,
category,
content,
image)
VALUES (
:title,
:category,
:content,
:image)');
$statementUpdateArticle = $pdo->prepare('UPDATE article SET 
title=:title,
category=:category,
content=:content,
image=:image
WHERE id=:id;'
);


$category = '';
$errors = [
  'title' => '',
  'image' => '',
  'category' => '',
  'content' => ''
];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';

if($id) {
  $statementReadArticle->bindValue(':id', $id);
  $statementReadArticle->execute();
  $article = $statementReadArticle->fetch();
  $title = $article['title'];
  $image = $article['image'];
  $category = $article['category'];
  $content = $article['content'];
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  require_once './utils/cleaningAndValidation.php';

  if (empty(array_filter($errors, fn ($e) => $e !== ''))) {
    if($id) {
        $statementUpdateArticle->bindValue(':title', $title);
        $statementUpdateArticle->bindValue(':content', $content);
        $statementUpdateArticle->bindValue(':category', $category);
        $statementUpdateArticle->bindValue(':image', $image);
        $statementUpdateArticle->bindValue(':id', $id);
        $statementUpdateArticle->execute();
    } else {
    $statementCreateArticle->bindValue(':title', $title);
    $statementCreateArticle->bindValue(':category', $category);
    $statementCreateArticle->bindValue(':content', $content);
    $statementCreateArticle->bindValue(':image', $image);  
    $statementCreateArticle->execute();
    }
    header('Location: /');
  }
}

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php require_once 'includes/head.php' ?>
        <link rel="stylesheet" href="/public/css/form-article.css">
        <title><?= $id ? 'Modifier' : 'Créer' ?> un article</title>
    </head>

    <body>
        <div class="container">
            <?php require_once 'includes/header.php' ?>
            <div class="content">
                <div class="block p-20 form-container">
                    <h1><?= $id ? 'Modifier' : 'Ecrire' ?> un article</h1>
                    <form action="/form-article.php<?= $id ? "?id=$id" : '' ?>" method="post">
                        <div class="form-control">
                            <label for="title">Titre</label>
                            <input type="text" name="title" id="title" value="<?= $title ?? '' ?>">
                            <?php if ($errors['title']) : ?>
                                <p class="text-danger"><?= $errors['title'] ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-control">
                            <label for="image">Image</label>
                            <input type="text" name="image" id="image" value="<?= $image ?? '' ?>">
                            <?php if ($errors['image']) : ?>
                                <p class="text-danger"><?= $errors['image'] ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-control">
                            <label for="category">Catégorie</label>
                            <select name="category" id="category">
                                <?php require_once './utils/generateCategories.php' ?>
                            </select>
                            <?php if ($errors['category']) : ?>
                                <p class="text-danger"><?= $errors['category'] ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-control">
                            <label for="content">Contenu</label>
                            <textarea name="content" id="content"><?= $content ?? ''?></textarea>
                            <?php if ($errors['content']) : ?>
                                <p class="text-danger"><?= $errors['content'] ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-actions">
                            <button class="btn btn-secondary" type="button">Annuler</button>
                            <button action="../utils/cleaning-validation" class="btn btn-primary" type="submit"><?= $id ? 'Modifier' : 'Sauvegarder'?></button>
                        </div>
                    </form>
                </div>
            </div>
            <?php require_once 'includes/footer.php' ?>
        </div>

    </body>
</html>