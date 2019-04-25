<div id="camagru">
    <?php session_start() ?>
    <h1>Hello <?= $_SESSION['login'] ?></h1>
    <section id="cam">
        <video id="video" width="640px" autoplay></video>
        <canvas id="canvas" value="pic" height="480px" width="640px"></canvas>
        <div class="info" id="photoInfo"></div>
        <div id="import-zone">
            <form action="<?= URL ?>?url=camagru&submit=import" method="post" enctype="multipart/form-data">
                <label>Import PNG image
                    <input type="file" name="img" accept="image/png">
                </label>
                <button type="submit">Import</button>
            </form>
        </div>
        <div id="cam-button">
            <button id="snap" type="button" class="btn-round rounded"><i class="fas fa-camera"></i></button>
            <button id="snap-push" type="submit" name="pic" class="btn-round rounded"><i class="fas fa-check"></i></button>
            <button id="import" type="submit" name="pic" class="btn-round rounded"><i class="fas fa-upload"></i></button>
        </div>
        <div id="cam-filters">
            <label>
                <input type="radio" name="filter" id="filter" checked="checked">
                <img src="<?= URL ?>public/asset/1.png" onclick="setFilter(this)">
            </label>
            <label>
                <input type="radio" name="filter" id="filter">
                <img src="<?= URL ?>public/asset/2.png" onclick="setFilter(this)">
            </label>
            <label>
                <input type="radio" name="filter" id="filter">
                <img src="<?= URL ?>public/asset/3.png" onclick="setFilter(this)">
            </label>
            <label>
                <input type="radio" name="filter" id="filter">
                <img src="<?= URL ?>public/asset/4.png" onclick="setFilter(this)">
            </label>
        </div>
    </section>
    <section id="cards" class="cards">
        <?php
        $index = -1;
        session_start();
        foreach ($photos as $photo): $index++?>
            <article>
                <img class="article-img" src="data:image/jpeg;base64,<?= $photo->getPhoto()?>">
                <div class="article-title">
                    <i class="fas fa-trash-alt" onclick="dropPhoto(<?= $photos[$index]->getId()?>, this)"></i>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</div>
<script src="<?= URL ?>scripts/camagru.js"></script>