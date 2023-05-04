
<?php 
    $formFlag = 0;
    $formMsg = "";
    $uri = $_SERVER['REQUEST_URI'];
    $rrStart = "";
    $rrEnd = "";
    $rrFee = "";

    //-- Submit save
    if(isset($_POST['btnAdd'])){
        $rrStart = $_POST['frmStart'];
        $rrEnd = $_POST['frmEnd'];
        $rrFee = $_POST['frmFee'];
        $action = $_GET['action'];

        try {
            $conn = OpenConnection();
            if (sqlsrv_begin_transaction($conn) == FALSE){
                $formFlag = 2;
                $formMsg = "Failed to begin SQL Transaction";
            }

            if($action == "edit") {
                $sqlQry = "UPDATE [RateRange] SET [StartRange] = ".$rrStart.", [EndRange] = ".$rrEnd.", [Fee] = ".$rrFee." WHERE Id = ". $_GET['id'];
            } else {
                $sqlQry = "INSERT INTO [RateRange]([RateId],[StartRange],[EndRange],[Fee]) VALUES(".$_GET['rid'].",".$rrStart.", ".$rrEnd.", ".$rrFee.")";
            }

            $exec = sqlsrv_query($conn, $sqlQry);

            if($exec){
                sqlsrv_commit($conn);
                $formFlag = 1;
                $formMsg = ($_GET['section'] == 'edit') ? "Record updated successfuly" : "Record added successfuly";
            } else {
                $formFlag = 2;
                $formMsg = ($_GET['section'] == 'edit') ? "Failed to update record" : "Failed to add record";
            }
            
            sqlsrv_free_stmt($exec);
            sqlsrv_close($conn);
        } catch(Exception $e){
            $formFlag = 2;
            $formMsg = $e->Message;
        }
    }

    if(isset($_GET['action'])){
        if($_GET['action'] == 'edit'){
            try {
                $conn = OpenConnection();
                $sqlQry = "SELECT * FROM [RateRange] WHERE Id = ". $_GET['id'];
                $getRecord = sqlsrv_query($conn, $sqlQry);
                if ($getRecord == FALSE)
                    die(FormatErrors(sqlsrv_errors()));
    
                while($row = sqlsrv_fetch_array($getRecord, SQLSRV_FETCH_ASSOC))
                {
                    $rrStart = $row['StartRange'];
                    $rrEnd = $row['EndRange'];
                    $rrFee = $row['Fee'];
                }
                
                sqlsrv_free_stmt($getRecord);
                sqlsrv_close($conn);
            } catch(Exception $e){
                
            }
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
                    <label for="frmStart">Start</label>
                    <input type="text" name="frmStart" class="form-control" id="frmStart" value="<?=$rrStart?>">
                </div>
                <div class="form-group">
                    <label for="frmEnd">End</label>
                    <input type="text" name="frmEnd" class="form-control" id="frmEnd" value="<?=$rrEnd?>">
                </div>
                <div class="form-group">
                    <label for="frmFee">Fee</label>
                    <input type="text" name="frmFee" class="form-control" id="frmFee" value="<?=$rrFee?>">
                </div>
                <div class="form-group">
                    <button type="submit" name="btnAdd" class="btn btn-primary btn-block" ><?=($_GET['action'] == 'edit')? "Update" : "Add" ?></button>
                </div>
            </div>
        </form>
    </div>
</div>