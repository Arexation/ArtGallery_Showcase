<?php
$paintings = [];
$artists = [];
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


$artistId = isset($_GET['id']) ? $_GET['id'] : '';


$artist = null;
foreach ($artists as $a) {
    if ($a['id'] === $artistId) {
        $artist = $a;
        break;
    }
}

$artistPaintings = [];
if ($artist) {
    foreach ($paintings as $painting) {
        if ($painting['artist_name'] === $artist['name']) {
            $artistPaintings[] = $painting;
        }
    }
}


$genres = [];
foreach ($paintings as $painting) {
    if (!in_array($painting['genre'], $genres)) {
        $genres[] = $painting['genre'];
    }
}

$uniqueArtists = [];
foreach ($artists as $a) {
    $uniqueArtists[$a['id']] = $a; 
}
$artists = array_values($uniqueArtists); 
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>
        <?php echo $artist ? htmlspecialchars($artist['name']) : 'Artist Not Found'; ?>
    </title>
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
            <div class="singleArtistCont">
                <?php if ($artist): ?>
                <h1>
                    <?php echo htmlspecialchars($artist['name']); ?>
                </h1>

                <picture>
                    <source srcset="resources/artists/large/<?php echo htmlspecialchars($artist['id']); ?>.jpg"
                        media="(min-width: 1200px)">
                    <source srcset="resources/artists/medium/<?php echo htmlspecialchars($artist['id']); ?>.jpg"
                        media="(min-width: 768px)">
                    <source srcset="resources/artists/square-small/<?php echo htmlspecialchars($artist['id']); ?>.jpg"
                        media="(min-width: 320px)">

                    <img src="resources/artists/large/<?php echo htmlspecialchars($artist['id']); ?>.jpg"
                        alt="<?php echo htmlspecialchars($artist['name']); ?>" />
                </picture>
                <p class="date">
                    <?php echo htmlspecialchars($artist['year_of_birth']); ?> -
                    <?php echo htmlspecialchars($artist['year_of_death']); ?>
                </p>
                <p>(
                    <?php echo htmlspecialchars($artist['nationality']); ?>)
                </p>
                <p class="description">
                    <?php echo nl2br($artist['description']); ?>
                </p>
                <p><a class="wiki" href="<?php echo htmlspecialchars($artist['wikipedia_link']); ?>" target="_blank">
                        <?php echo htmlspecialchars($artist['name']); ?> on <span>Wikipedia</span>
                    </a></p>


            </div>


            <h2 class="bottomName">Paintings by
                <?php echo htmlspecialchars($artist['name']); ?>
            </h2>

            <?php if (!empty($artistPaintings)): ?>
            <?php foreach ($artistPaintings as $painting): ?>


            <div class="painting-item">
                <div class="painting-img">
                    <a href="singlePainting.php?id=<?php echo urlencode($painting['id']); ?>">
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
                                alt="<?php echo htmlspecialchars($painting['title']); ?>" />
                        </picture>
                    </a>
                </div>
                <div class="painting-info">
                    <h3><a href="singlePainting.php?id=<?php echo urlencode($painting['id']); ?>">
                            <?php echo htmlspecialchars($painting['title']); ?>
                        </a></h3>
                </div></a>
            </div>

            <?php endforeach; ?>
            <?php else: ?>
            <p>No paintings found for this artist.</p>
            <?php endif; ?>
            <?php else: ?>
            <h1>Artist Not Found</h1>
            <p>Sorry, we couldn't find an artist with the provided ID.</p>
            <?php endif; ?>


            <a class="back" href="index.php">
                <p class="header">Back to Gallery</p>
            </a>
        </div>



    </div>

</body>

</html>