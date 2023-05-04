
<?php 
    $formFlag = 0;
    $formMsg = "";
    $rName = "";
    $uri = $_SERVER['REQUEST_URI'];

    //-- Submit save
    if(isset($_POST['btnAdd'])){
        $rName = $_POST['frmName'];
        $action = (isset($_GET['section'])) ? $_GET['section'] : "";

        try {
            $conn = OpenConnection();
            if (sqlsrv_begin_transaction($conn) == FALSE){
                $formFlag = 2;
                $formMsg = "Failed to begin SQL Transaction";
            }

            if($action == "edit") {
                $sqlQry = "UPDATE [Rate] SET [Name] = '".$rName."' WHERE Id = ". $_GET['id'];
            } else {
                $sqlQry = "INSERT INTO [Rate]([Name]) VALUES('".$rName."')";
            }

            $exec = sqlsrv_query($conn, $sqlQry);

            if($exec){
                sqlsrv_commit($conn);
                $formFlag = 1;
                $formMsg = (isset($_GET['section'])) ? "Record updated successfuly" : "Record added successfuly";
            } else {
                $formFlag = 2;
                $formMsg = (isset($_GET['section'])) ? "Failed to update record" : "Failed to add record";
            }
            
            sqlsrv_free_stmt($exec);
            sqlsrv_close($conn);
        } catch(Exception $e){
            $formFlag = 2;
            $formMsg = $e->Message;
        }
    }

    if(isset($_GET['section'])){
        try {
            $conn = OpenConnection();
            $sqlQry = "SELECT * FROM [Rate] WHERE Id = ". $_GET['id'];
            $getRecord = sqlsrv_query($conn, $sqlQry);
            if ($getRecord == FALSE)
                die(FormatErrors(sqlsrv_errors()));

            while($row = sqlsrv_fetch_array($getRecord, SQLSRV_FETCH_ASSOC))
            {
                $rName = $row['Name'];
            }
            
            sqlsrv_free_stmt($getRecord);
            sqlsrv_close($conn);
        } catch(Exception $e){
            
        }
    }
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Rate Form</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="<?=$uri?>">
            <?php if($formFlag > 0){ ?>
                <div class="alert alert-<?=($formFlag == 1) ? "success" : "danger"?>" role="alert">
                    <?=$formMsg?>
                </div>
            <?php } ?>
            <div clas="row">
                <div class="form-group">
                    <label for="frmName">Name</label>
                    <input type="text" name="frmName" class="form-control" id="frmName" value="<?=$rName?>">
                </div>
                <div class="form-group">
                    <button type="submit" name="btnAdd" class="btn btn-primary btn-block" ><?=(isset($_GET['section']))? "Update" : "Add" ?></button>
                </div>
            </div>
        </form>
    </div>
</div>