
<?php 
    $formFlag = 0;
    $formMsg = "";

    //-- Submit save
    if(isset($_POST['btnAdd'])){
        $mName = $_POST['frmName'];
        $mRateId = $_POST['frmRate']; 

        try {
            $conn = OpenConnection();
            if (sqlsrv_begin_transaction($conn) == FALSE){
                $detailsIsError = 2;
                $detailsFormMsg = "Failed to begin SQL Transaction";
            }

            $sqlQry = "INSERT INTO [System]([Name],[Balance],[RateId],[AccumulatedAmount]) VALUES('".$mName."',0,".$mRateId.",0)";
            $exec = sqlsrv_query($conn, $sqlQry);

            if($exec){
                sqlsrv_commit($conn);
                $formFlag = 1;
                $formMsg = "Record added successfuly";
            } else {
                $formFlag = 2;
                $formMsg = "Failed to add record";
            }
            
            sqlsrv_free_stmt($exec);
            sqlsrv_close($conn);
        } catch(Exception $e){
            $formFlag = 2;
            $formMsg = $e->Message;
        }
    }
?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Machine Add Form</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="machine.php">
            <?php if($formFlag > 0){ ?>
                <div class="alert alert-<?=($formFlag == 1) ? "success" : "danger"?>" role="alert">
                    <?=$formMsg?>
                </div>
            <?php } ?>
            <div clas="row">
                <div class="form-group">
                    <label for="frmName">Name</label>
                    <input type="text" name="frmName" class="form-control" id="frmName" >
                </div>
                <div class="form-group">
                    <label for="frmRate">Rate</label>
                    <select name="frmRate" class="form-control" id="frmRate">
                        <?php 
                            try {
                                $conn = OpenConnection();
                                $sqlQry = "SELECT * FROM [Rate]";
                                $getRecord = sqlsrv_query($conn, $sqlQry);
                                if ($getRecord == FALSE)
                                    die(FormatErrors(sqlsrv_errors()));

                                while($row = sqlsrv_fetch_array($getRecord, SQLSRV_FETCH_ASSOC))
                                {
                                    echo "<option value='".$row['Id']."'>".$row['Name']."</option>";
                                }
                                
                                sqlsrv_free_stmt($getRecord);
                                sqlsrv_close($conn);
                            } catch(Exception $e){
                                die(sqlsrv_errors());
                            }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <button type="submit" name="btnAdd" class="btn btn-primary btn-block" >Add</button>
                </div>
            </div>
        </form>
    </div>
</div>