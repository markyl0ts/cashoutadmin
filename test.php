<?php 

    $server = "localhost";
    $conVars = array("Database" => "CashOut", "UID" => "couser", "PWD" => "qqQQ11!!");
    $conn = sqlsrv_connect($server, $conVars);

    if( $conn ) {
        echo "YES";
    } else {
        echo "NO";
        die( print_r(sqlsrv_errors(), true));
    }

?>