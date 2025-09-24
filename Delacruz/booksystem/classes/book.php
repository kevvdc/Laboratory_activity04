<?php

include "database.php";

class Book extends Library {
    public $id;
    public $title = "";
    public $author = "";
    public $genre = "";
    public $publication_year = "";
    public $publisher = "";
    public $copies = "";

    protected $db;

    public function __construct() {
        $this->db = new Library();
    }

    public function addBook() {
        $sql = "INSERT INTO book (title, author, genre, publication_year, publisher, copies)
        VALUES (:title, :author, :genre, :publication_year, :publisher, :copies)";

        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(":title", $this->title);
        $query->bindParam(":author", $this->author);
        $query->bindParam(":genre", $this->genre);
        $query->bindParam(":publication_year", $this->publication_year);
        $query->bindParam(":publisher", $this->publisher);
        $query->bindParam(":copies", $this->copies);
        return $query->execute();
    }

    public function viewBooks($title = "", $genre = "") {
        $sql = "SELECT * FROM book WHERE title LIKE CONCAT('%', :title, '%') AND genre LIKE CONCAT('%', :genre, '%') ORDER BY title ASC";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(":title", $title);
        $query->bindParam(":genre", $genre);
        
        if ($query->execute()) {
            return $query->fetchAll(); // fetch as associative array
        } else {
            return null;
        }
    }

    public function isBookExist($ptitle){
        $sql = "SELECT COUNT(*) as total FROM book WHERE title = :title";
        $query = $this->db->connect()->prepare($sql);
        $query->bindParam(":title", $ptitle);
        $record = null;
        if ($query->execute()) {
            $record = $query->fetch();
        }
        return ($record & $record["total"] > 0);
    }
}
