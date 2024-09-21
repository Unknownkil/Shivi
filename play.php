<?php
if (!isset($_GET['id'])) {
    echo '<p>Error: No movie ID provided!</p>';
    exit;
}

$id = $_GET['id'];
$apiUrl = 'https://moviesapi.nepdevsnepcoder.workers.dev/api/video?mp4=' . urlencode($id);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$response = curl_exec($ch);
curl_close($ch);

$videoData = json_decode($response, true);

if (!isset($videoData['video_url'])) {
    echo '<p>Error: Video URL not found!</p>';
    exit;
}

$videoUrl = $videoData['video_url'];

// Replace domain if necessary
if (strpos($videoUrl, 'fastlink.cyou') !== false) {
    $videoUrl = str_replace('fastlink.cyou', '1.fastlink.cyou', $videoUrl);
}

// MP4 Ad URL
$mp4AdUrl = 'https://anonm3u.000webhostapp.com/bjbef9.mp4'; // Replace with your MP4 ad URL
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moviehome</title>
    <script src="//content.jwplatform.com/libraries/SAHhwvZq.js"></script>
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
            margin-top: 10px;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
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
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
        }
        .section-buttons button {
            background-color: #FF6347;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 1rem;
            font-size: 18px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .section-buttons button:hover {
            background-color: #FF4500;
        }
        .telegram-button {
            background-color: #0088cc;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 1rem;
            font-size: 18px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .telegram-button:hover {
            background-color: #005580;
        }
        /* JW Player CSS */
        .jwplayer {
            width: 100%;
            height: 60vh; /* Adjusted height for better viewing */
            background-color: #000000 !important;
            border: none !important;
            border-radius: 20px !important;
        }
        .jw-icon-tooltip {
            background-color: #FF6347 !important;
        }
        .jw-controlbar {
            background-color: #050708 !important;
        }
        .jw-controlbar .jw-icon-tooltip {
            color: #FFFFFF !important;
        }
        .jw-sharing-tooltip {
            background-color: #FF6347 !important;
        }
        .jw-sharing-tooltip .jw-sharing-tooltip-text {
            color: #FFFFFF !important;
        }
        .jw-logo {
            display: none !important;
        }
        .skip-button {
            background-color: #FF6347;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 1rem;
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
        }
        .skip-button:hover {
            background-color: #FF4500;
        }
        .download-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 1rem;
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
            text-decoration: none;
            display: inline-block;
        }
        .download-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>Moviehome Your Home Theatre</h1>
    <div id="player" class="box1"></div>

    <!-- JW Player Skip and Download buttons -->
    <button id="skipButton" class="skip-button" style="display: none;">Skip Ad</button>
    <a href="https://t.me/nepdevs" class="telegram-button">Join Telegram</a>
    <a id="downloadButton" class="download-button" href="<?php echo htmlspecialchars('https://moviessearchapi.nepdevsnepcoder.workers.dev/?url=' . urlencode($videoUrl)); ?>" download>Download</a>

    <script type="text/javascript">
        var playerInstance = jwplayer("player");

        var adPlaying = true; // Flag to track if ad is playing

        // Function to skip the ad and play the movie
        function skipAd() {
            if (adPlaying) {
                playerInstance.playlistItem(1); // Move to the next item in the playlist (movie)
                adPlaying = false; // Update ad playing status
                document.getElementById('skipButton').style.display = 'none'; // Hide skip button
            }
        }

        playerInstance.setup({
            playlist: [{
                file: "<?php echo htmlspecialchars($mp4AdUrl); ?>",
                image: "https://mallucampaign.in/images/img_1720423801.jpg",
                type: "mp4",
                advertising: {
                    client: "vpaid",
                    tag: "<?php echo htmlspecialchars($mp4AdUrl); ?>"
                }
            }, {
                file: "<?php echo htmlspecialchars($videoUrl); ?>",
                image: "https://mallucampaign.in/images/img_1720423801.jpg",
                type: "mp4",
                captions: {
                    color: "#FF0000",
                    fontSize: 14,
                    fontFamily: "Arial",
                    backgroundOpacity: 0.5
                },
                autostart: true,
                primary: "html5",
                skin: {
                    name: "bekle",
                    active: "#FF0000"
                },
                sharing: {
                    link: "<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>",
                    sites: ["telegram"]
                },
                logo: {
                    file: "https://www.example.com/logo.png",
                    link: "https://www.example.com",
                    hide: false,
                    position: "top-left"
                },
                related: {
                    autoplaytimer: 10,
                    file: "https://api.jwplayer.com/v2/playlists/VIDEO_PLAYLIST_ID"
                },
                tracks: [{
                    file: "https://www.example.com/captions_en.vtt",
                    label: "English",
                    kind: "captions"
                }],
                playbackRateControls: [0.5, 0.75, 1, 1.25, 1.5, 2],
                abouttext: "Your custom about text",
                aboutlink: "https://www.example.com/about",
                aspectratio: "16:9",
                displaytitle: true,
                controls: true,
                displaydescription: true,
                autostart: false
            }],
            autostart: false,
            width: "100%",
            aspectratio: "16:9",
            controls: true,
            sharing: {
                link: "<?php echo htmlspecialchars($_SERVER['REQUEST_URI']); ?>",
                sites: ["telegram"]
            }
        });

        // Add skip ad button logic
        playerInstance.on('adTime', function(event) {
            var adDuration = event.duration; // Get duration of the ad
            setTimeout(function() {
                if (adPlaying) {
                    document.getElementById('skipButton').style.display = 'block'; // Display skip button 3 seconds before ad ends
                }
            }, (adDuration - 3000)); // Display skip button 3 seconds before ad ends
        });

        // Click event for skip ad button
        document.getElementById('skipButton').addEventListener('click', function() {
            skipAd();
        });

        // Event listener for when main video starts
        playerInstance.on('play', function() {
            adPlaying = false; // Main video starts playing, no longer in ad phase
            document.getElementById('skipButton').style.display = 'none'; // Hide skip button
        });

        // Event listener for when playlist item changes
        playerInstance.on('playlistItem', function(event) {
            if (event.index === 0) { // If ad is playing
                adPlaying = true; // Set ad playing flag to true
            } else { // If main video is playing
                adPlaying = false; // Set ad playing flag to false
            }
        });

        // Custom controls for seeking backward and forward
        playerInstance.on('ready', function() {
            var videoElement = document.getElementById('player');

            function seekBackward() {
                var currentTime = playerInstance.getPosition();
                playerInstance.seek(currentTime - 10);
            }

            function seekForward() {
                var currentTime = playerInstance.getPosition();
                playerInstance.seek(currentTime + 10);
            }

            document.addEventListener('keydown', function(event) {
                switch(event.key) {
                    case 'ArrowLeft':
                        seekBackward();
                        break;
                    case 'ArrowRight':
                        seekForward();
                        break;
                    default:
                        return;
                }
            });
        });
    </script>
</body>
</html>
