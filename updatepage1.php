<?php
session_start();

ob_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include('header.php');
include("../LoginRegisterAuthentication/connection.php");

$row = [];

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($connection, $_GET['id']);

    // Execute the SELECT query
    $query = "SELECT * FROM students WHERE id = '$id'";
    $result = mysqli_query($connection, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    } else {
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
        } else {
            echo "No records found for ID: $id";
        }
    }
}

if (isset($_POST['updatestudents'])) {
    $idnew = mysqli_real_escape_string($connection, $_GET['id']);
    $learners_name = mysqli_real_escape_string($connection, $_POST['learners_name']);
    $region = mysqli_real_escape_string($connection, $_POST['region']);
    $division = mysqli_real_escape_string($connection, $_POST['division']);
    $school_id = mysqli_real_escape_string($connection, $_POST['school_id']);
    $school_year = mysqli_real_escape_string($connection, $_POST['school_year']);
    $grade = mysqli_real_escape_string($connection, $_POST['grade']);
    $school_level = mysqli_real_escape_string($connection, $_POST['school_level']);
    $gender = mysqli_real_escape_string($connection, $_POST['gender']);
    $subject = mysqli_real_escape_string($connection, $_POST['subject']);
    $section = mysqli_real_escape_string($connection, $_POST['section']);

    // Prepare SQL statement
    $stmt = $connection->prepare("UPDATE students SET learners_name = ?, region = ?, division = ?, school_id = ?, school_year = ?, grade = ?, school_level = ?, gender = ?, subject = ?, section = ? WHERE id = ?");
    $stmt->bind_param("ssssssssssi", $learners_name, $region, $division, $school_id, $school_year, $grade, $school_level, $gender, $subject, $section, $idnew);

    if ($stmt->execute()) {
        header('Location: Crud.php?update_msg=You Have Successfully Updated The Data');
        exit();
    } else {
        die("Query failed: " . $stmt->error);
    }

    $stmt->close();
    $connection->close();
}

ob_end_flush();
?>


<form action="updatepage1.php?id_new=<?php echo isset($id) ? $id : ''; ?>" method="post">
    <div class="form-group">
        <label for="learners_name">Learners Name</label>
        <input type="text" name="learners_name" class="form-control" value="<?php echo isset($row['learners_name']) ? htmlspecialchars($row['learners_name']) : '' ?>">
    </div>

    <div class="form-group">
        <label for="region">Region</label>
        <input type="text" name="region" class="form-control" value="<?php echo isset($row['region']) ? htmlspecialchars($row['region']) : '' ?>">
    </div>

    <div class="form-group">
        <label for="division">Division</label>
        <input type="text" name="division" class="form-control" value="<?php echo isset($row['division']) ? htmlspecialchars($row['division']) : '' ?>">
    </div>

    <div class="form-group">
        <label for="school_id">School ID</label>
        <input type="text" name="school_id" class="form-control" value="<?php echo isset($row['school_id']) ? htmlspecialchars($row['school_id']) : '' ?>">
    </div>

    <div class="form-group">
        <label for="school_year">School Year</label>
        <input type="text" name="school_year" class="form-control" value="<?php echo isset($row['school_year']) ? htmlspecialchars($row['school_year']) : '' ?>">
    </div>

    <div class="form-group">
        <label for="grade">Grade Level</label>
        <input type="text" name="grade" class="form-control" value="<?php echo isset($row['grade']) ? htmlspecialchars($row['grade']) : '' ?>">
    </div>

    <div class="form-group">
        <label for="school_level">School Level</label>
        <select name="school_level" class="form-control">
            <option value="SHS" <?php echo isset($row['school_level']) && $row['school_level'] == 'SHS' ? 'selected' : ''; ?>>SHS</option>
            <option value="JHS" <?php echo isset($row['school_level']) && $row['school_level'] == 'JHS' ? 'selected' : ''; ?>>JHS</option>
        </select>
    </div>

    <div class="form-group">
        <label for="gender">Gender</label>
        <select name="gender" class="form-control">
            <option value="Male" <?php echo isset($row['gender']) && $row['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
            <option value="Female" <?php echo isset($row['gender']) && $row['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
        </select>
    </div>

    <div class="form-group">
        <label for="subject">Subject</label>
        <input type="text" name="subject" class="form-control" value="<?php echo isset($row['subject']) ? htmlspecialchars($row['subject']) : '' ?>">
    </div>

    <div class="form-group">
        <label for="section">Section</label>
        <input type="text" name="section" class="form-control" value="<?php echo isset($row['section']) ? htmlspecialchars($row['section']) : '' ?>">
    </div>

    <input type="submit" class="btn btn-success" name="updatestudents" value="UPDATE">
</form>

<?php include ('footer.php'); ?>
