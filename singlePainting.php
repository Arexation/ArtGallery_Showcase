<?php
$paintings = [];
$paintingsFile = fopen('resources/paintings.txt', 'r');
while (($line = fgets($paintingsFile)) !== false) {
    $fields = explode('~', trim($line));
    $paintings[] = [
        'id' => trim($fields[0]),
        'artist_name' => trim($fields[1]),
        'title' => trim($fields[2]),
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
        'description' => trim($fields[5], " \t\n\r\0\x0B\xC2\xA0"),
        'wikipedia_link' => trim($fields[6])
    ];
}
fclose($artistsFile);

$paintingId = isset($_GET['id']) ? $_GET['id'] : '';

$painting = array_filter($paintings, function($p) use ($paintingId) {
    return $p['id'] === $paintingId;
});
$painting = reset($painting);

if (!$painting) {
    die('Painting not found.');
}

$artistId = null;
foreach ($artists as $artist) {
    if ($artist['name'] == $painting['artist_name']) {
        $artistId = $artist['id'];
        break;
    }
}

if (!$artistId) {
    die('Artist not found.');
}

$genres = [];
foreach ($paintings as $p) {
    if (!in_array($p['genre'], $genres)) {
        $genres[] = $p['genre'];
    }
}

$uniqueArtists = [];
foreach ($artists as $artist) {
    $uniqueArtists[$artist['id']] = $artist;
}
$artists = array_values($uniqueArtists);
?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>
        <?php echo htmlspecialchars($painting['title']); ?>
    </title>
    <link rel="stylesheet" href="./css/Style.css" />
    <meta charset="UTF-8">
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <meta name="author" content="Arian Rahimzadeh">
</head>

<body class="d-flex flex-column min-vh-100 singlePainting">

    <div id="main" class="d-flex flex-grow-1">
        <div id="menu" class=" text-white p-4 col-md-2 text-center fs-6">
            <?php include("utilities/header.php"); ?>
            <?php include("utilities/navigation.php"); ?>
            <?php renderNavigation($genres, $artists); ?>
        </div>

        <div class="container my-6 flex-grow-2 text-center">
            <div class="singlePaintCont">
                <?php if ($painting): ?>
                <picture>
                    <source srcset="resources/paintings/huge/<?php echo htmlspecialchars($painting['id']); ?>.jpg"
                        media="(min-width: 1200px)">
                    <source srcset="resources/paintings/large/<?php echo htmlspecialchars($painting['id']); ?>.jpg"
                        media="(min-width: 768px)">
                    <source srcset="resources/paintings/medium/<?php echo htmlspecialchars($painting['id']); ?>.jpg"
                        media="(min-width: 320px)">

                    <img src="resources/paintings/huge/<?php echo htmlspecialchars($painting['id']); ?>.jpg"
                        alt="<?php echo htmlspecialchars($painting['title']); ?>" />
                </picture>

                <h1>
                    <?php echo htmlspecialchars($painting['title']); ?>
                </h1>
                <p><b>Artist:</b> <a href="singleArtist.php?id=<?php echo urlencode($artistId); ?>">
                        <?php echo htmlspecialchars($painting['artist_name']); ?>
                    </a></p>
                <p>( Year:
                    <?php echo htmlspecialchars($painting['year']); ?> -
                    <?php echo htmlspecialchars($painting['width']); ?>cm x
                    <?php echo htmlspecialchars($painting['height']); ?>cm )
                </p>
                <p><b>Genre:</b> <a href="index.php?genre=<?php echo urlencode($painting['genre']); ?>">
                        <?php echo htmlspecialchars($painting['genre']); ?>
                    </a></p>
                <p class="description">
                    <?php echo $painting['description']; ?>
                </p>
                <p><a class="wiki" href="<?php echo htmlspecialchars($painting['wikipedia_link']); ?>" target="_blank">
                        <?php echo htmlspecialchars($painting['title']); ?> on <span>Wikipedia</span>
                    </a></p>
                <?php else: ?>
                <p>Painting not found.</p>
                <?php endif; ?>


            </div>
            <a class="back" href="index.php">
                <p class="header">Back to Gallery</p>
            </a>
        </div>

    </div>
</body>

</html>