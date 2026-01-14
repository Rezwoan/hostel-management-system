<?php
  
    $host="localhost";
    $user="root";
    $password="";
    $dbName="smartHostel_db";
    $port=3306;

    function dbConnect()
    {
        global $host;
        global $user;
        global $password;
        global $dbName;
        global $port;
        $conn=mysqli_connect($host, $user, $password, $dbName, $port);

        if(!$conn)
        {
            echo mysqli_connect_error();
            //echo "not connected";
        }

        else
        {
            //echo "connection succefully establishe<br>";
            
            return $conn;
        }
    }

    

    

?>