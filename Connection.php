<?php
class Connection {
    private $hostAddress = "localhost";
    private $dbUserName = "userName";
    private $dbPassWord = "passWord";
    private $dbName = "test";
    private $connState;
    
    function __construct($new_user, $new_pass){
        //$connQuery = mysql_connect($this->hostAddress, $this->dbUserName, $this->dbPassWord)
        //    or die(mysql_error());
        //$this->connState = TRUE;
        try {
            $dbh = new PDO('mysql:host=localhost;dbname=test', $new_user, $new_pass, array(
                                    PDO::ATTR_PERSISTENT => true));
            $this->set_user($new_user);
            $this->set_pass($new_pass);
            //foreach($dbh->query('SELECT * from FOO') as $row) {
            //     print_r($row);
            // }
            //$dbh = null; CLOSES CONNECTION
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    function get_connState(){
        return $this->connState;
    }
    function set_pass($new_pass){
        $this->dbPassWord = $new_pass;
    }
    function set_user($new_user){
        $this->dbUserName = $new_user;
    }
    function get_pass(){
        return $this->dbPassWord;
    }
    function get_user(){
        return $this->dbUserName;
    }
    
}
