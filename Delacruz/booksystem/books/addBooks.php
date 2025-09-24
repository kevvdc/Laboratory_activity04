<?php

require_once "../classes/book.php";

$bookObj = new Book();

$book = [];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book["title"] = trim(htmlspecialchars($_POST["title"] ?? ""));
    $book["author"] = trim(htmlspecialchars($_POST["author"] ?? ""));
    $book["genre"] = trim(htmlspecialchars($_POST["genre"] ?? ""));
    $book["publication_year"] = trim(htmlspecialchars($_POST["publication_year"] ?? ""));
    $book["publisher"] = trim(htmlspecialchars($_POST["publisher"] ?? ""));
    $book["copies"] = trim(htmlspecialchars($_POST["copies"] ?? ""));

    // Validation
    if (empty($book["title"])) {
        $errors["title"] = "Title is required";
    }

    if (empty($book["author"])) {
        $errors["author"] = "Author is required";
    }

    if (empty($book["genre"])) {
        $errors["genre"] = "Genre is required";
    }

    if (empty($book["publication_year"])) {
        $errors["publication_year"] = "Publication year is required";
    } elseif (!filter_var($book["publication_year"], FILTER_VALIDATE_INT)) {
        $errors["publication_year"] = "Invalid publication year";
    } elseif ($book["publication_year"] > date("Y")) {
        $errors["publication_year"] = "Publication year cannot be in the future";
    }

    if (empty($book["publisher"])) {
        $errors["publisher"] = "Publisher is required";
    }

    if (empty($book["copies"])) {
        $errors["copies"] = "Copies is required";
    } elseif (!filter_var($book["copies"], FILTER_VALIDATE_INT) || $book["copies"] < 1) {
        $errors["copies"] = "Copies must be a positive integer";
    }

    // If no errors → Save book
    if (empty(array_filter($errors))) {
        try {
            $db = new Library();
            $conn = $db->connect();

            $stmt = $conn->prepare("INSERT INTO book (title, author, genre, publication_year, publisher, copies) 
                                    VALUES (:title, :author, :genre, :publication_year, :publisher, :copies)");
            $stmt->bindParam(":title", $book["title"]);
            $stmt->bindParam(":author", $book["author"]);
            $stmt->bindParam(":genre", $book["genre"]);
            $stmt->bindParam(":publication_year", $book["publication_year"], PDO::PARAM_INT);
            $stmt->bindParam(":publisher", $book["publisher"]);
            $stmt->bindParam(":copies", $book["copies"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: viewBooks.php");
                exit();
            } else {
                echo "Error saving book.";
            }
        } catch (PDOException $e) {
            echo "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
    <link rel="stylesheet" href="style.css">
    <style>
        p.error { color: red; margin: 0; }
    </style>
</head>
<body>
    <div class="sty"></div>
    <div class="container">
        <h2>Add Book</h2>
        <form method="POST">
            <input type="text" name="title" placeholder="Title" 
                value="<?= $book["title"] ?? "" ?>">
            <p class="error"><?= $errors["title"] ?? "" ?></p>
            <br>

            <input type="text" name="author" placeholder="Author" 
                value="<?= $book["author"] ?? "" ?>">
            <p class="error"><?= $errors["author"] ?? "" ?></p>
            <br>

            <select name="genre" id="genre">
                <option value="">Select Genre</option>
                <option value="history" <?= (isset($book["genre"]) && strtolower($book["genre"])=='history') ? "selected" : "" ?>>History</option>
                <option value="science" <?= (isset($book["genre"]) && strtolower($book["genre"])=='science') ? "selected" : "" ?>>Science</option>
                <option value="fiction" <?= (isset($book["genre"]) && strtolower($book["genre"])=='fiction') ? "selected" : "" ?>>Fiction</option>
            </select>
            <p class="error"><?= $errors["genre"] ?? "" ?></p>
            <br>

            <input type="number" name="publication_year" placeholder="Publication Year" 
                value="<?= $book["publication_year"] ?? "" ?>">
            <p class="error"><?= $errors["publication_year"] ?? "" ?></p>
            <br>

            <input type="text" name="publisher" placeholder="Publisher" 
                value="<?= $book["publisher"] ?? "" ?>">
            <p class="error"><?= $errors["publisher"] ?? "" ?></p>
            <br>

            <input type="number" name="copies" placeholder="Copies" min="1"
                value="<?= $book["copies"] ?? "1" ?>">
            <p class="error"><?= $errors["copies"] ?? "" ?></p>
            <br>

            <input type="submit" value="Add Book">
        </form>
        <button><a href="viewBooks.php">View Books</a></button>
    </div>
    <br>
    
</body>
</html>
