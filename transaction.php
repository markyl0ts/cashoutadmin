<?php
    include('include/header.php');
?>

<!-- Custom styles for this page -->
<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

<!-- Begin Page Content -->
<div class="container-fluid">

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Transactions</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Machine</th>
                            <th>Reference #</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Transaction Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Machine</th>
                            <th>Reference #</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Transaction Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        
                            try {
                                $conn = OpenConnection();
                                $query = "
                                        SELECT 
                                            (SELECT CONCAT(s.[Name],'|',s.Id) FROM [System] s WHERE s.Id = t.SystemId) as 'Machine',
                                            t.Reference, 
                                            (SELECT c.FullName FROM Contact c WHERE c.id = t.ContactId) as 'Name',
                                            t.Amount, 
                                            (SELECT rr.Fee FROM RateRange rr WHERE rr.Id = t.RateRangeId) as 'Fee',
                                            t.CreatedDate,
                                            t.[Status]
                                        FROM [Transaction] t
                                ";
                                $getTrans = sqlsrv_query($conn, $query);
                                if ($getTrans == FALSE)
                                    die(FormatErrors(sqlsrv_errors()));

                                while($row = sqlsrv_fetch_array($getTrans, SQLSRV_FETCH_ASSOC))
                                {
                                    $macVal = $row['Machine'];
                                    $arrMac = explode("|",$macVal);
                                    $macLink = "";
                                    if(count($arrMac) > 0)
                                        $macLink = "<a href='machine.php?section=details&id=".$arrMac[1]."' >".$arrMac[0]."</a>";

                                    echo "
                                        <tr>
                                            <td>".$macLink."</td>
                                            <td>".$row['Reference']."</td>
                                            <td>".$row['Name']."</td>
                                            <td>".$row['Amount']."</td>
                                            <td>".$row['Fee']."</td>
                                            <td>".(string)$row['CreatedDate']->format('Y-m-d H:i:s')."</td>
                                            <td>".db_status_to_word($row['Status'])."</td>
                                            <td></td>
                                        </tr>
                                    ";
                                }
                                sqlsrv_free_stmt($getTrans);
                                sqlsrv_close($conn);

                            } catch(Exception $e){
                                echo "
                                    <tr>
                                        <td colspan='7' >No data found</td>
                                    </tr>
                                ";
                            }
                        
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->

<!-- Page level plugins -->
<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script>
    // Call the dataTables jQuery plugin
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<?php
    include('include/footer.php');
?>