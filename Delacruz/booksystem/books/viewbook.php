<?php
require_once "../classes/book.php";
$bookObj = new Book();

$title = $genre = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $title = isset($_GET["title"]) ? trim(htmlspecialchars($_GET["title"])) : "";
    $genre = isset($_GET["genre"]) ? trim(htmlspecialchars($_GET["genre"])) : "";
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Library - View Books</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">

        <div class="card">
            <h1>Library Book List</h1>

            <form method="get" class="search-form">
                <label>Title:</label>
                <input type="text" name="title" value="<?= $title ?>">

                <label>Genre:</label>
                <select name="genre">
                    <option value="">--All--</option>
                    <option value="history" <?= ($genre=="history")?"selected":"" ?>>History</option>
                    <option value="science" <?= ($genre=="science")?"selected":"" ?>>Science</option>
                    <option value="fiction" <?= ($genre=="fiction")?"selected":"" ?>>Fiction</option>
                </select>

                <input type="submit" class="btn" value="Search">

            </form>


            
        </div>

        <div class="card table-wrapper">
            <table>
                <tr>
                    <th>No.</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Year</th>
                    <th>Publisher</th>
                    <th>Copies</th>
                    <th>Options</th>
                </tr>
                <?php
                $counter = 1;
                foreach ($bookObj->viewBook($title, $genre) as $book) {
                    $msg = "Do you wish to delete the book " . $book["title"] . "?";
                ?>
                <tr>
                    <td><?= $counter++ ?></td>
                    <td><?= $book["title"] ?></td>
                    <td><?= $book["author"] ?></td>
                    <td><?= $book["genre"] ?></td>
                    <td><?= $book["publication_year"] ?? "-" ?></td>
                    <td><?= $book["publisher"] ?></td>
                    <td><?= $book["copies"] ?></td>
                    <td class="action-links">
                        <a href="editbook.php?id=<?= $book["id"] ?>">Edit</a>
                        <a href="deletebook.php?id=<?= $book["id"] ?>" onclick="return confirm('<?= $msg ?>')">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <div class="add-btn">
                <a href="addbook.php" class="btn">Add Book</a>
            </div>
        </div>

    </div>
</body>
</html>
