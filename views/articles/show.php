<div class="container">
    <h3>Seznam novic</h3>
    <div class="article">
        <h4><?php echo $article->title;?></h4>
        <p><b>Povzetek:</b> <?php echo $article->abstract;?></p>
        <p><?php echo $article->text; ?></p>
        <p>
            Objavil: 
                <a href="index.php?controller=users&action=profile&id=<?php echo $article->user->id; ?>">
                <?php echo htmlspecialchars($article->user->username); ?>
                </a>, 
                <?php echo date_format(date_create($article->date), 'd. m. Y \ob H:i:s'); ?>
                </p>

        <!-- Gumbi za lastnika novice -->
        <?php if (isset($_SESSION["USER_ID"]) && $_SESSION["USER_ID"] == $article->user_id): ?>
            <a href="/articles/edit?id=<?php echo $article->id; ?>" class="btn btn-warning">Uredi</a>
            <a href="/articles/delete?id=<?php echo $article->id; ?>" class="btn btn-danger" onclick="return confirm('Ali ste prepričani, da želite izbrisati to novico?')">Izbriši</a>
        <?php endif; ?>

        <a href="/" class="btn btn-secondary">Nazaj</a>
    </div>

    <!-- Komentarji -->
    <div class="container mt-4">
        <h3>Komentarji</h3>

        <?php
        require_once('models/comments.php'); // Preveri, ali je vključeno
        
        $comments = Comment::findByArticle($article->id);
        
        if (empty($comments)): ?>
            <p>Za to novico še ni komentarjev.</p>
        <?php else: ?>
            <?php foreach ($comments as $comment): ?>
                <div class="comment border p-2 mb-2">
                    <p><strong>Uporabnik #<?php echo $comment->user_id; ?></strong> je zapisal:</p>
                    <p><?php echo htmlspecialchars($comment->content); ?></p>
                    <small><?php echo date_format(date_create($comment->created_at), 'd. m. Y \ob H:i:s'); ?></small>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Obrazec za dodajanje novega komentarja -->
        <?php if (isset($_SESSION["USER_ID"]) && $_SESSION["USER_ID"] !== $article->user_id): ?>
            <h4>Dodaj komentar</h4>
            <form action="/comments/store" method="POST">
                <input type="hidden" name="article_id" value="<?php echo $article->id; ?>">
                <div class="mb-3">
                    <label for="content" class="form-label">Vaš komentar:</label>
                    <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Objavi komentar</button>
            </form>
        <?php endif; ?>
    </div>
</div>
