<?php
session_start(); // Start the session at the very top

// Check if the session is correctly set
if (!isset($_SESSION['username']) || !isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

include('header.php'); 
include("../LoginRegisterAuthentication/connection.php"); 

$userid = $_SESSION['userid'];

// Debugging output to check the user ID
if (empty($userid)) {
    echo "User ID is not set. Session userid: " . htmlspecialchars($userid);
    exit();
}

// Query to fetch student data for the current user
$query = "SELECT * FROM students WHERE user_id = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 'i', $userid);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

// Fetch and display the data
while ($row = mysqli_fetch_assoc($result)) {
    echo "Learners Name: " . htmlspecialchars($row['learners_name']);
}
?>



<br>
<div class="box1">
    <a href="Crud.php" class="btn btn-secondary">All</a>
    <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">ADD STUDENTS</button>
</div>
</br>

<!-- Filter and Search Form -->
<form method="GET" action="" class="mb-3">
    <div class="d-flex flex-wrap justify-content-center align-items-center">
        <div class="dropdown mb-2 mr-2">
           
         
        
        
        
        
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo isset($_GET['school_level']) && $_GET['school_level'] ? $_GET['school_level'] : 'School Level'; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item" href="?school_level=SHS<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">SHS</a></li>
                <li><a class="dropdown-item" href="?school_level=JHS<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">JHS</a></li>
                <li><a class="dropdown-item" href="?school_level=">All Levels</a></li>
            </ul>
        </div>

        <div class="dropdown mb-2 mr-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="schoolYearDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo isset($_GET['school_year']) && $_GET['school_year'] ? $_GET['school_year'] : 'School Year'; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="schoolYearDropdown">
                <?php for ($year = 2020; $year <= 2024; $year++): ?>
                    <li><a class="dropdown-item" href="?school_year=<?php echo $year; ?>"><?php echo $year; ?></a></li>
                <?php endfor; ?>
                <li><a class="dropdown-item" href="?school_year=">All Years</a></li>
            </ul>
        </div>

        <div class="dropdown mb-2 mr-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="gradeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo isset($_GET['grade']) && $_GET['grade'] ? $_GET['grade'] : 'Grade Level'; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="gradeDropdown">
                <?php for ($i = 7; $i <= 12; $i++): ?>
                    <li><a class="dropdown-item" href="?grade=<?php echo $i; ?>th<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>"><?php echo $i; ?>th</a></li>
                <?php endfor; ?>
                <li><a class="dropdown-item" href="?grade=">All Grades</a></li>
            </ul>
        </div>

        <div class="dropdown mb-2 mr-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="sectionDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo isset($_GET['section']) && $_GET['section'] ? $_GET['section'] : 'Section'; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="sectionDropdown">
                <li><a class="dropdown-item" href="?section=SectionA<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Section A</a></li>
                <li><a class="dropdown-item" href="?section=SectionB<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Section B</a></li>
                <li><a class="dropdown-item" href="?section=">All Sections</a></li>
            </ul>
        </div>

        <div class="dropdown mb-2 mr-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="genderDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo isset($_GET['gender']) && $_GET['gender'] ? $_GET['gender'] : 'Gender'; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="genderDropdown">
                <li><a class="dropdown-item" href="?gender=Male<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Male</a></li>
                <li><a class="dropdown-item" href="?gender=Female<?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>">Female</a></li>
                <li><a class="dropdown-item" href="?gender=">All</a></li>
            </ul>
        </div>

        <div class="dropdown mb-2">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="subjectDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo isset($_GET['subject']) && $_GET['subject'] ? $_GET['subject'] : 'Subject'; ?>
            </button>
            <ul class="dropdown-menu" aria-labelledby="subjectDropdown" id="subjectList">
                <!-- Subjects will be loaded here based on the selected grade level -->
            </ul>
        </div>
        <div class="input-group">
    <input type="text" name="search" class="form-control" placeholder="Search by Learners Name" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
    <div class="input-group-append">
        <input type="submit" class="btn btn-primary" value="Search">
    </div>
</div>
</form>
    </div>
</form>



<div class="table-responsive">
    <table class="table table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>Learners Name</th>
                <th>Section</th>
                <th>Grade</th>
                <th>School Level</th>
                <th>Region</th>
                <th>Division</th>
                <th>School ID</th>
                <th>School Year</th>
                <th>Gender</th>
                <th>Subject</th>
            </tr>
        </thead>
        <tbody>
        <?php
$search = isset($_GET['search']) ? $_GET['search'] : '';
$school_level = isset($_GET['school_level']) ? $_GET['school_level'] : '';
$school_year = isset($_GET['school_year']) ? $_GET['school_year'] : '';
$grade = isset($_GET['grade']) ? $_GET['grade'] : '';
$section = isset($_GET['section']) ? $_GET['section'] : '';
$gender = isset($_GET['gender']) ? $_GET['gender'] : '';
$subject = isset($_GET['subject']) ? $_GET['subject'] : '';

$query = "SELECT * FROM students WHERE 1=1";

if ($search) {
    $query .= " AND learners_name LIKE '%$search%'";
}
if ($school_level) {
    $query .= " AND school_level = '$school_level'";
}
if ($school_year) {
    $query .= " AND school_year = '$school_year'";
}
if ($grade) {
    $query .= " AND grade = '$grade'";
}
if ($section) {
    $query .= " AND section = '$section'";
}
if ($gender) {
    $query .= " AND gender = '$gender'";
}
if ($subject) {
    $query .= " AND subject = '$subject'";
}

$query .= " ORDER BY id DESC";

$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
} else {
    while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?php echo $row['learners_name']; ?></td>
            <td><?php echo $row['section']; ?></td>
            <td><?php echo $row['grade']; ?></td>
            <td><?php echo $row['school_level']; ?></td>
            <td><?php echo $row['region']; ?></td>
            <td><?php echo $row['division']; ?></td>
            <td><?php echo $row['school_id']; ?></td> <!-- Displaying school_id correctly -->
            <td><?php echo $row['school_year']; ?></td>
            <td><?php echo $row['gender']; ?></td>
            <td><?php echo $row['subject']; ?></td>
            <td>
            
            </td>
        </tr>
        <?php
    }
}
?>

