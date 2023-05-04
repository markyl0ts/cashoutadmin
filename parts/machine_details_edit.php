<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Edit Details</h1>

<?php
    $detailsIsError = 0;
    $detailsFormMsg = "";

    //-- Submit save details
    if(isset($_POST['btnSaveDetails'])){
        $mName = $_POST['frmName'];
        $mRateId = $_POST['frmRate']; 
        $mAccumulateAmount = $_POST['frmAcAmount']; 

        try {
            $conn = OpenConnection();
            if (sqlsrv_begin_transaction($conn) == FALSE){
                $detailsIsError = 2;
                $detailsFormMsg = "Failed to begin SQL Transaction";
            }

            $sqlQry = "UPDATE [System] SET [Name] = '".$mName."', RateId = ".$mRateId.", AccumulatedAmount = ".$mAccumulateAmount." WHERE Id = ". $_GET['id'];
            $exec = sqlsrv_query($conn, $sqlQry);

            if($exec){
                sqlsrv_commit($conn);
                $detailsIsError = 1;
                $detailsFormMsg = "Record updated successfuly";
            } else {
                $detailsIsError = 2;
                $detailsFormMsg = "Failed to update record";
            }
            
            sqlsrv_free_stmt($exec);
            sqlsrv_close($conn);
        } catch(Exception $e){
            $detailsIsError = 2;
            $detailsFormMsg = $e->Message;
        }
    }

    $mName = "";
    $mGuid = ""; 
    $mBalance = 0;
    $mRateId = 0; 
    $mAccumulateAmount = 0; 

    try {
        $conn = OpenConnection();
        $sqlQry = "SELECT * FROM [System] WHERE Id = ". $_GET['id'];
        $getRecord = sqlsrv_query($conn, $sqlQry);
        if ($getRecord == FALSE)
            die(FormatErrors(sqlsrv_errors()));

        while($row = sqlsrv_fetch_array($getRecord, SQLSRV_FETCH_ASSOC))
        {
            $mName = $row['Name'];
            $mGuid = $row['guid'];
            $mBalance = $row['Balance'];
            $mRateId = $row['RateId'];
            $mAccumulateAmount = $row['AccumulatedAmount'];
        }
        
        sqlsrv_free_stmt($getRecord);
        sqlsrv_close($conn);
    } catch(Exception $e){
        $detailsIsError = 2;
        $detailsFormMsg = $e->Message;
    }
?>

<div class="row">
    <div class="col-xl-6 col-md-6 mb-4" >
        <div class="card shadow mb-4" >
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Details</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="machine.php?section=edit&id=<?=$_GET['id']?>" >
                    <?php if($detailsIsError > 0){ ?>
                        <div class="alert alert-<?=($detailsIsError == 1) ? "success" : "danger"?>" role="alert">
                            <?=$detailsFormMsg?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="frmName">Name</label>
                        <input type="text" name="frmName" class="form-control" id="frmName" value="<?=$mName?>" >
                    </div>
                    <div class="form-group">
                        <label for="frmAcAmount">Accumulated Amount</label>
                        <input type="text" name="frmAcAmount" class="form-control" id="frmAcAmount" value="<?=$mAccumulateAmount?>">
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
                                        echo "<option value='".$row['Id']."' ".(($mRateId == $row['Id']) ? "selected" : "").">".$row['Name']."</option>";
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
                        <button type="submit" name="btnSaveDetails" class="btn btn-primary btn-block" >Save Details</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 mb-4" >
        <div class="card shadow mb-4" >
            <?php 
            
            $bcFormFlag = 0;
            $bcFormMsg = "";

            //-- Submit save bills
            if(isset($_POST['btnSaveBills'])){
                $bc50 = $_POST['frm50'];
                $bc100 = $_POST['frm100'];
                $bc200 = $_POST['frm200'];
                $bc500 = $_POST['frm500'];
                $bc1000 = $_POST['frm1000'];

                try {
                    $conn = OpenConnection();
                    if (sqlsrv_begin_transaction($conn) == FALSE){
                        $bcFormFlag = 2;
                        $bcFormMsg = "Failed to begin SQL Transaction";
                    }

                    $sqlQry = "UPDATE [BillCounter] SET [50Bill] = '".$bc50."', [100Bill] = ".$bc100.", [200Bill] = ".$bc200.", [500Bill] = ".$bc500.", [1000Bill] = ".$bc1000." WHERE SystemId = ". $_GET['id'];
                    $exec = sqlsrv_query($conn, $sqlQry);

                    if($exec){
                        sqlsrv_commit($conn);
                        $bcFormFlag = 1;
                        $bcFormMsg = "Record updated successfuly";
                    } else {
                        $bcFormFlag = 2;
                        $bcFormMsg = "Failed to update record";
                    }
                    
                    sqlsrv_free_stmt($exec);
                    sqlsrv_close($conn);

                    update_machine_balance($_GET['id'], $bc50, $bc100, $bc200, $bc500, $bc1000);
                } catch(Exception $e){
                    $bcFormFlag = 2;
                    $bcFormMsg = $e->Message;
                }
            }

            $bc50 = 0;
            $bc100 = 0;
            $bc200 = 0;
            $bc500 = 0;
            $bc1000 = 0;
            
            try {
                $conn = OpenConnection();
                $sqlQry = "SELECT * FROM [BillCounter] WHERE SystemId = ". $_GET['id'];
                $getRecord = sqlsrv_query($conn, $sqlQry);
                if ($getRecord == FALSE)
                    die(FormatErrors(sqlsrv_errors()));

                while($row = sqlsrv_fetch_array($getRecord, SQLSRV_FETCH_ASSOC))
                {
                    $bc50 = $row['50Bill'];
                    $bc100 = $row['100Bill'];
                    $bc200 = $row['200Bill'];
                    $bc500 = $row['500Bill'];
                    $bc1000 = $row['1000Bill'];
                }
                
                sqlsrv_free_stmt($getRecord);
                sqlsrv_close($conn);
            } catch(Exception $e){
                $detailsIsError = 2;
                $detailsFormMsg = $e->Message;
            }
            ?>

            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Bill Counter</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="machine.php?section=edit&id=<?=$_GET['id']?>" >
                    <?php if($bcFormFlag > 0){ ?>
                        <div class="alert alert-<?=($bcFormFlag == 1) ? "success" : "danger"?>" role="alert">
                            <?=$bcFormMsg?>
                        </div>
                    <?php } ?>
                    <div class="form-group">
                        <label for="frm50">50 Bills</label>
                        <input type="text" name="frm50" class="form-control" id="frm50" value="<?=$bc50?>" >
                    </div>
                    <div class="form-group">
                        <label for="frm100">100 Bills</label>
                        <input type="text" name="frm100" class="form-control" id="frm100" value="<?=$bc100?>" >
                    </div>
                    <div class="form-group">
                        <label for="frm200">200 Bills</label>
                        <input type="text" name="frm200" class="form-control" id="frm200" value="<?=$bc200?>" >
                    </div>
                    <div class="form-group">
                        <label for="frm500">500 Bills</label>
                        <input type="text" name="frm500" class="form-control" id="frm500" value="<?=$bc500?>" >
                    </div>
                    <div class="form-group">
                        <label for="frm1000">1000 Bills</label>
                        <input type="text" name="frm1000" class="form-control" id="frm1000" value="<?=$bc1000?>" >
                    </div>
                    <div class="form-group">
                        <button type="submit" name="btnSaveBills" class="btn btn-primary btn-block" >Save Bills</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>