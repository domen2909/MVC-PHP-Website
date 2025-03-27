<div class="container">
    <h3 class="mb-4">Seznam novic</h3>

    <?php foreach ($articles as $article): ?>
        <div class="article border p-3 mb-3">
            <h4><?php echo htmlspecialchars($article->title); ?></h4>
            <p><b>Povzetek:</b> <?php echo htmlspecialchars($article->abstract); ?></p>
            <p>
                Objavil:
                <a href="index.php?controller=users&action=profile&id=<?php echo $article->user->id; ?>">
                    <?php echo htmlspecialchars($article->user->username); ?>
                </a>,
                    <?php echo date_format(date_create($article->date), 'd. m. Y \ob H:i:s'); ?>
            </p>

            <!-- Gumb za prebranje več -->
            <a href="/articles/show?id=<?php echo $article->id; ?>" class="btn btn-info">
                Preberi več
            </a>
        </div>
    <?php endforeach; ?>
</div>
