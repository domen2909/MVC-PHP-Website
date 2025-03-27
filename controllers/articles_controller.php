<?php
/*
    Controller za novice. Vključuje naslednje standardne akcije:
        index: izpiše vse novice
        show: izpiše posamezno novico
        
    TODO:
        list: izpiše novice prijavljenega uporabnika
        create: izpiše obrazec za vstavljanje novice
        store: vstavi novico v bazo
        edit: izpiše vmesnik za urejanje novice
        update: posodobi novico v bazi
        delete: izbriše novico iz baze
*/

class articles_controller
{
    public function index()
    {
        //s pomočjo statične metode modela, dobimo seznam vseh novic
        //$ads bo na voljo v pogledu za vse oglase index.php
        $articles = Article::all();

        //pogled bo oblikoval seznam vseh oglasov v html kodo
        require_once('views/articles/index.php');
    }

    public function show()
    {
        //preverimo, če je uporabnik podal informacijo, o oglasu, ki ga želi pogledati
        if (!isset($_GET['id'])) {
            return call('pages', 'error'); //če ne, kličemo akcijo napaka na kontrolerju stran
            //retun smo nastavil za to, da se izvajanje kode v tej akciji ne nadaljuje
        }
        //drugače najdemo oglas in ga prikažemo
        $article = Article::find($_GET['id']);
        require_once('views/articles/show.php');
    }

    public function create(){
        // Preverimo, ali je uporabnik prijavljen
        if(!isset($_SESSION["USER_ID"])){
            header("Location: /auth/login"); // Preusmerimo na prijavo
            exit();
        }

        require_once('views/articles/create.php');
    }

    public function store() {
        // Preverimo, ali je uporabnik prijavljen
        if (!isset($_SESSION["USER_ID"])) {
            header("Location: /auth/login");
            exit();
        }
    
        // Preverimo, ali so vsi podatki izpolnjeni
        if (empty($_POST["title"]) || empty($_POST["abstract"]) || empty($_POST["text"])) {
            header("Location: /articles/create?error=1");
            exit();
        }
    
        // Shrani novico v bazo
        $user_id = $_SESSION["USER_ID"];
        if (Article::create($_POST["title"], $_POST["abstract"], $_POST["text"], $user_id)) {
            header("Location: /articles/index"); // Preusmeri na seznam novic
            exit();
        } else {
            header("Location: /articles/create?error=2"); // Napaka pri shranjevanju
            exit();
        }
    }

    public function my_articles() {
        // Preverimo, ali je uporabnik prijavljen
        if (!isset($_SESSION["USER_ID"])) {
            header("Location: /auth/login");
            exit();
        }
    
        // Dobimo novice samo od prijavljenega uporabnika
        $user_id = $_SESSION["USER_ID"];
        $articles = Article::findByUser($user_id);
    
        // Prikažemo pogled my_articles.php in mu predamo podatke
        require_once('views/articles/my_articles.php');
    }

    public function edit() {
        // Preverimo, ali je uporabnik prijavljen
        if (!isset($_SESSION["USER_ID"])) {
            header("Location: /auth/login");
            exit();
        }
    
        // Preverimo, ali je podan ID novice
        if (!isset($_GET['id'])) {
            header("Location: /pages/error");
            exit();
        }
    
        $article_id = intval($_GET['id']);
        $article = Article::find($article_id);
    
        // Preverimo, ali novica obstaja in ali pripada trenutnemu uporabniku
        if (!$article || $article->user->id !== $_SESSION["USER_ID"]) {
            header("Location: /pages/error");
            exit();
        }
    
        // Prikažemo obrazec za urejanje novice
        require_once('views/articles/edit.php');
    }

    public function update() {
        // Preverimo, ali je uporabnik prijavljen
        if (!isset($_SESSION["USER_ID"])) {
            header("Location: /auth/login");
            exit();
        }
    
        // Preverimo, ali so vsi podatki poslani
        if (empty($_POST["id"]) || empty($_POST["title"]) || empty($_POST["abstract"]) || empty($_POST["text"])) {
            header("Location: /articles/edit?id=" . $_POST["id"] . "&error=1");
            exit();
        }
    
        $article_id = intval($_POST["id"]);
        $article = Article::find($article_id);
    
        // Preverimo, ali novica obstaja in pripada trenutnemu uporabniku
        if (!$article || $article->user->id !== $_SESSION["USER_ID"]) {
            header("Location: /pages/error");
            exit();
        }
    
        // Posodobimo novico v bazi
        if (Article::update($article_id, $_POST["title"], $_POST["abstract"], $_POST["text"])) {
            header("Location: /articles/my_articles");
            exit();
        } else {
            header("Location: /articles/edit?id=" . $article_id . "&error=2");
            exit();
        }
    }

    public function delete() {
        // Preverimo, ali je uporabnik prijavljen
        if (!isset($_SESSION["USER_ID"])) {
            header("Location: /auth/login");
            exit();
        }
    
        // Preverimo, ali je podan ID novice
        if (!isset($_GET['id'])) {
            header("Location: /pages/error");
            exit();
        }
    
        $article_id = intval($_GET['id']);
        $article = Article::find($article_id);
    
        // Preverimo, ali novica obstaja in pripada trenutnemu uporabniku
        if (!$article || $article->user->id !== $_SESSION["USER_ID"]) {
            header("Location: /pages/error");
            exit();
        }
    
        // Izbrišemo novico iz baze
        if (Article::delete($article_id)) {
            header("Location: /articles/my_articles");
            exit();
        } else {
            header("Location: /pages/error");
            exit();
        }
    } 
}