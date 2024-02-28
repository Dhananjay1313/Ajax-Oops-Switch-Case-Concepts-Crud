<?php
class ab 
{   
    private $servername = 'localhost';
    private $username = 'root';
    private $password = '';
    private $dbname = 'ajaxoops';
    
    protected $con;
    
    public function __construct()
    {
        if (!isset($this->con)) {    
            $this->con = new mysqli($this->servername, $this->username, $this->password, $this->dbname);          
        }   
        return $this->con;
    }
}

class abc extends ab
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getdata($query)
    {       
        $result = $this->con->query($query);
        
        if ($result == false) {
            return false;
        } 
        
        $rows = array();
        
        while ($row = $result->fetch_assoc()){
            $rows[] = $row;
        }
        
        return $rows;
    }

    public function execute($query) 
    {
        $result = $this->con->query($query);
        
        if ($result == false) {
            echo 'Error: cannot execute the command';
            return false;
        } else {
            return true;
        }       
    }
}
?>