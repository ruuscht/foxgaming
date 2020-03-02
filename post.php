<?php
class GBPost {
    private $databaseHandler;
    private $order = "desc";
    private $posts;

    public function __construct($dbh) {
        
        $this->databaseHandler = $dbh;

    }

    public function fetchAll(){
        
        $sql="SELECT id, Title, Description, Published, postimages from posts";
        $return_array= $this->databaseHandler->query($sql);
        if($return_array){
            return $this->posts=$return_array->fetchAll(PDO::FETCH_ASSOC);
        }else{
            echo "Error! No content!";
        }
    }
}




?>