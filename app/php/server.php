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
        $this -> connectionString   = NULL;
        $this -> sqlQuery           = NULL;
        $this -> dataSet            = NULL;
        $this -> db_server          = 'localhost';
        $this -> user_name          = 'root';
        $this -> user_pass          = '';        
        $this -> db_name            = 'db_library';
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
            $this -> sqlQuery = "SELECT * FROM book ORDER BY `id_book` DESC LIMIT $number";
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
            $this -> sqlQuery = "SELECT * FROM `book` ORDER BY `likes_num` ASC LIMIT $number";
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
            $this -> sqlQuery = "SELECT * FROM `book` WHERE `id_book` = $id_book";
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
            $this -> sqlQuery = "SELECT * FROM `user` WHERE `id_user` = $id_user";
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
            $this -> sqlQuery = "SELECT `book`.`id_book`, `book`.`title`, `book`.`author`, `book`.`category`, `book`.`year` FROM `book` WHERE `book`.`id_book` = (SELECT `liked`.`id_book` FROM `liked` WHERE `liked`.`id_user` = $id_user) ORDER BY `book`.`id_book`";
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
            $this -> sqlQuery = "SELECT * FROM `book` WHERE `category` = '".$category."'";
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
    function search($str){
        $temp = array();
        $result = array();
        $search_str = strtolower(mysql_real_escape_string($str));
        if($search_str!=NULL)
        {
            $this -> sqlQuery = "SELECT `id_book`, `title` FROM `book`";
            $this -> dataSet = mysql_query($this -> sqlQuery);
            if(!is_resource($this->dataSet))
            {
                die('Error: ' . mysql_error());
            }
            if (!mysql_num_rows($this -> dataSet) == 0) {
                while ($row = mysql_fetch_assoc($this -> dataSet)) {
                    $temp[$row['id_book']] = $row['title'];
                }
                foreach($temp as $key=>$value){
                    $pos = strpos(strtolower($value), $search_str);
                    if ($pos === false) {
                        unset($temp[$key]);
                    }   else{
                        $query = mysql_safequery("SELECT * FROM `book` WHERE `id_book`=?",array($key)) or die(mysql_error());
                        $row = mysql_fetch_assoc($query);
                        array_push($result,$row);
                    }
                }
                return json_encode($result);
            }
            return NULL;
        }
    }
    
    function activate(){
        die('activate function call');
//        $name = 'db_library';
//        mysql_query("CREATE DATABASE `".$name."`") or die(mysql_error());
//        mysql_query("CREATE TABLE `book` (
//                    `id_book` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//                    `title` VARCHAR(255) NOT NULL,
//                    `author` INT(10) NOT NULL,
//                    `category` INT(10) NOT NULL,
//                    `year` VARCHAR(255) NOT NULL,
//                    `info` TEXT NOT NULL,
//                    `photo` VARCHAR(255) NOT NULL,
//                    `likes_num` INT(10) NOT NULL DEFAULT 0
//                    )") or die(mysql_error());
//        mysql_query("CREATE TABLE `author` (
//                    `id_author` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//                    `name` VARCHAR(255) NOT NULL,
//                    `country` INT(10) NOT NULL,
//                    `info` TEXT NOT NULL
//                    )") or die(mysql_error());
//        mysql_query("CREATE TABLE `user` (
//                    `id_user` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//                    `email` VARCHAR(255) NOT NULL,
//                    `username` VARCHAR(255) NOT NULL,
//                    `country` INT(10) NOT NULL,
//                    `password` VARCHAR(255) NOT NULL
//                    )") or die(mysql_error());
//        mysql_query("CREATE TABLE `liked` (
//                    `id_book` INT(10) NOT NULL,
//                    `id_user` INT(10) NOT NULL
//                    )") or die(mysql_error());
//        mysql_query("CREATE TABLE `country` (
//                    `id_country` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//                    `name` VARCHAR(255) NOT NULL
//                    )") or die(mysql_error());
//        mysql_query("CREATE TABLE `category` (
//                    `id_category` INT(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//                    `name` VARCHAR(255) NOT NULL
//                    )") or die(mysql_error());
    }
    
    function insertBooks(){
//        mysql_safequery("INSERT INTO `user`(`email`, `username`, `country`, `password`) VALUES (?,?,?,?)",array(
//            'admin@example.com',
//            'admin',
//            1,
//            'p@ssw0rd'
//        )) or die(mysql_error());
//        
//        mysql_safequery("INSERT INTO `category`(`name`) VALUES (?)",array(
//            'Adventures'
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `category`(`name`) VALUES (?)",array(
//            'Comics & Graphic Novels'
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `category`(`name`) VALUES (?)",array(
//            'Drama'
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `category`(`name`) VALUES (?)",array(
//            'Fairy Tale'
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `category`(`name`) VALUES (?)",array(
//            'Poetry'
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `category`(`name`) VALUES (?)",array(
//            'Romance'
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `category`(`name`) VALUES (?)",array(
//            'Science & Education'
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `category`(`name`) VALUES (?)",array(
//            'Science Fiction'
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `category`(`name`) VALUES (?)",array(
//            'Technology & Engineering'
//        )) or die(mysql_error());
//        
//        
//        mysql_safequery("INSERT INTO `country`(`name`) VALUES (?)",array(
//            'United Kingdom',
//        )) or die(mysql_error());
//        
//        mysql_safequery("INSERT INTO `author`(`name`, `country`, `info`) VALUES (?,?,?)",array(
//            'J. K. Rowling',
//            1,
//            'British novelist best known as the author of the Harry Potter fantasy series.'
//        )) or die(mysql_error());
//        
//        mysql_safequery("INSERT INTO `book`(`title`, `author`, `category`, `year`, `info`, `photo`) VALUES (?,?,?,?,?,?)",array(
//            'Harry Potter and the Philosopher`s Stone',
//            1,
//            1,
//            '1997',
//            'First novel in the Harry Potter series and J. K. Rowling`s debut novel.',
//            'http://localhost/library/php/images/1.jpg',
//        )) or die(mysql_error());
//                mysql_safequery("INSERT INTO `book`(`title`, `author`, `category`, `year`, `info`, `photo`) VALUES (?,?,?,?,?,?)",array(
//            'Harry Potter and the Chamber of Secrets',
//            1,
//            1,
//            '1998',
//            'The second novel in the Harry Potter series, written by J. K. Rowling.',
//            'http://localhost/library/php/images/2.jpg',
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `book`(`title`, `author`, `category`, `year`, `info`, `photo`) VALUES (?,?,?,?,?,?)",array(
//            'Harry Potter and the Prisoner of Azkaban',
//            1,
//            1,
//            '1999',
//            'The third novel in the Harry Potter series, written by J. K. Rowling.',
//            'http://localhost/library/php/images/3.jpg',
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `book`(`title`, `author`, `category`, `year`, `info`, `photo`) VALUES (?,?,?,?,?,?)",array(
//            'Harry Potter and the Goblet of Fire',
//            1,
//            1,
//            '2000',
//            'The fourth novel in the Harry Potter series, written by British author J. K. Rowling.',
//            'http://localhost/library/php/images/4.jpg',
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `book`(`title`, `author`, `category`, `year`, `info`, `photo`) VALUES (?,?,?,?,?,?)",array(
//            'Harry Potter and the Order of the Phoenix',
//            1,
//            1,
//            '2003',
//            'The fifth novel in the Harry Potter series, written by J. K. Rowling.',
//            'http://localhost/library/php/images/6.jpg',
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `book`(`title`, `author`, `category`, `year`, `info`, `photo`) VALUES (?,?,?,?,?,?)",array(
//            'Harry Potter and the Half-Blood Prince',
//            1,
//            1,
//            '2005',
//            'The sixth and penultimate novel in the Harry Potter series, written by British author J. K. Rowling.',
//            'http://localhost/library/php/images/6.jpg',
//        )) or die(mysql_error());
//        mysql_safequery("INSERT INTO `book`(`title`, `author`, `category`, `year`, `info`, `photo`) VALUES (?,?,?,?,?,?)",array(
//            'Harry Potter and the Deathly Hallows',
//            1,
//            1,
//            '2007',
//            'The seventh and final novel of the Harry Potter series, written by British author J. K. Rowling.',
//            'http://localhost/library/php/images/7.jpg',
//        )) or die(mysql_error());
    }
}
function mysql_safequery($query,$params=false) {
    if ($params) {
        foreach ($params as &$v) { 
            $v = mysql_real_escape_string($v);
        }
        $sql_query = vsprintf( str_replace("?","'%s'",$query), $params );
        $sql_query = mysql_query($sql_query);
    } else {
        $sql_query = mysql_query($query);
    }
    return ($sql_query);
}

$s = new Server();
$s->dbConnect();
echo '<pre>';
echo $s->search('Philosopher');
$s->dbDisconnect();
unset($s);