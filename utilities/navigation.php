<?php
function renderNavigation($genres, $artists) {
?>
<div id="genreListing">
    <h2>Genres</h2>
    <a href="./index.php">
    <li>
        &nbsp;&nbsp;&nbsp;ALL&nbsp;&nbsp;&nbsp;
    </li>
    </a>
    <?php foreach ($genres as $genre): ?>
        <a href="./index.php?genre=<?php echo urlencode($genre); ?>"><li><?php echo htmlspecialchars($genre); ?></li></a>
    <?php endforeach; ?>
</div>

<div id="artistListing">
    <h2>Artists</h2>
    <?php foreach ($artists as $artist): ?>
        <a href="./singleArtist.php?id=<?php echo urlencode($artist['id']); ?>">
        <li>
            <?php echo htmlspecialchars($artist['name']); ?> 
        </li>
        </a>
    <?php endforeach; ?>
</div>
<?php
}
?>
