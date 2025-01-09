<?php

$servername = "localhost";
$username = "bit_academy";
$password = "";
$dbname = "netland";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sort_series = (isset($_GET['sort_series'])) ? $_GET['sort_series'] : 'title';
$order_series = (isset($_GET['order_series']) && $_GET['order_series'] === 'desc') ? 'DESC' : 'ASC';
$sort_movies = (isset($_GET['sort_movies'])) ? $_GET['sort_movies'] : 'title';
$order_movies = (isset($_GET['order_movies']) && $_GET['order_movies'] === 'desc') ? 'DESC' : 'ASC';

$sql_series = "SELECT * FROM series ORDER BY $sort_series $order_series";
$result_series = $conn->query($sql_series);

$sql_movies = "SELECT * FROM movies ORDER BY $sort_movies $order_movies";
$result_movies = $conn->query($sql_movies);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beheerderspagina</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #1c2951;
        }

        h2 {
            text-align: center;
            margin-top: 100px;
            color: white;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
            color: white;
        }

        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
            border-color: darkgrey;
        }

        th,
        td {
            padding-right: 20px;
            padding-left: 20px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border-color: darkgrey;
            cursor: pointer;
        }

        th {
            background-color: #1c2951;
            color: white;
        }

        tr {
            background-color: #1c2951;
            color: grey;
        }

        #insert {
            text-align: center;
        }
    </style>
</head>

<body>
    <h1>Beheerderspagina</h1>

    <h2>Series</h2>
    <table>
        <thead>
            <tr>
                <th><a>Titel</a></th>
                <th><a href="?sort_series=rating&order_series=<?php echo ($sort_series == 'rating' && $order_series == 'ASC') ? 'desc' : 'asc'; ?>">↓ Rating</a></th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_series->num_rows > 0) {
                while ($row = $result_series->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["rating"] . "</td>";
                    echo "<td><a href='detail.php?type=series&id=" . $row['id'] . "'>Bekijk details</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Geen series gevonden</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <h2>Movies</h2>
    <table>
        <thead>
            <tr>
                <th><a>Titel</a></th>
                <th><a href="?sort_movies=length_in_minutes&order_movies=<?php echo ($sort_movies == 'length_in_minutes' && $order_movies == 'ASC') ? 'desc' : 'asc'; ?>">↓ Lengte (min)</a></th>
                <th>Details</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result_movies->num_rows > 0) {
                while ($row = $result_movies->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["title"] . "</td>";
                    echo "<td>" . $row["length_in_minutes"] . "</td>";
                    echo "<td><a href='detail.php?type=movies&id=" . $row['id'] . "'>Bekijk details</a></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Geen films gevonden</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <div id='insert'>
        <?php
        echo "<td><a href='insert.php'>toevoegen</a></td>";
        echo "<p></p>";
        echo "<td><a href='edit.php'>bewerken</a></td>";
        ?>
    </div>
</body>

</html>