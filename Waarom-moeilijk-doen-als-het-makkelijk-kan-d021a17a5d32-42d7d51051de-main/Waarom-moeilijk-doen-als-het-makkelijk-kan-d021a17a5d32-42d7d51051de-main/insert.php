<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "bit_academy";
$password = "";
$dbname = "netland";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$type = $_POST['type'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if ($type == 'movie') {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $length = $_POST['length'];
        $released = $_POST['released'];
        $country = mysqli_real_escape_string($conn, $_POST['country']);
        $summary = mysqli_real_escape_string($conn, $_POST['summary']);
        $trailer = mysqli_real_escape_string($conn, $_POST['trailer']);

        $sql = "INSERT INTO movies (title, length_in_minutes, released_at, country_of_origin, summary, youtube_trailer_id) 
                VALUES ('$title', '$length', '$released', '$country', '$summary', '$trailer')";

        if ($conn->query($sql) === true) {
            echo "New movie added successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } elseif ($type == 'series') {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $rating = $_POST['rating'];
        $awards = $_POST['awards'];
        $country = mysqli_real_escape_string($conn, $_POST['country']);
        $seasons = $_POST['seasons'];
        $language = mysqli_real_escape_string($conn, $_POST['language']);
        $summary = mysqli_real_escape_string($conn, $_POST['summary']);

        if (strlen($language) > 255) {
            echo "Error: The 'Gesproken taal' field exceeds the maximum length of 255 characters.";
        } else {
            $sql = "INSERT INTO series (title, rating, summary, has_won_awards, seasons, country, spoken_in_language) 
                    VALUES ('$title', '$rating', '$summary', '$awards', '$seasons', '$country', '$language')";

            if ($conn->query($sql) === true) {
                echo "New series added successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Movies or Series</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1c2951;
        }

        h2 {
            text-align: center;
            color: white;
        }

        form {
            align-items: center;
            text-align: center;
        }

        a {
            display: block;
            text-align: center;
            color: lightblue;
            margin-bottom: 20px;
        }

        a:hover {
            color: red;
        }

        .form-section {
            display: none;
        }
    </style>
</head>

<body>
    <h2>Add Movies or Series</h2>
    <a href='index.php'>terug</a>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="type">Choose type:</label><br>
        <select id="type" name="type" required onchange="this.form.submit()">
            <option value="">Select...</option>
            <option value="movie" <?php if ($type == 'movie') {
                echo 'selected';
                                  }
                                    ?>>Movie</option>
            <option value="series" <?php if ($type == 'series') {
                echo 'selected';
                                   }
                                    ?>>Series</option>
        </select><br><br>
    </form>


    <?php if ($type == 'movie') : ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="type" value="movie">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" required><br><br>
            <label for="length">Length (min):</label><br>
            <input type="text" id="length" name="length" required><br><br>
            <label for="released">Release Date:</label><br>
            <input type="date" id="released" name="released" required><br><br>
            <label for="country">Country of Origin:</label><br>
            <input type="text" id="country" name="country" required><br><br>
            <label for="summary">Summary:</label><br>
            <input type="text" id="summary" name="summary" required><br><br>
            <label for="trailer">YouTube Trailer ID:</label><br>
            <input type="text" id="trailer" name="trailer" required><br><br>
            <input type="submit" name="submit" value="Submit">
        </form>
        
    <?php elseif ($type == 'series') : ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="type" value="series">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" required><br><br>
            <label for="rating">Rating:</label><br>
            <input type="text" id="rating" name="rating" required><br><br>
            <label for="awards">Heeft prijzen gewonnen:</label><br>
            <input type="text" id="awards" name="awards" required><br><br>
            <label for="country">Land van herkomst:</label><br>
            <input type="text" id="country" name="country" required><br><br>
            <label for="seasons">Aantal seizoenen:</label><br>
            <input type="text" id="seasons" name="seasons" required><br><br>
            <label for="language">Gesproken taal:</label><br>
            <input type="text" id="language" name="language" required><br><br>
            <label for="summary">Samenvatting:</label><br>
            <input type="text" id="summary" name="summary" required><br><br>
            <input type="submit" name="submit" value="Submit">
        </form>
    <?php endif; ?>
</body>

</html>
