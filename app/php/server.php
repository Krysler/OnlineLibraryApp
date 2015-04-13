<?php

class Server    {
    public $connectionString;
    public $dataSet;
    private $sqlQuery;
    protected $db_server;
    protected $user_name;
    protected $user_pass;
    protected $db_name;
    function __construct()    {
        $this -> connectionString = NULL;
        $this -> sqlQuery = NULL;
        $this -> dataSet = NULL;
        $this -> db_server = 'localhost';
        $this -> user_name = 'library_admin';
        $this -> user_pass = 'FjJ9XWfX5atRB5D7';
        $this -> db_name = 'db_library';
    }
    function dbConnect()    {
        $this -> connectionString = mysql_connect($this -> db_server,$this -> user_name,$this -> user_pass);
        if (!$this -> connectionString)
        {
            die('Error: ' . mysql_error());
        }
        mysql_select_db($this -> db_name,$this -> connectionString);
        if (!mysql_select_db($this -> db_name,$this -> connectionString))
        {
            die('Error: ' . mysql_error());
        }
        return $this -> connectionString;
    }
    function dbDisconnect() {
        mysql_close($this -> connectionString);
        $this -> connectionString = NULL;
        $this -> sqlQuery = NULL;
        $this -> dataSet = NULL;
                $this -> db_name = NULL;
                $this -> db_server = NULL;
                $this -> user_name = NULL;
                $this -> user_pass = NULL;
    }

    function getLastAddedBooks($num){
        $temp = array();
        $number = intval($num);
        if($number!=0)
        {
            $this -> sqlQuery = "SELECT book.id_book, book.title, book.author, book.category, book.year, book.photo, book.likes_num FROM book ORDER BY id_book DESC LIMIT $number";
            $this -> dataSet = mysql_query($this -> sqlQuery);
            if(!is_resource($this->dataSet))
            {
                die('Error: ' . mysql_error());
            }
            if (!mysql_num_rows($this -> dataSet) == 0) {
                while ($row = mysql_fetch_assoc($this -> dataSet)) {
                        array_push($temp,$row);
                }
                return json_encode($temp);
            }
            return NULL;
        }
    }
    function getMostLikedBooks($num){
        $temp = array();
        $number = intval($num);
        if($number!=0)
        {
            $this -> sqlQuery = "SELECT book.id_book, book.title, book.author, book.category, book.year, book.photo, book.likes_num FROM book ORDER BY likes_num ASC LIMIT $number";
            $this -> dataSet = mysql_query($this -> sqlQuery);
            if(!is_resource($this->dataSet))
            {
                die('Error: ' . mysql_error());
            }
            if (!mysql_num_rows($this -> dataSet) == 0) {
                while ($row = mysql_fetch_assoc($this -> dataSet)) {
                        array_push($temp,$row);
                }
                return json_encode($temp);
            }
            return NULL;
        }
    }
    function getInfoBook($id){
        $temp = array();
        $id_book = intval($id);
        if($id_book!=0)
        {
            $this -> sqlQuery = "SELECT * FROM book WHERE id_book = $id_book";
            $this -> dataSet = mysql_query($this -> sqlQuery);
            if(!is_resource($this->dataSet))
            {
                die('Error: ' . mysql_error());
            }
            if (!mysql_num_rows($this -> dataSet) == 0) {
                while ($row = mysql_fetch_assoc($this -> dataSet)) {
                        array_push($temp,$row);
                }
                return json_encode($temp);
            }
            return NULL;
        }
    }
    function getInfoUser($id){
        $temp = array();
        $id_user = intval($id);
        if($id_user!=0)
        {
            $this -> sqlQuery = "SELECT * FROM user WHERE id_user = $id_user";
            $this -> dataSet = mysql_query($this -> sqlQuery);
            if(!is_resource($this->dataSet))
            {
                die('Error: ' . mysql_error());
            }
            if (!mysql_num_rows($this -> dataSet) == 0) {
                while ($row = mysql_fetch_assoc($this -> dataSet)) {
                        array_push($temp,$row);
                }
                return json_encode($temp);
            }
            return NULL;
        }
    }
    function getUserLikes($id){
        $temp = array();
        $id_user = intval($id);
        if($id_user!=0)
        {
            //$this -> sqlQuery = "SELECT book.id_book, book.title, book.author, book.category, book.year FROM book INNER JOIN liked ON liked.id_book = book.id_book WHERE liked.id_user = $id_user ORDER BY book.id_book";
            $this -> sqlQuery = "SELECT book.id_book, book.title, book.author, book.category, book.year FROM book WHERE book.id_book = (SELECT liked.id_book FROM liked WHERE liked.id_user = $id_user) ORDER BY book.id_book";
            $this -> dataSet = mysql_query($this -> sqlQuery);
            if(!is_resource($this->dataSet))
            {
                die('Error: ' . mysql_error());
            }
            if (!mysql_num_rows($this -> dataSet) == 0) {
                while ($row = mysql_fetch_assoc($this -> dataSet)) {
                        array_push($temp,$row);
                }
                return json_encode($temp);
            }
            return NULL;
        }
    }
    function getBooksByCategory($str){
        $temp = array();
        $category = mysql_real_escape_string($str);
        if($category!=NULL)
        {
            $this -> sqlQuery = "SELECT * FROM book WHERE category = '".$category."'";
            $this -> dataSet = mysql_query($this -> sqlQuery);
            if(!is_resource($this->dataSet))
            {
                die('Error: ' . mysql_error());
            }
            if (!mysql_num_rows($this -> dataSet) == 0) {
                while ($row = mysql_fetch_assoc($this -> dataSet)) {
                        array_push($temp,$row);
                }
                return json_encode($temp);
            }
            return NULL;
        }
    }
    function serach($str){
        $temp = array();
        $search_str = mysql_real_escape_string($str);
        if($search_str!=NULL)
        {
            $this -> sqlQuery = "SELECT id_book, title FROM book";
            $this -> dataSet = mysql_query($this -> sqlQuery);
            if(!is_resource($this->dataSet))
            {
                die('Error: ' . mysql_error());
            }
            if (!mysql_num_rows($this -> dataSet) == 0) {
                while ($row = mysql_fetch_assoc($this -> dataSet)) {
                        array_push($temp,$row);
                }
                $flag = false;
                $this -> sqlQuery = "SELECT * FROM book WHERE id_book in (";
                foreach ($temp as $key => $value) {
                    $words = explode(" ", $value['title']);
                    foreach ($words as $key_w => $value_w) {
                        if(strcasecmp($value_w, $search_str) == 0)
                        {
                            $this -> sqlQuery .= "'".$value['id_book']."',";
                            $flag = true;
                        }
                    } 
                }
                $this -> sqlQuery = rtrim($this -> sqlQuery, ",");
                $this -> sqlQuery .= ")";
                if(!$flag)
                {
                    unset($temp);
                    $temp = array();
                    return json_encode($temp);
                }
                $this -> dataSet = mysql_query($this -> sqlQuery);
                if(!is_resource($this->dataSet))
                {
                    die('Error: ' . mysql_error());
                }
                if (!mysql_num_rows($this -> dataSet) == 0) 
                {
                    unset($temp);
                    $temp = array();
                    while ($row = mysql_fetch_assoc($this -> dataSet)) {
                        array_push($temp,$row);
                    }
                }
                return json_encode($temp);
            }
            return NULL;
        }
    }

}
$s = new Server();
$s->dbConnect();

echo $s->serach('Moby');

$s->dbDisconnect();
unset($s);