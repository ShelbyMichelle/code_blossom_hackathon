<?php 
session_start();
include 'connect.php'; 

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD SYSTEM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="./CSS/display.css">
    <style>
                /* media-queries.css */

                @media (max-width: 768px) {
          /* Make the table more responsive */
          .table {
              display: block;
              overflow-x: auto;
              width: 100%;
          }
      
          /* Adjust table cells for better readability */
          .table thead, .table tbody, .table tr, .table th, .table td {
              display: block;
              text-align: left;
          }
      
          .table thead {
              float: left;
          }
      
          .table tbody {
              width: auto;
              position: relative;
              overflow-x: auto;
              white-space: nowrap;
          }
      
          .table th, .table td {
              border-bottom: 1px solid #ddd;
              padding: 8px;
              box-sizing: border-box;
          }
      
          .table th {
              background: #343a40;
              color: #fff;
              position: absolute;
              left: 0;
              width: 100%;
          }
      
          /* Hide table header labels on mobile and show in cells */
          .table thead {
              display: none;
          }
      
          .table tbody tr {
              display: flex;
              flex-direction: column;
              align-items: flex-start;
              border: 1px solid #ddd;
              margin-bottom: 10px;
          }
      
          /* Add label before each cell on small screens */
          .table td[data-label]::before {
              content: attr(data-label);
              font-weight: bold;
              margin-right: 10px;
              display: inline-block;
              width: 80px;
          }
      
          /* Adjust buttons */
          .btn {
              margin: 5px 0;
              width: 100%;
          }
      }
      
      /* Custom styles for the header and container */
      
      @media (max-width: 576px) {
          /* Stack header content vertically on smaller screens */
          .header {
              flex-direction: column;
          }
      }
    </style>

</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <div>
            <h1 class='h1'>Welcome
                <?php echo htmlspecialchars($_SESSION['username']); ?>!
                
            </h1>
        </div>
        <div class="logout">
           <button class='btn btn-primary'>
               <a href="logout.php" class="text-light">Logout</a>
           </button>
        </div> 
    </div>

    <!-- Container Section -->
    <div class="container my-5">
        <button class='btn btn-success mb-4'>
            <a href="user.php" class="text-light">Add New Employee</a>
        </button>

        <?php
            // Handle sorting parameters safely
            $sort = isset($_GET['sort']) ? mysqli_real_escape_string($con, $_GET['sort']) : 'id';
            $order = isset($_GET['order']) && $_GET['order'] === 'desc' ? 'desc' : 'asc';

            // Toggle sorting order
            $newOrder = $order === 'asc' ? 'desc' : 'asc';

            // Construct the SQL query with sorting
            $sql = "SELECT * FROM `crud` ORDER BY $sort $order";
            $result = mysqli_query($con, $sql);
        ?>

        <!-- Table Section -->
        <table class="table table-hover table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th scope="col"><a href="?sort=id&order=<?php echo $newOrder; ?>" class="text-light">Serial No</a></th>
                    <th scope="col"><a href="?sort=name&order=<?php echo $newOrder; ?>" class="text-light">Name</a></th>
                    <th scope="col"><a href="?sort=email&order=<?php echo $newOrder; ?>" class="text-light">Email</a></th>
                    <th scope="col"><a href="?sort=position&order=<?php echo $newOrder; ?>" class="text-light">Postion</a></th>
                    <th scope="col"><a href="?sort=salary&order=<?php echo $newOrder; ?>" class="text-light">Salary</a></th>
                    <th scope="col">Operations</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $id = $row['id'];
                            $name = htmlspecialchars($row['name']);
                            $email = htmlspecialchars($row['email']);
                            $postion = htmlspecialchars($row['position']);
                            $salary = htmlspecialchars($row['salary']);

                            echo '<tr>
                                    <th scope="row">' . $id . '</th>
                                    <td>' . $name . '</td>
                                    <td>' . $email . '</td>
                                    <td>' . $postion . '</td>
                                    <td>' . $salary . '</td>
                                    <td>
                                        <button class="btn btn-info">
                                            <a href="update.php?updateid=' . $id . '" class="text-light">Update</a>
                                        </button>
                                        <button class="btn btn-danger">
                                            <a href="delete.php?deleteid=' . $id . '" class="text-light">Delete</a>
                                        </button>
                                    </td>
                                </tr>';
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No records found</td></tr>";
                    }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