</tbody>
    </table>
</div>

<!-- Modal for Adding Student -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add New Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="insert_data.php" id="addStudentForm">
                    <div class="mb-3">
                        <label for="learners_name" class="form-label">Learners Name</label>
                        <input type="text" class="form-control" id="learners_name" name="learners_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="grade" class="form-label">Grade Level</label>
                        <select class="form-control" id="grade" name="grade" onchange="updateSchoolLevel(); updateSections();" required>
                            <option value="">Select Grade Level</option>
                            <?php for ($i = 7; $i <= 12; $i++): ?>
                                <option value="<?php echo $i; ?>th"><?php echo $i; ?>th</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="school_level" name="school_level" Hidden>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="region" name="region" value="VII" Hidden>
                    </div>
                    <div class="mb-3">
                        <input type="text" class="form-control" id="division" name="division" value="CEBU" Hidden>
                    </div>
                    <div class="mb-3">
                        <label for="school_year" class="form-label">School Year</label>
                        <select class="form-control" id="school_year" name="school_year" required>
                            <?php for ($year = 2020; $year <= 2024; $year++): ?>
                                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="section" class="form-label">Section</label>
                        <select class="form-control" id="section" name="section" required>
                            <option value="">Select Section</option>
                            <!-- Sections will be populated based on grade level -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" id="gender" name="gender" required>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="subject" class="form-label">Subject</label>
                        <select class="form-control" id="subject" name="subject" required>
                            <!-- Subjects will be populated based on grade level and school level -->
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</form>
<script>
function updateSchoolLevel() {
    var grade = document.getElementById('grade').value;
    var schoolLevel = document.getElementById('school_level');
    if (grade) {
        if (parseInt(grade) <= 10) {
            schoolLevel.value = 'JHS';
        } else {
            schoolLevel.value = 'SHS';
        }
    } else {
        schoolLevel.value = '';
    }
    updateSubjects();
    updateSections();
}

function updateSubjects() {
    var grade = document.getElementById('grade').value;
    var subjectDropdown = document.getElementById('subject');
    var subjects = [];

    if (grade) {
        if (parseInt(grade) <= 10) {
            subjects = ['Math', 'Science', 'English', 'History', 'PE'];
        } else {
            subjects = ['Advanced Math', 'Advanced Science', 'Philosophy', 'Economics', 'PE'];
        }
    }

    subjectDropdown.innerHTML = '';
    subjects.forEach(function(subject) {
        var option = document.createElement('option');
        option.value = subject;
        option.textContent = subject;
        subjectDropdown.appendChild(option);
    });
}

function updateSections() {
    var grade = document.getElementById('grade').value;
    var sectionDropdown = document.getElementById('section');
    var sections = [];

    if (grade) {
        if (parseInt(grade) >= 7 && parseInt(grade) <= 10) {
            sections = ['Section A', 'Section B', 'Section C']; // Example sections for JHS
        } else if (parseInt(grade) >= 11 && parseInt(grade) <= 12) {
            sections = ['Section X', 'Section Y', 'Section Z']; // Example sections for SHS
        }
    }
    sectionDropdown.innerHTML = '<option value="">Select Section</option>'; 
    sections.forEach(function(section) {
        var option = document.createElement('option');
        option.value = section;
        option.textContent = section;
        sectionDropdown.appendChild(option);
    });
    console.log("Sections populated:", sections); // Log sections to console
}

</script>


<?php include('footer.php'); ?>