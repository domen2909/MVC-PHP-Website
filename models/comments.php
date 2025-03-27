<?php
class Comment {
    public $id;
    public $user_id;
    public $article_id;
    public $content;
    public $created_at;

    // Konstruktor
    public function __construct($id, $user_id, $article_id, $content, $created_at) {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->article_id = $article_id;
        $this->content = $content;
        $this->created_at = $created_at;
    }

    // Metoda za pridobivanje vseh komentarjev za določen članek
    public static function findByArticle($article_id) {
        $db = Db::getInstance();
        $article_id = intval($article_id);

        $query = "SELECT * FROM comments WHERE article_id = '$article_id' ORDER BY created_at ASC;";
        $res = $db->query($query);

        $comments = [];
        while ($comment = $res->fetch_object()) {
            $comments[] = new Comment($comment->id, $comment->user_id, $comment->article_id, $comment->content, $comment->created_at);
        }

        return $comments;
    }

    // Metoda za dodajanje komentarja v bazo
    public static function create($user_id, $article_id, $content) {
        $db = Db::getInstance();
        $user_id = intval($user_id);
        $article_id = intval($article_id);
        $content = mysqli_real_escape_string($db, $content);

        $query = "INSERT INTO comments (user_id, article_id, content) VALUES ('$user_id', '$article_id', '$content');";
        return $db->query($query);
    }

     // NOVO: Metoda za štetje komentarjev določenega uporabnika
     public static function countByUser($user_id) {
        $db = Db::getInstance();
        $user_id = intval($user_id);
        $query = "SELECT COUNT(*) AS count FROM comments WHERE user_id = '$user_id';";
        $res = $db->query($query);
        if ($row = $res->fetch_object()) {
            return $row->count;
        }
        return 0;
    }
}