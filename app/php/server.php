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
        $number = intval($num);
        if($number!=0)
        {
            $this -> sqlQuery = "SELECT id_book, title, author, year FROM book ORDER BY id_book DESC LIMIT $number";
            $this -> dataSet = mysql_query($this -> sqlQuery);
            if(!is_resource($this->dataSet))
            {
                die('Error: ' . mysql_error());
            }
            $temp = array();
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
        $number = intval($num);
        if($number!=0)
        {
            $this -> sqlQuery = "SELECT id_book, title, author, year FROM book ORDER BY likes_num ASC LIMIT $number";
            $this -> dataSet = mysql_query($this -> sqlQuery);
            if(!is_resource($this->dataSet))
            {
                die('Error: ' . mysql_error());
            }
            $temp = array();
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
        $id_book = intval($id);
        if($id_book!=0)
        {
            $this -> sqlQuery = "SELECT * FROM book WHERE id_book = $id_book";
            $this -> dataSet = mysql_query($this -> sqlQuery);
            if(!is_resource($this->dataSet))
            {
                die('Error: ' . mysql_error());
            }
            $temp = array();
            if (!mysql_num_rows($this -> dataSet) == 0) {
                while ($row = mysql_fetch_assoc($this -> dataSet)) {
                        array_push($temp,$row);
                }
                return json_encode($temp);
            }
            return NULL;
        }
    }

}
