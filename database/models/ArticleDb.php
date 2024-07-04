<?php

$pdo = require_once __DIR__.'/../database.php';

class ArticleDB {

    private  $statementReadAllArticles;
    private  $statementReadArticle;
    private  $statementUpdateArticle;
    private  $statementCreateArticle; 
    private  $statementDeleteArticle;

    function __construct(private PDO $pdo)
    {

        $this->statementReadAllArticles = $pdo->prepare('SELECT * FROM article');

        $this->statementReadArticle = $pdo->prepare('SELECT * FROM article WHERE id=:id');

        $this->statementUpdateArticle = $pdo->prepare('UPDATE article SET 
        title=:title,
        category=:category,
        content=:content,
        image=:image
        WHERE id=:id;'
        );

        $this->statementCreateArticle = $pdo->prepare('INSERT INTO article (
            title,
            category,
            content,
            image)
            VALUES (
            :title,
            :category,
            :content,
            :image)');

        $this->statementDeleteArticle = $pdo->prepare('DELETE FROM article WHERE id=:id');
    }

   
    


    public function fetchAllArticles() {
        $this->statementReadAllArticles->execute();
        return $this->statementReadAllArticles->fetchAll();
        
    }

    public function fetchOneArticle(int $id) {

        $this->statementReadArticle->bindValue(':id', $id);
        $this->statementReadArticle->execute();
        return $this->statementReadArticle->fetch();
    }

    public function createArticle($article) {
        $this->statementCreateArticle->bindValue(':title', $article['title']);
        $this->statementCreateArticle->bindValue(':category', $article['category']);
        $this->statementCreateArticle->bindValue(':content', $article['content']);
        $this->statementCreateArticle->bindValue(':image', $article['image']);  
        $this->statementCreateArticle->execute();
        return $this->fetchOneArticle($this->pdo->lastInsertId());
    }

    public function updateArticle($article) {
        $this->statementUpdateArticle->bindValue(':title',$article['title']);
        $this->statementUpdateArticle->bindValue(':content', $article['content']);
        $this->statementUpdateArticle->bindValue(':category', $article['category']);
        $this->statementUpdateArticle->bindValue(':image', $article['image']);
        $this->statementUpdateArticle->bindValue(':id', $article['id']);
        $this->statementUpdateArticle->execute();
        return $article;
    }

    public function deleteArticle(int $id) {
        $this->statementDeleteArticle->bindValue(':id', $id);
        $this->statementDeleteArticle->execute();
        return $id;
    }
   
}

return new ArticleDB($pdo);