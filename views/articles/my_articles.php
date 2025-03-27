<div class="container">
    <h3 class="mb-3">Moje novice</h3>

    <?php if (empty($articles)): ?>
        <p>Trenutno nimate objavljenih novic.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <div class="article border p-3 mb-3">
                <h4><?php echo htmlspecialchars($article->title); ?></h4>
                <p><b>Povzetek:</b> <?php echo htmlspecialchars($article->abstract); ?></p>
                <p><?php echo htmlspecialchars($article->text); ?></p>
                <p><small>Objavljeno: <?php echo date_format(date_create($article->date), 'd. m. Y \ob H:i:s'); ?></small></p>
                <!-- Gumb za urejanje novice -->
                <a href="/articles/edit?id=<?php echo $article->id; ?>" class="btn btn-warning">Uredi</a>
                <!-- Gumb za brisanje novice -->
                <a href="/articles/delete?id=<?php echo $article->id; ?>" class="btn btn-danger" onclick="return confirm('Ali ste prepričani, da želite izbrisati to novico?')">Izbriši</a>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <a href="/" class="btn btn-secondary">Nazaj na domov</a>
</div>
