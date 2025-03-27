<?php
class comments_controller {

    // Prikaz komentarjev za določeno novico
    public function show() {
        if (!isset($_GET['article_id'])) {
            header("Location: /pages/error");
            exit();
        }

        $article_id = $_GET['article_id'];
        $comments = Comment::findByArticle($article_id);

        require_once('views/comments/show.php'); // Prikažemo komentarje v View-u
    }

    // Dodajanje novega komentarja
    public function store() {
        if (!isset($_SESSION["USER_ID"])) {
            header("Location: /auth/login");
            exit();
        }

        if (!isset($_POST["article_id"]) || empty($_POST["content"])) {
            header("Location: /articles/show?id=" . $_POST["article_id"] . "&error=1");
            exit();
        }

        $user_id = $_SESSION["USER_ID"];
        $article_id = $_POST["article_id"];
        $content = $_POST["content"];

        if (Comment::create($user_id, $article_id, $content)) {
            header("Location: /articles/show?id=" . $article_id);
            exit();
        } else {
            header("Location: /articles/show?id=" . $article_id . "&error=2");
            exit();
        }
    }
}
