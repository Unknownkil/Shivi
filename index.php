
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movies</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            text-align: center;
            background-color: #000000;
            margin: 0;
            padding: 0;
        }
        h1 {
            color: #FFFFFF;
            margin-top: 20px;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .box1 {
            width: calc(45% - 20px);
            border: 2px solid black;
            background-color: #050708;
            border-radius: 2rem .3rem;
            margin-bottom: 20px;
            overflow: hidden;
        }
        .box1:hover {
            background-color: rgba(165, 42, 42, 0.521);
            border-color: white;
        }
        .box1 img {
            width: 100%;
            height: auto;
            cursor: pointer;
        }
        .box1 .card-text {
            padding: 10px;
            text-align: left;
            color: #FFFFFF;
            font-size: 14px;
        }
        .box1 a {
            text-decoration: none;
            color: #FFFFFF;
        }
        input[type="search"] {
            width: 40%;
            height: 38px;
            border: 1.5px solid yellow;
            text-align: center;
            border-radius: 1rem;
            font-size: 18px;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        input[type="search"]:hover,
        input[type="search"]:focus {
            border: 4px solid orangered;
        }
        input[type="submit"] {
            background-color: orangered;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 1rem;
            font-size: 18px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #ff6347;
        }
        .section-buttons {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .section-buttons button {
            background-color: #FF6347;
            color: white;
            border: none;
            padding: 10px 15px; /* Reduced padding */
            border-radius: 1rem;
            font-size: 16px; /* Reduced font size */
            cursor: pointer;
            margin-bottom: 10px; /* Added margin-bottom for spacing */
        }
        .section-buttons button:hover {
            background-color: #FF4500;
        }
    </style>
</head>
<body>


<h1>Latest Movies</h1>

<div class="section-buttons">
    <form method="get">
        <button name="section" value="movies">Movies</button>
        <button name="section" value="cartoons">Cartoons</button>
        <button name="section" value="series">Series</button>
        <button name="section" value="hollywood">Hollywood</button>
    </form>
</div>

<form method="get" action="">
    <input type="search" name="search" placeholder="Search movies...">
    <input type="submit" value="Search">
</form>

<div class="container">
    <?php
    // Function to fetch and display data from API
    function displayMovies($apiUrl) {
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);
        
        if (isset($data['movies'])) {
            foreach ($data['movies'] as $movie) {
                echo '<div class="box1">';
                echo '<a href="play.php?id=' . urlencode($movie['id']) . '">';
                echo '<img src="' . $movie['image_url'] . '" alt="Movie Poster">';
                echo '</a>';
                echo '<div class="card-text">';
                echo '<h3 style="font-size: 16px;">' . $movie['name'] . '</h3>';
                echo '<p style="font-size: 12px;">Duration: ' . $movie['duration'] . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo '<p>No movies found.</p>';
        }
    }
    
    // Function to search across JSON data
    function searchSectionData($searchTerm, $apiBase) {
        $foundAny = false;
        for ($i = 0; $i <= 150; $i++) { // Search through pages 0 to 100
            $apiUrl = $apiBase . $i;
            $response = file_get_contents($apiUrl);
            $data = json_decode($response, true);

            if (isset($data['movies'])) {
                foreach ($data['movies'] as $movie) {
                    if (stripos($movie['name'], $searchTerm) !== false) {
                        echo '<div class="box1">';
                        echo '<a href="play.php?id=' . urlencode($movie['id']) . '">';
                        echo '<img src="' . $movie['image_url'] . '" alt="Movie Poster">';
                        echo '</a>';
                        echo '<div class="card-text">';
                        echo '<h3 style="font-size: 16px;">' . $movie['name'] . '</h3>';
                        echo '<p style="font-size: 12px;">Duration: ' . $movie['duration'] . '</p>';
                        echo '</div>';
                        echo '</div>';
                        $foundAny = true;
                    }
                }
            }
        }

        if (!$foundAny) {
            echo '<p>No results found for "' . htmlspecialchars($searchTerm) . '".</p>';
        }
    }
    
    // Check if search term is set in GET parameters
    if (isset($_GET['search'])) {
        $searchTerm = $_GET['search'];
        
        if (isset($_GET['section'])) {
            $section = $_GET['section'];
            if ($section === 'movies') {
                $apiBase = 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=movies/date/';
            } elseif ($section === 'cartoons') {
                $apiBase = 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=Cartoon-Animation-Dub-in-Hindi/date/';
            } elseif ($section === 'series') {
                $apiBase = 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=Netflix-Hindi-Audio-TV-Full-Shows-Zee5-WEB-Serials/date/';
            } elseif ($section === 'hollywood') {
                $apiBase = 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=All-Hollywood-Movies-Dub-in-Hindi/date/';
            }
            searchSectionData($searchTerm, $apiBase);
        } else {
            // Search across all sections if no specific section is selected
            $sections = [
                'movies' => 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=movies/date/',
                'cartoons' => 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=Cartoon-Animation-Dub-in-Hindi/date/',
                'series' => 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=Netflix-Hindi-Audio-TV-Full-Shows-Zee5-WEB-Serials/date/',
                'hollywood' => 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=All-Hollywood-Movies-Dub-in-Hindi/date/'
            ];
            
            foreach ($sections as $apiBase) {
                searchSectionData($searchTerm, $apiBase);
            }
        }
    } elseif (isset($_GET['section'])) {
        $section = $_GET['section'];
        
        // Determine API URL based on section
        if ($section === 'movies') {
            $apiBase = 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=movies/date/';
        } elseif ($section === 'cartoons') {
            $apiBase = 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=Cartoon-Animation-Dub-in-Hindi/date/';
        } elseif ($section === 'series') {
            $apiBase = 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=Netflix-Hindi-Audio-TV-Full-Shows-Zee5-WEB-Serials/date/';
        } elseif ($section === 'hollywood') {
            $apiBase = 'https://moviesapi.nepdevsnepcoder.workers.dev/api/movies?url=All-Hollywood-Movies-Dub-in-Hindi/date/';
        }
        
        // Display movies based on selected section
        for ($i = 0; $i <= 150; $i++) {
            $apiUrl = $apiBase . $i;
            echo '<h2>Page ' . $i . '</h2>';
            displayMovies($apiUrl);
        }
    } else {
        // Default to movies if no section or search is selected
        $defaultApiUrl = 'https://moviesapi.nepdevsnepcoder.workers.dev/';
        displayMovies($defaultApiUrl);
    }
    ?>
</div>

</body>
</html>
