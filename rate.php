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
                include('parts/rate_listing.php');
            if($_GET['section'] == 'range')
                include('parts/rate_range_listing.php');
        } else {
            include('parts/rate_listing.php');
        }
    ?>

</div>
<!-- /.container-fluid -->

<?php
    include('include/footer.php');
?>