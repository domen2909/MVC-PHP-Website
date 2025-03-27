<div class="container">
    <h3 class="mb-3">Uredi novico</h3>
    <form action="/articles/update" method="POST">
        <!-- ID novice (skrit input) -->
        <input type="hidden" name="id" value="<?php echo $article->id; ?>">

        <div class="mb-3">
            <label for="title" class="form-label">Naslov</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($article->title); ?>" required>
        </div>
        <div class="mb-3">
            <label for="abstract" class="form-label">Povzetek</label>
            <textarea class="form-control" id="abstract" name="abstract" rows="2" required><?php echo htmlspecialchars($article->abstract); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="text" class="form-label">Opis novice</label>
            <textarea class="form-control" id="text" name="text" rows="5" required><?php echo htmlspecialchars($article->text); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Shrani spremembe</button>
        <a href="/articles/my_articles" class="btn btn-secondary">Prekliči</a>
    </form>
</div>
