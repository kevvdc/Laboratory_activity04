<?php
require_once "../classes/book.php";
$bookObj = new Book();

$book = [];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book["title"] = trim(htmlspecialchars($_POST["title"]));
    $book["author"] = trim(htmlspecialchars($_POST["author"]));
    $book["genre"] = trim(htmlspecialchars($_POST["genre"]));
    $book["publication_year"] = trim(htmlspecialchars($_POST["publication_year"]));
    $book["publisher"] = trim(htmlspecialchars($_POST["publisher"]));
    $book["copies"] = trim(htmlspecialchars($_POST["copies"]));


    if (empty($book["title"])) $errors["title"] = "Title is required";
    if (empty($book["author"])) $errors["author"] = "Author is required";
    if (empty($book["genre"])) $errors["genre"] = "Genre is required";
    if (empty($book["publication_year"])) {
        $errors["publication_year"] = "Publication year is required";
    } elseif ($book["publication_year"] > date("Y")) {
        $errors["publication_year"] = "Year cannot be in the future";
    }
    if (empty($book["copies"]) || $book["copies"] < 1) {
        $errors["copies"] = "Copies must be at least 1";
    }

    if ($bookObj->doesBookExist($book["title"]) && !$bookObj->isBookDeleted($book["title"])) {
        $errors["title"] = "This book already exists in the database";
    }


    if (empty(array_filter($errors))) {
        $bookObj->title = $book["title"];
        $bookObj->author = $book["author"];
        $bookObj->genre = $book["genre"];
        $bookObj->publication_year = $book["publication_year"];
        $bookObj->publisher = $book["publisher"];
        $bookObj->copies = $book["copies"];

        if ($bookObj->addBook())
            header("Location: viewbook.php");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Add Book</h1>
            <form method="post">
                <label>Title *</label>
                <input type="text" name="title" value="<?= $book["title"] ?? "" ?>">
                <p class="error"><?= $errors["title"] ?? "" ?></p>

                <label>Author *</label>
                <input type="text" name="author" value="<?= $book["author"] ?? "" ?>">
                <p class="error"><?= $errors["author"] ?? "" ?></p>

                <label>Genre *</label>
                <select name="genre">
                    <option value="">--Select--</option>
                    <option value="history" <?= (isset($book["genre"]) && $book["genre"]=="history")?"selected":"" ?>>History</option>
                    <option value="science" <?= (isset($book["genre"]) && $book["genre"]=="science")?"selected":"" ?>>Science</option>
                    <option value="fiction" <?= (isset($book["genre"]) && $book["genre"]=="fiction")?"selected":"" ?>>Fiction</option>
                </select>
                <p class="error"><?= $errors["genre"] ?? "" ?></p>

                <label>Publication Year</label>
                <input type="number" name="publication_year" value="<?= $book["publication_year"] ?? "" ?>">
                <p class="error"><?= $errors["publication_year"] ?? "" ?></p>

                <label>Publisher</label>
                <input type="text" name="publisher" value="<?= $book["publisher"] ?? "" ?>">

                <label>Copies *</label>
                <input type="number" name="copies" value="<?= $book["copies"] ?? 1 ?>">
                <p class="error"><?= $errors["copies"] ?? "" ?></p>

                <input type="submit" class="btn" value="Save Book">
                <a href="viewbook.php" class="btn">View Books</a>
            </form>
        </div>
    </div>
</body>
</html>

