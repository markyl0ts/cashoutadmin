<?php
    include('include/header.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

    <?php 
        if(isset($_GET['section'])){
            if($_GET['section'] == 'details')
                include('parts/machine_details.php');
            if($_GET['section'] == 'edit')
                include('parts/machine_details_edit.php');
        } else {
            include('parts/machine_listing.php');
        }
    ?>

</div>
<!-- /.container-fluid -->

<?php
    include('include/footer.php');
?>