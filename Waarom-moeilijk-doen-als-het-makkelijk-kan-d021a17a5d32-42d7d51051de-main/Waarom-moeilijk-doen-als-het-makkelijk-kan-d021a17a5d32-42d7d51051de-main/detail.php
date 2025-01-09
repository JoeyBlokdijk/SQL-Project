<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail</title>
    <style>
        body {
            background-color: #1c2951;
        }

        h1,
        p {
            color: white;
            text-align: center;
        }

        #samenvatting {
            border-style: solid;
            border-radius: 10px;
            border-color: darkgrey;
        }

        a {
            color: lightblue;
        }

        a:hover {
            color: red;
        }
    </style>
</head>
<body>
<?php
if (isset($_GET['type']) && isset($_GET['id']) && !empty($_GET['type']) && !empty($_GET['id'])) {
    $type = $_GET['type'];
    $id = $_GET['id'];

    $servername = "localhost";
    $username = "bit_academy";
    $password = "";
    $dbname = "netland";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($type === 'series') {
        $sql = "SELECT * FROM series WHERE id = $id";
    } elseif ($type === 'movies') {
        $sql = "SELECT * FROM movies WHERE id = $id";
    } else {
        echo "Ongeldige type geselecteerd.";
        exit;
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<a href='index.php'>terug</a>";

        if ($type === 'series') {
            echo "<h1>" . $row['title'] . "</h1>";
            echo "<p>Rating: " . $row['rating'] . "</p>";
            echo "<p>Heeft prijzen gewonnen: " . ($row['has_won_awards'] ? 'Ja' : 'Nee') . "</p>";
            echo "<p>Land van herkomst: " . $row['country'] . "</p>";
            echo "<p>Aantal seizoenen: " . $row['seasons'] . "</p>";
            echo "<p>Gesproken taal: " . $row['spoken_in_language'] . "</p>";
            echo "<div id='samenvatting'>";
            echo "<p>Samenvatting: </p>";
            echo "<p>" . $row['summary'] . "</p>";
            echo "</div>";
        } elseif ($type === 'movies') {
            echo "<h1>" . $row['title'] . "</h1>";
            echo "<p>Lengte: " . $row['length_in_minutes'] . " min</p>";
            echo "<p>Uitgebracht op: " . $row['released_at'] . "</p>";
            echo "<p>Land van herkomst: " . $row['country_of_origin'] . "</p>";
            echo "<div id='samenvatting'>";
            echo "<p>Samenvatting: " . $row['summary'] . "</p>";
            echo "</div>";
            if (!empty($row['youtube_trailer_id'])) {
                echo "<iframe width='560' height='315' src='https://www.youtube.com/embed/" . $row['youtube_trailer_id'] . "' frameborder='0' allowfullscreen id='trailer'></iframe>";
            }
        }
    } else {
        echo "Geen details gevonden voor deze $type.";
    }

    $conn->close();
} else {
    echo "Geen geldige selectie.";
}
?>
</body>
</html>
