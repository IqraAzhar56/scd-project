<?php 
session_start();
include_once('includes/config.php');
if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
} else {
    // Add Faculty
    if(isset($_POST['addFaculty'])) {
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $subject = mysqli_real_escape_string($con, $_POST['subject']);
        $email = mysqli_real_escape_string($con, $_POST['email']);

        // Check if email already exists
        $check_email_query = "SELECT * FROM faculty WHERE email='$email'";
        $result = mysqli_query($con, $check_email_query);

        if(mysqli_num_rows($result) == 0) {
            // Insert into database
            $query = "INSERT INTO faculty (name, subject, email) VALUES ('$name', '$subject', '$email')";
            mysqli_query($con, $query);
        } else {
            echo "Email already exists!";
        }
    }

    // Deleting a faculty member
    if(isset($_GET['delete'])) {
        $faculty_id = $_GET['delete'];
        $query = "DELETE FROM faculty WHERE id = '$faculty_id'";
        mysqli_query($con, $query);
        header('location:faculty-list.php');
    }

    // Updating a faculty member
    if(isset($_POST['editFaculty'])) {
        $faculty_id = $_POST['faculty_id'];
        $name = mysqli_real_escape_string($con, $_POST['name']);
        $subject = mysqli_real_escape_string($con, $_POST['subject']);
        $email = mysqli_real_escape_string($con, $_POST['email']);

        // Check if email already exists for another faculty
        $check_email_query = "SELECT * FROM faculty WHERE email='$email' AND id!='$faculty_id'";
        $result = mysqli_query($con, $check_email_query);

        if(mysqli_num_rows($result) == 0) {
            // Update the database
            $query = "UPDATE faculty SET name='$name', subject='$subject', email='$email' WHERE id='$faculty_id'";
            mysqli_query($con, $query);
        } else {
            echo "Email already exists!";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Subject Registration</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <style>
         /* Styling for various elements such as buttons, tables, and forms */
    button[type="submit"] {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
    }

    /* Styling the Faculty List Table */
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .table th, .table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .table th {
        background-color: #f8f9fa;
        color: #495057;
    }

    .table td {
        background-color: #ffffff;
    }

    .table tr:nth-child(even) td {
        background-color: #f2f2f2;
    }

    .table tr:hover {
        background-color: #f1f1f1;
    }

    .table a {
        padding: 5px 10px;
        margin-right: 10px;
        border-radius: 5px;
        text-decoration: none;
        background-color: #17a2b8;
        color: white;
        transition: background-color 0.3s ease;
    }

    .table a:hover {
        background-color: #138496;
    }

    .table a.delete {
        background-color: #dc3545;
    }

    .table a.delete:hover {
        background-color: #c82333;
    }

    /* Form styling */
    form input[type="text"],
    form input[type="email"],
    form select {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }

    form input[type="text"]:focus,
    form input[type="email"]:focus,
    form select:focus {
        border-color: #007bff;
        outline: none;
    }

    form {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }
    </style>
    <body class="sb-nav-fixed">
        <?php include_once('includes/navbar.php'); ?>
        <div id="layoutSidenav">
            <?php include_once('includes/sidebar.php'); ?>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h2 class="mt-4">Subject Registration</h2>

                        <!-- Add Faculty Form -->
                        <form method="POST" class="mb-4">
                            <input type="text" name="name" placeholder=" Name" required>
                            <input type="email" name="email" placeholder=" Email" required>
                           <select name="subject" required>
                           <option value="" disabled selected>Select a Subject</option>
                           <option value="Computer Network">Computer Network</option>
                         <option value="Database">Database</option>
                         <option value="OOP">OOP</option>
                          <option value="Operating system">Operating system</option>
                         <option value="Calculus">Calculus</option>
                         <option value="Software Construction & Development">Software Construction & Development</option>
                         </select>
                         <button type="submit" name="addFaculty">Add</button>
                        </form>

                        <!-- Faculty List Table -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Subject</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Fetch faculty list
                                        $query = mysqli_query($con, "SELECT * FROM faculty");
                                        while ($row = mysqli_fetch_assoc($query)) { ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['email']; ?></td>
                                                <td><?php echo $row['subject']; ?></td>
                                                <td>
         <a href="edit-faculty.php?id=<?php echo $row['id']; ?>">Edit</a>
        <a href="?delete=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this Entry?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <?php include('includes/footer.php'); ?>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
<?php } ?>
