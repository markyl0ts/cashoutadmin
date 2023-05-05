<?php

function OpenConnection(){
    $serverName = "192.168.254.121,1433";
    $connectionOptions = array("Database"=>"CashOut",
        "Uid"=>"couser", "PWD"=>"qqQQ11!!");
    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if($conn == false)
        die(sqlsrv_errors());

    return $conn;
}

function db_status_to_word($status){
    switch($status){
        case 1: return "COMPLETED"; break;
        case 2: return "CANCELED"; break;
        default: return "PENDING";
    }
}

function get_base_url(){
    return "http://".$_SERVER['HTTP_HOST'].'/admin';
}

function in_open_session(){
    if(isset($_SESSION['isLoggedIn'])){
        header("Location: http://".$_SERVER['HTTP_HOST'].'/admin');
    }
}

function in_close_session(){
    if(!isset($_SESSION['isLoggedIn'])){
        header("Location: http://".$_SERVER['HTTP_HOST'].'/admin/login.php');
    }
}

function update_machine_balance($machineId, $bill50, $bill100, $bill200, $bill500, $bill1000){
    $total = 0;
    if($bill50 > 0)
        $total = $total + (50 * $bill50);
    
    if($bill100 > 0)
        $total = $total + (100 * $bill100);

    if($bill200 > 0)
        $total = $total + (200 * $bill200);

    if($bill500 > 0)
        $total = $total + (500 * $bill500);

    if($bill1000 > 0)
        $total = $total + (1000 * $bill1000);

    try {
        $conn = OpenConnection();
        if (sqlsrv_begin_transaction($conn) == FALSE){
            die(sqlsrv_errors());
        }

        $sqlQry = "UPDATE [System] SET [Balance] = ".$total." WHERE Id = ". $machineId;
        $exec = sqlsrv_query($conn, $sqlQry);

        if($exec){
            sqlsrv_commit($conn);
        }
        
        sqlsrv_free_stmt($exec);
        sqlsrv_close($conn);
    } catch(Exception $e){
        die(sqlsrv_errors());
    }
}

?>