<?php
/*
    Controller za uporabnike. Vključuje naslednje standardne akcije:
        create: izpiše obrazec za registracijo
        store: obdela podatke iz obrazca za registracijo in ustvari uporabnika v bazi
        edit: izpiše obrazec za urejanje profila
        update: obdela podatke iz obrazca za urejanje profila in jih shrani v bazo
*/

class users_controller
{
    function create(){
        $error = "";
        if(isset($_GET["error"])){
            switch($_GET["error"]){
                case 1: $error = "Izpolnite vse podatke"; break;
                case 2: $error = "Gesli se ne ujemata."; break;
                case 3: $error = "Uporabniško ime je že zasedeno."; break;
                default: $error = "Prišlo je do napake med registracijo uporabnika.";
            }
        }
        require_once('views/users/create.php');
    }
    
    function store(){
        //Preveri če so vsi podatki izpolnjeni
        if(empty($_POST["username"]) || empty($_POST["email"]) || empty($_POST["password"])){
            header("Location: /users/create?error=1"); 
        }
        //Preveri če se gesli ujemata
        else if($_POST["password"] != $_POST["repeat_password"]){
            header("Location: /users/create?error=2"); 
        }
        //Preveri ali uporabniško ime obstaja
        else if(User::is_available($_POST["username"])){
            header("Location: /users/create?error=3"); 
        }
        //Podatki so pravilno izpolnjeni, registriraj uporabnika
        else if(User::create($_POST["username"], $_POST["email"], $_POST["password"])){
            header("Location: /auth/login");
        }
        //Prišlo je do napake pri registraciji
        else{
            header("Location: /users/create?error=4"); 
        }
        die();
    }

    function edit(){
        if(!isset($_SESSION["USER_ID"])){
            header("Location: /pages/error");
            die();
        }
        $user = User::find($_SESSION["USER_ID"]);
        $error = "";
        if(isset($_GET["error"])){
            switch($_GET["error"]){
                case 1: $error = "Izpolnite vse podatke"; break;
                case 2: $error = "Uporabniško ime je že zasedeno."; break;
                default: $error = "Prišlo je do napake med urejanjem uporabnika.";
            }
        }
        require_once('views/users/edit.php');
    }

    function update(){
        if(!isset($_SESSION["USER_ID"])){
            header("Location: /pages/error");
            die();
        }
        $user = User::find($_SESSION["USER_ID"]);
        //Preveri če so vsi podatki izpolnjeni
        if(empty($_POST["username"]) || empty($_POST["email"])){
            header("Location: /users/edit?error=1"); 
        }
        //Preveri ali uporabniško ime obstaja
        else if($user->username != $_POST["username"] && User::is_available($_POST["username"])){
            header("Location: /users/edit?error=2"); 
        }
        //Podatki so pravilno izpolnjeni, registriraj uporabnika
        else if($user->update($_POST["username"], $_POST["email"])){
            header("Location: /");
        }
        //Prišlo je do napake pri registraciji
        else{
            header("Location: /users/edit?error=3"); 
        }
        die();
    }

    public function profile()
    {
        require_once('models/articles.php');
        require_once('models/comments.php');
    
        // Zahtevaj, da je uporabnik prijavljen
        if (!isset($_SESSION["USER_ID"])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
    
        // Preveri, ali je v URL-ju podan "id"
        if (isset($_GET["id"])) {
            $userId = intval($_GET["id"]);  // profil avtorja novice
        } else {
            $userId = $_SESSION["USER_ID"]; // če ni id, prikaži profil prijavljenega
        }
    
        // Naloži podatke o tem uporabniku
        $user = User::find($userId);
        if (!$user) {
            die("Ta uporabnik ne obstaja.");
        }
    
        // Pridobi novice tega uporabnika
        $articles = Article::findByUser($user->id);
        $articleCount = count($articles);
    
        // Pridobi število komentarjev
        $commentCount = Comment::countByUser($user->id);
    
        // Vključi pogled za profil
        require_once('views/users/profile.php');
    }
    

    // Nova metoda za spremembo gesla
    public function update_password() {
        if (!isset($_SESSION["USER_ID"])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        $user = User::find($_SESSION["USER_ID"]);
        if (empty($_POST["old_password"]) || empty($_POST["new_password"]) || empty($_POST["repeat_new_password"])) {
            header("Location: index.php?controller=users&action=edit&error=password_empty");
            exit();
        }
        if ($_POST["new_password"] !== $_POST["repeat_new_password"]) {
            header("Location: index.php?controller=users&action=edit&error=password_mismatch");
            exit();
        }
        if (!password_verify($_POST["old_password"], $user->password)) {
            header("Location: index.php?controller=users&action=edit&error=incorrect_old_password");
            exit();
        }
        if ($user->updatePassword($_POST["new_password"])) {
            header("Location: index.php?controller=users&action=profile&success=password_changed");
            exit();
        } else {
            header("Location: index.php?controller=users&action=edit&error=password_update_failed");
            exit();
        }
    }
}