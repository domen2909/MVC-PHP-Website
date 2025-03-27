<div class="container">
    <h3 class="mb-3">Uredi profil</h3>
    <form action="index.php?controller=users&action=update" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Vzdevek</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user->username); ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-pošta</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user->email); ?>">
        </div>
        <button type="submit" class="btn btn-primary" name="register">Shrani</button>
        <label class="text-danger"><?php echo $error; ?></label>
    </form>

    <!-- Obrazec za spremembo gesla -->
    <hr>
    <h3 class="mb-3">Spremeni geslo</h3>
    <form action="index.php?controller=users&action=update_password" method="POST">
        <div class="mb-3">
            <label for="old_password" class="form-label">Staro geslo</label>
            <input type="password" class="form-control" id="old_password" name="old_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Novo geslo</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="mb-3">
            <label for="repeat_new_password" class="form-label">Ponovi novo geslo</label>
            <input type="password" class="form-control" id="repeat_new_password" name="repeat_new_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Spremeni geslo</button>
    </form>
</div>
