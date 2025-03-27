<div class="container">
    <h2>Moj profil</h2>
    <?php if (!$user): ?>
        <p>Uporabnik ni najden.</p>
    <?php else: ?>
        <p><strong>Uporabniško ime:</strong> <?php echo htmlspecialchars($user->username); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user->email); ?></p>
        <p><strong>Objavljenih novic:</strong> <?php echo $articleCount; ?></p>
        <p><strong>Objavljenih komentarjev:</strong> <?php echo $commentCount; ?></p>
        <!-- Opcijsko: dodaj gumb za urejanje profila -->
        <a href="index.php?controller=users&action=edit" class="btn btn-warning">Uredi profil</a>
    <?php endif; ?>
</div>
