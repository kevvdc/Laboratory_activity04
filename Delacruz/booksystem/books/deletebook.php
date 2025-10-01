<?php
require_once "../classes/book.php";
$bookObj = new Book();

$id = "";

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET["id"])) {
        $id = trim(htmlspecialchars($_GET["id"]));
        $book = $bookObj->fetchBook($id);

        if (!$book) {
            echo "<a href='viewbook.php'>View Books</a>";
            exit("Book Not Found");
        } else {
            $bookObj->deleteBook($id); 
            header("Location: viewbook.php");
        }
    } else {
        echo "<a href='viewbook.php'>View Books</a>";
        exit("Book Not Found");
    }
}
?>
