<?php
require_once "database.php";

class Book extends Database
{
    public $title = "";
    public $author = "";
    public $genre = "";
    public $publication_year = "";
    public $publisher = "";
    public $copies = 1;
    public $deleted = 0;


    public function addBook()
    {
        $sql = "INSERT INTO book (title, author, genre, publication_year, publisher, copies) 
                VALUES (:title, :author, :genre, :publication_year, :publisher, :copies)";
        $query = $this->connect()->prepare($sql);

        $query->bindParam(":title", $this->title);
        $query->bindParam(":author", $this->author);
        $query->bindParam(":genre", $this->genre);
        $query->bindParam(":publication_year", $this->publication_year);
        $query->bindParam(":publisher", $this->publisher);
        $query->bindParam(":copies", $this->copies);

        return $query->execute();
    }

   
    public function viewBook($title="", $author="", $genre="")
    {
        $sql = "SELECT * FROM book 
                WHERE title LIKE CONCAT('%', :title, '%') 
                AND genre LIKE CONCAT('%', :genre, '%')
                ORDER BY title ASC";
        $query = $this->connect()->prepare($sql);

        $query->bindParam(":title", $title);
        $query->bindParam(":author", $author);
        $query->bindParam(":genre", $genre);

        if ($query->execute())
            return $query->fetchAll();
        else
            return null;
    }

 
    public function doesBookExist($title, $id="")
    {
        $sql = "SELECT COUNT(*) as total FROM book WHERE title = :title AND id <> :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":title", $title);
        $query->bindParam(":id", $id);

        if ($query->execute())
            $result = $query->fetch();
        else
            return false;

        return $result["total"] > 0 ? true : false;
    }


    public function isBookDeleted($title)
    {
        $sql = "SELECT COUNT(*) as total_delete FROM book WHERE title = :title AND deleted = 1";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":title", $title);

        if ($query->execute())
            $result = $query->fetch();
        else
            return false;

        return $result["total_delete"] > 0 ? true : false;
    }


    public function fetchBook($id)
    {
        $sql = "SELECT * FROM book WHERE id = :id";
        $query = $this->connect()->prepare($sql);
        $query->bindParam(":id", $id);

        if ($query->execute())
            return $query->fetch();
        else
            return null;
    }


    public function editBook($id)
    {
        $sql = "UPDATE book SET title=:title, author=:author, genre=:genre, 
                publication_year=:publication_year, publisher=:publisher, copies=:copies 
                WHERE id=:id";

        $query = $this->connect()->prepare($sql);

        $query->bindParam(":title", $this->title);
        $query->bindParam(":author", $this->author);
        $query->bindParam(":genre", $this->genre);
        $query->bindParam(":publication_year", $this->publication_year);
        $query->bindParam(":publisher", $this->publisher);
        $query->bindParam(":copies", $this->copies);
        $query->bindParam(":id", $id);

        return $query->execute();
    }


    public function deleteBook($id)
{
    $sql = "DELETE FROM book WHERE id = :id";
    $query = $this->connect()->prepare($sql);
    $query->bindParam(":id", $id);

    return $query->execute();
}
}
