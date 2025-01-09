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

$id = $_GET['id'] ?? '';
$type = $_GET['type'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if ($type == 'movie') {
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $length = $_POST['length'];
        $released = $_POST['released'];
        $country = mysqli_real_escape_string($conn, $_POST['country']);
        $summary = mysqli_real_escape_string($conn, $_POST['summary']);
        $trailer = mysqli_real_escape_string($conn, $_POST['trailer']);

        $sql = "UPDATE movies SET 
                    title='$title', 
                    length_in_minutes='$length', 
                    released_at='$released', 
                    country_of_origin='$country', 
                    summary='$summary', 
                    youtube_trailer_id='$trailer' 
                WHERE id='$id'";

        if ($conn->query($sql) === true) {
            echo "Movie updated successfully";
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
            $sql = "UPDATE series SET 
                        title='$title', 
                        rating='$rating', 
                        summary='$summary', 
                        has_won_awards='$awards', 
                        seasons='$seasons', 
                        country='$country', 
                        spoken_in_language='$language' 
                    WHERE id='$id'";

            if ($conn->query($sql) === true) {
                echo "Series updated successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

if ($type == 'movie') {
    $sql = "SELECT * FROM movies WHERE id='$id'";
} elseif ($type == 'series') {
    $sql = "SELECT * FROM series WHERE id='$id'";
} else {
    echo "Invalid type.";
    exit();
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "No record found";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Movie or Series</title>
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
    </style>
</head>
<body>
    <h2>Edit Movie or Series</h2>
    <a href='index.php'>terug</a>

    <?php if ($type == 'movie') : ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?type=movie&id=<?php echo $id; ?>">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="<?php echo $row['title']; ?>" required><br><br>
            <label for="length">Length (min):</label><br>
            <input type="text" id="length" name="length" value="<?php echo $row['length_in_minutes']; ?>" required><br><br>
            <label for="released">Release Date:</label><br>
            <input type="date" id="released" name="released" value="<?php echo $row['released_at']; ?>" required><br><br>
            <label for="country">Country of Origin:</label><br>
            <input type="text" id="country" name="country" value="<?php echo $row['country_of_origin']; ?>" required><br><br>
            <label for="summary">Summary:</label><br>
            <input type="text" id="summary" name="summary" value="<?php echo $row['summary']; ?>" required><br><br>
            <label for="trailer">YouTube Trailer ID:</label><br>
            <input type="text" id="trailer" name="trailer" value="<?php echo $row['youtube_trailer_id']; ?>" required><br><br>
            <input type="submit" name="submit" value="Update">
        </form>
        
    <?php elseif ($type == 'series') : ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?type=series&id=<?php echo $id; ?>">
            <label for="title">Title:</label><br>
            <input type="text" id="title" name="title" value="<?php echo $row['title']; ?>" required><br><br>
            <label for="rating">Rating:</label><br>
            <input type="text" id="rating" name="rating" value="<?php echo $row['rating']; ?>" required><br><br>
            <label for="awards">Heeft prijzen gewonnen:</label><br>
            <input type="text" id="awards" name="awards" value="<?php echo $row['has_won_awards']; ?>" required><br><br>
            <label for="country">Land van herkomst:</label><br>
            <input type="text" id="country" name="country" value="<?php echo $row['country']; ?>" required><br><br>
            <label for="seasons">Aantal seizoenen:</label><br>
            <input type="text" id="seasons" name="seasons" value="<?php echo $row['seasons']; ?>" required><br><br>
            <label for="language">Gesproken taal:</label><br>
            <input type="text" id="language" name="language" value="<?php echo $row['spoken_in_language']; ?>" required><br><br>
            <label for="summary">Samenvatting:</label><br>
            <input type="text" id="summary" name="summary" value="<?php echo $row['summary']; ?>" required><br><br>
            <input type="submit" name="submit" value="Update">
        </form>
    <?php endif; ?>
</body>
</html>
