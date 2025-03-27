<?php
/*
    Model za novico. Vsebuje lastnosti, ki definirajo strukturo novice in sovpadajo s stolpci v bazi.

    V modelu moramo definirati tudi relacije oz. povezane entitete/modele. V primeru novice je to $user, ki 
    povezuje novico z uporabnikom, ki je novico objavil. Relacija nam poskrbi za nalaganje podatkov o uporabniku, 
    da nimamo samo user_id, ampak tudi username, ...
*/


require_once 'users.php'; // Vključimo model za uporabnike

class Article
{
    public $id;
    public $title;
    public $abstract;
    public $text;
    public $date;
    public $user;      // Objekt tipa User (naloženi podatki o uporabniku)
    public $user_id;   // ID uporabnika, ki je novico objavil

    // Konstruktor
    public function __construct($id, $title, $abstract, $text, $date, $user_id)
    {
        $this->id       = $id;
        $this->title    = $title;
        $this->abstract = $abstract;
        $this->text     = $text;
        $this->date     = $date;
        $this->user_id  = $user_id;
        $this->user     = User::find($user_id); // Naložimo podatke o uporabniku
    }

    // Vrne vse novice iz baze
    public static function all()
    {
        $db = Db::getInstance();
        $query = "SELECT * FROM articles;";
        $res = $db->query($query);
        $articles = array();
        while ($article = $res->fetch_object()) {
            array_push($articles, new Article(
                $article->id, 
                $article->title, 
                $article->abstract, 
                $article->text, 
                $article->date, 
                $article->user_id
            ));
        }
        return $articles;
    }

    // Vrne eno novico z določenim ID-jem
    public static function find($id)
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $id);
        $query = "SELECT * FROM articles WHERE id = '$id';";
        $res = $db->query($query);
        if ($article = $res->fetch_object()) {
            return new Article(
                $article->id, 
                $article->title, 
                $article->abstract, 
                $article->text, 
                $article->date, 
                $article->user_id
            );
        }
        return null;
    }

    // Ustvari novo novico in jo vstavi v bazo
    public static function create($title, $abstract, $text, $user_id)
    {
        $db = Db::getInstance();
        // Preprečimo SQL injection in zagotovimo pravilno tipizacijo
        $title    = mysqli_real_escape_string($db, $title);
        $abstract = mysqli_real_escape_string($db, $abstract);
        $text     = mysqli_real_escape_string($db, $text);
        $user_id  = intval($user_id);
    
        $query = "INSERT INTO articles (title, abstract, text, user_id) 
                  VALUES ('$title', '$abstract', '$text', '$user_id')";
    
        return $db->query($query);
    }

    // Vrne vse novice, ki jih je objavil določen uporabnik (upoštevamo sortiranje po datumu)
    public static function findByUser($user_id)
    {
        $db = Db::getInstance();
        $user_id = intval($user_id);
        $query = "SELECT * FROM articles WHERE user_id = '$user_id' ORDER BY date DESC;";
        $res = $db->query($query);
    
        $articles = array();
        while ($article = $res->fetch_object()) {
            array_push($articles, new Article(
                $article->id, 
                $article->title, 
                $article->abstract, 
                $article->text, 
                $article->date, 
                $article->user_id
            ));
        }
        return $articles;
    }

    // Posodobi novico z določenim ID-jem
    public static function update($id, $title, $abstract, $text)
    {
        $db = Db::getInstance();
        $id = intval($id);
        $title    = mysqli_real_escape_string($db, $title);
        $abstract = mysqli_real_escape_string($db, $abstract);
        $text     = mysqli_real_escape_string($db, $text);
    
        $query = "UPDATE articles 
                  SET title = '$title', abstract = '$abstract', text = '$text' 
                  WHERE id = '$id' LIMIT 1;";
    
        return $db->query($query);
    }

    // Izbriše novico z določenim ID-jem
    public static function delete($id)
    {
        $db = Db::getInstance();
        $id = intval($id);
        $query = "DELETE FROM articles WHERE id = '$id' LIMIT 1;";
    
        return $db->query($query);
    }
}
?>