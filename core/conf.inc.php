<?php

    /*
        Config Information
    */
    
    class Config{
        
        
        public $link;
        
        function __construct(){
            $this->link = null;
        }
        
       /* Send Debug to Java Console */
        function dbConsole($data) {
            $output = $data;
            if ( is_array( $output ) ){
                $output = implode( ',', $output);
            }

            echo "<script type='text/javascript'>console.log('Debug Objects: " . $output . "');</script>";
        }
        
    /* Database Connect Function */
        public function dbConnect($wprt = false){
            /* Global Variables */
            $dbhst = "localhost";
            $dbusrnm = "root";
            $dbpsw = "";
            $dbldb = "opmh";
            $dbprt = ""; /*Optional*/
            // Check if using custom port
            if ($wprt == false){
                // Connect
                $this->link = mysqli_connect($dbhst, $dbusrnm, $dbpsw, $dbldb);
            } else {
                // Connect with port
                $this->link = mysqli_connect($dbhst, $dbusrnm, $dbpsw, $dbldb, $dbprt);  
            }
            // Check if connect is working if not throw error
            if (!$this->link) {
                $this->dbConsole("Error: Unable to connect to MySQL.{PHP_EOL}");
                $this->dbConsole("Debugging errno: " . mysqli_connect_errno() . PHP_EOL);
                $this->dbConsole("Debugging error: " . mysqli_connect_error() . PHP_EOL);
                exit;
            }
        }
        
        
        /* Close Database Connect and return */
        public function dbCloseConnection(){
            if ($this->link == null){
                return;
            }
            mysqli_close($this->link);
        }
        
    }
    
?>