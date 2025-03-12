<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Online Banquet Booking System | Services</title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
<link href="css/font-awesome.css" rel="stylesheet"> 
<script src="js/jquery-1.11.1.min.js"></script>
<script src="js/bootstrap.js"></script>
</head>
<body>

<div class="banner jarallax">
    <div class="agileinfo-dot">
        <?php include_once('includes/header.php');?>
        <div class="wthree-heading">
            <h2>Services</h2>
        </div>
    </div>
</div>

<div class="about-top">
    <div class="container">
        <div class="wthree-services-bottom-grids">
            <p>List of services provided by us.</p>
            <div class="bs-docs-example">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Package Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM tblservice";
                        $query = $dbh->prepare($sql);
                        $query->execute();
                        $results = $query->fetchAll(PDO::FETCH_OBJ);
                        $cnt = 1;

                        if ($query->rowCount() > 0) {
                            foreach ($results as $row) { ?>
                                <tr>
                                    <td><?php echo htmlentities($cnt); ?></td>
                                    <td><?php echo htmlentities($row->ServiceName); ?></td>
                                    <td><?php echo htmlentities($row->SerDes); ?></td>
                                    <td>₹<?php echo htmlentities($row->ServicePrice); ?></td>
                                    <td>
                                        <?php if ($_SESSION['obbsuid'] == "") { ?>
                                            <a href="login.php" class="btn btn-default">Book Services</a>
                                        <?php } else { ?>
                                            <a href="book-services.php?bookid=<?php echo $row->ID; ?>" class="btn btn-default">Book Services</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php $cnt++;
                            }
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>
</body>
</html>
