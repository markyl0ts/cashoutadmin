<!-- Custom styles for this page -->
<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

<div class="row">
    <div class="col-xl-3 col-md-6">
        <?php include('parts/rate_add.php'); ?>
    </div>
    <div class="col-xl-9 col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Rates</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="35%" >Name</th>
                                <th>Rate Range</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Rate Range</th>
                                <th>Actions</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            <?php
                                
                                try {
                                    $conn = OpenConnection();
                                    $query = "
                                            SELECT 
                                                r.*,
                                                (SELECT COUNT(rr.Id) FROM [RateRange] rr WHERE rr.RateId = r.Id) as 'Cnt'
                                            FROM [Rate] r
                                        ";
                                    $getRecord = sqlsrv_query($conn, $query);
                                    if ($getRecord == FALSE)
                                        die(FormatErrors(sqlsrv_errors()));

                                    while($row = sqlsrv_fetch_array($getRecord, SQLSRV_FETCH_ASSOC))
                                    {
                                        echo "
                                            <tr>
                                                <td>".$row['Name']."</td>
                                                <td>".$row['Cnt']."</td>
                                                <td>
                                                    <a href='rate.php?section=edit&id=".$row['Id']."' title='Edit' class='btn btn-primary btn-sm btn-circle'><i class='fas fa-pen'></i></a>
                                                    <a href='rate.php?section=range&action=add&rid=".$row['Id']."' title='Add Range' class='btn btn-primary btn-sm btn-circle'><i class='fas fa-layer-group'></i></a>
                                                    <a href='#' title='Delete' class='btn btn-danger btn-sm btn-circle'><i class='fas fa-trash'></i></a>
                                                </td>
                                            </tr>
                                        ";
                                    }
                                    sqlsrv_free_stmt($getRecord);
                                    sqlsrv_close($conn);

                                } catch(Exception $e){
                                    
                                }
                            
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page level plugins -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>