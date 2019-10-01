<?php

class AdminUsers {
 
    // database connection and table name
    private $conn;
    private $table_name = "tuckshop_admin_user";
 
    // constructor with $db as database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    function getUserByEmail($email = '') {
        $query = "SELECT * FROM " . $this->table_name . " where is_active = 1 and email = '".$email."'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }    
}