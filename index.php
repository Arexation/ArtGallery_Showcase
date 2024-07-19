<?php
$paintings = [];
$paintingsFile = fopen('resources/paintings.txt', 'r');
while (($line = fgets($paintingsFile)) !== false) {
    $fields = explode('~', trim($line));
    $paintings[] = [
        'id' => trim($fields[0]),
        'artist_name' => trim($fields[1]),
        'title' => htmlspecialchars(trim($fields[2])),
        'year' => trim($fields[3]),
        'width' => trim($fields[4]),
        'height' => trim($fields[5]),
        'price' => trim($fields[6]),
        'description' => trim($fields[7]),
        'wikipedia_link' => trim($fields[8]),
        'genre' => trim($fields[9])
    ];
}
fclose($paintingsFile);

$artists = [];
$artistsFile = fopen('resources/artists.txt', 'r');
while (($line = fgets($artistsFile)) !== false) {
    $fields = explode('~', trim($line));
    $artists[] = [
        'id' => trim($fields[0]),
        'name' => trim($fields[1]),
        'nationality' => trim($fields[2]),
        'year_of_birth' => trim($fields[3]),
        'year_of_death' => trim($fields[4]),
        'description' => trim($fields[5]),
        'wikipedia_link' => trim($fields[6])
    ];
}
fclose($artistsFile);

$genreFilter = isset($_GET['genre']) ? $_GET['genre'] : '';

$filteredPaintings = array_filter($paintings, function($painting) use ($genreFilter) {
    return $genreFilter === '' || $painting['genre'] === $genreFilter;
});

$genres = [];
foreach ($paintings as $painting) {
    if (!in_array($painting['genre'], $genres)) {
        $genres[] = $painting['genre'];
    }
}

$uniqueArtists = [];
foreach ($artists as $artist) {
    $uniqueArtists[$artist['id']] = $artist;
}
$artists = array_values($uniqueArtists);

function getArtistIdByName($artists, $name) {
    foreach ($artists as $artist) {
        if ($artist['name'] === $name) {
            return $artist['id'];
        }
    }
    return null;
}
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Home Page of Art Gallery</title>
    <link rel="stylesheet" href="./css/Style.css" />
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <meta name="author" content="Arian Rahimzadeh">
</head>

<body class="d-flex flex-column min-vh-100">

    <div id="main" class="d-flex flex-grow-1">
        <div id="menu" class="text-white p-4 col-md-2 text-center fs-6">
            <?php include("utilities/header.php"); ?>
            <?php include("utilities/navigation.php"); ?>
            <?php renderNavigation($genres, $artists); ?>
        </div>

        <div class="container my-6 flex-grow-2 text-center">
            <?php foreach ($filteredPaintings as $painting): ?>
            <div class="painting-item p-3">
                <div class="painting-img">
                    <?php 
                    $imagePath = 'resources/paintings/square-medium/' . htmlspecialchars($painting['id']) . '.jpg';
                    $imageAlt = htmlspecialchars($painting['title']);
                    ?>
                    <a href="singlePainting.php?id=<?php echo urlencode($painting['id']); ?>">
                        <?php if (file_exists($imagePath)): ?>
                        <picture>
                            <source
                                srcset="resources/paintings/square-medium/<?php echo htmlspecialchars($painting['id']); ?>.jpg"
                                media="(min-width: 1200px)">
                            <source
                                srcset="resources/paintings/square-small/<?php echo htmlspecialchars($painting['id']); ?>.jpg"
                                media="(min-width: 768px)">
                            <source
                                srcset="resources/paintings/square-tiny/<?php echo htmlspecialchars($painting['id']); ?>.jpg"
                                media="(min-width: 320px)">

                            <img src="resources/paintings/square-medium/<?php echo htmlspecialchars($painting['id']); ?>.jpg"
                                alt="<?php echo $imageAlt; ?>" />
                        </picture>
                        <?php else: ?>
                        <p>Image not found for
                            <?php echo $imageAlt; ?>
                        </p>
                        <?php endif; ?>
                    </a>
                </div>
                <h3><a href="singlePainting.php?id=<?php echo urlencode($painting['id']); ?>">
                        <?php echo $imageAlt; ?>
                    </a></h3>
                <div class="painting-artist">
                    <?php
                    $artistId = getArtistIdByName($artists, $painting['artist_name']);
                    if ($artistId !== null):
                    ?>
                    <p><a href="./singleArtist.php?id=<?php echo urlencode($artistId); ?>">
                            <?php echo htmlspecialchars($painting['artist_name']); ?>
                        </a></p>
                    <?php else: ?>
                    <p>
                        <?php echo htmlspecialchars($painting['artist_name']); ?>
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>