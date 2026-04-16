<?php
include('config.php');
$pageTitle = "Diseases";
include('header.php');
include('navbar.php');
?>

<div class="container mt-5">
    <h2 class="page-title">Disease List</h2>
    <div class="table-container">
        <table class="table table-bordered table-striped">
            <thead class="table-warning">
                <tr>
                    <th>Disease ID</th>
                    <th>Disease Name</th>
                    <th>Category</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = mysqli_query($conn, "SELECT * FROM Disease ORDER BY disease_id ASC");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                  <td>{$row['disease_id']}</td>
                  <td>{$row['disease_name']}</td>
                  <td>{$row['category']}</td>
                </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>