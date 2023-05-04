<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">Machine Details</h1>


<div class="row">
    <div class="col-xl-4 col-md-6 mb-4" >
        <!-- Details pannel -->
        <div class="card shadow mb-4" >
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Details</h6>
            </div>
            <div class="card-body">
                <?php 
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
                        die(sqlsrv_errors());
                    }

                    $rName = "";
                    try {
                        $conn = OpenConnection();
                        $sqlQry = "SELECT * FROM [Rate] WHERE Id = ". $mRateId;
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
                        die(sqlsrv_errors());
                    }
                ?>

                <p><strong>Guid: </strong><?=$mGuid?></p>
                <p><strong>Name: </strong><?=$mName?></p>
                <p><strong>Balance: </strong><?=$mBalance?></p>
                <p><strong>Accumulated Amount: </strong><?=$mAccumulateAmount?></p>
                <p><strong>Rate: </strong> <a href="rate.php?section=edit&id=<?=$mRateId?>" title="Rate details"><?=$rName?></a></p>
            </div>
        </div>
    </div>
    <div class="col-xl-8 col-md-6 mb-4" >
        <!-- Bill counter pannel -->
        <div class="card shadow mb-4">
            <a href="#billCounterPannelBody" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="billCounterPannelBody">
                <h6 class="m-0 font-weight-bold text-primary">Bill Counter</h6>
            </a>
            <div class="collapse show" id="billCounterPannelBody">
                <div class="card-body" >

                    <?php 
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

                    <div class="row">
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">50 Peso</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$bc50?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">100 Peso</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$bc100?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">200 Peso</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$bc200?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">500 Peso</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$bc500?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">1000 Peso</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?=$bc1000?></div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-money-bill-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>