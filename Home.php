<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$xmlFile = 'people.xml';
$xml = simplexml_load_file($xmlFile) or die("Error: Cannot load or parse people.xml");

// Helper function to generate student ID like 20251001, incrementing by 1
function generateStudentID($xml) {
    $prefix = "2025"; // fixed year part, you can update if needed
    $start = 1001;    // start number

    $maxNum = 0;
    foreach ($xml->student as $student) {
        $id = (string)$student->id;
        if (strpos($id, $prefix) === 0) {
            $numPart = intval(substr($id, strlen($prefix)));
            if ($numPart > $maxNum) {
                $maxNum = $numPart;
            }
        }
    }

    $nextNum = $maxNum ? $maxNum + 1 : $start;
    return $prefix . str_pad($nextNum, 4, "0", STR_PAD_LEFT);
}

// Handle deletion
if (isset($_GET['delete_student'])) {
    $student_id = $_GET['delete_student'];

    $dom = new DOMDocument;
    $dom->load($xmlFile);

    $xpath = new DOMXPath($dom);
    foreach ($xpath->query('//student[id="' . $student_id . '"]') as $studentNode) {
        $studentNode->parentNode->removeChild($studentNode);
    }

    $dom->save($xmlFile);
    header("Location: Home.php");
    exit();
}

// Handle adding a student
if (isset($_POST['add_student'])) {
    $newStudent = $xml->addChild('student');
    $newStudent->addChild('id', generateStudentID($xml));
    $newStudent->addChild('fullname', $_POST['fullname']);
    $newStudent->addChild('age', $_POST['age']);
    $newStudent->addChild('section', $_POST['section']);
    $newStudent->addChild('course', $_POST['course']);
    $xml->asXML($xmlFile);
    header("Location: Home.php");
    exit();
}

// Handle updating a student
if (isset($_POST['update_student'])) {
    $student_id = $_POST['id'];
    foreach ($xml->student as $student) {
        if ((string)$student->id == $student_id) {
            $student->fullname = $_POST['fullname'];
            $student->age = $_POST['age'];
            $student->section = $_POST['section'];
            $student->course = $_POST['course'];
            $xml->asXML($xmlFile);
            break;
        }
    }
    header("Location: Home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Info System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 600;
        }

        .nav-links a {
            color: white;
            margin-left: 20px;
            text-decoration: none;
            font-weight: 500;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .container {
            background: #fff;
            margin: 2rem auto;
            padding: 2rem;
            width: 90%;
            max-width: 1000px;
            border-radius: 8px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .container:hover {
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        th, td {
            padding: 12px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        th {
            background-color: #ecf0f1;
            color: #34495e;
        }

        td {
            background-color: #fff;
        }

        input[type="text"], input[type="number"], button {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            font-size: 16px;
            box-sizing: border-box;
        }

        input[type="text"]:focus, input[type="number"]:focus {
            border-color: #3498db;
            outline: none;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        .footer {
            text-align: center;
            padding: 1rem;
            background-color: #2c3e50;
            color: white;
        }

        .search-box {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .search-box input[type="text"] {
            width: 80%;
            margin-right: 10px;
        }

        .search-box button {
            width: 15%;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">Student Info System</div>
    <div class="nav-links">
        <a href="Home.php">Home</a>
        <a href="aboutus.php">About Us</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <h3 style="text-align: center; margin-top: 2rem;">Explore and Add Student Below</h3>

    <!-- Search -->
    <div class="search-box">
        <form method="get" style="width: 100%; display: flex;">
            <input type="text" name="search" placeholder="Search by ID, name, section, or course" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Table -->
    <table>
        <thead>
        <tr>
            <th>Student ID</th>
            <th>Full Name</th>
            <th>Age</th>
            <th>Section</th>
            <th>Course</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
        foreach ($xml->student as $student):
            if (
                $search === '' || 
                strpos(strtolower((string)$student->id), $search) !== false ||
                strpos(strtolower((string)$student->fullname), $search) !== false ||
                strpos(strtolower((string)$student->section), $search) !== false ||
                strpos(strtolower((string)$student->course), $search) !== false
            ):
        ?>
            <tr>
                <td><?php echo htmlspecialchars($student->id); ?></td>
                <td><?php echo htmlspecialchars($student->fullname); ?></td>
                <td><?php echo htmlspecialchars($student->age); ?></td>
                <td><?php echo htmlspecialchars($student->section); ?></td>
                <td><?php echo htmlspecialchars($student->course); ?></td>
                <td>
                    <a href="?edit_student=<?php echo htmlspecialchars($student->id); ?>"><button>Edit</button></a>
                    <a href="?delete_student=<?php echo htmlspecialchars($student->id); ?>" onclick="return confirm('Are you sure you want to delete this student?');"><button>Delete</button></a>
                </td>
            </tr>
        <?php endif; endforeach; ?>
        </tbody>
    </table>

    <hr>

    <!-- Edit Form -->
    <?php if (isset($_GET['edit_student'])):
        $edit_id = $_GET['edit_student'];
        foreach ($xml->student as $student):
            if ((string)$student->id === $edit_id): ?>
                <h3>Edit Student</h3>
                <form method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($student->id); ?>">
                    <input type="text" name="fullname" placeholder="Full Name" value="<?php echo htmlspecialchars($student->fullname); ?>" required>
                    <input type="number" name="age" placeholder="Age" value="<?php echo htmlspecialchars($student->age); ?>" required>
                    <input type="text" name="section" placeholder="Section" value="<?php echo htmlspecialchars($student->section); ?>" required>
                    <input type="text" name="course" placeholder="Course" value="<?php echo htmlspecialchars($student->course); ?>" required>
                    <button type="submit" name="update_student">Update Student</button>
                </form>
    <?php break; endif; endforeach; else: ?>

    <!-- Add Form -->
    <h3>Add New Student</h3>
    <form method="post">
        <input type="text" name="fullname" placeholder="Full Name" required>
        <input type="number" name="age" placeholder="Age" required>
        <input type="text" name="section" placeholder="Section" required>
        <input type="text" name="course" placeholder="Course" required>
        <button type="submit" name="add_student">Add Student</button>
    </form>
    <?php endif; ?>
</div>

<div class="footer">
    &copy; <?php echo date("Y"); ?> Student Info System. All rights reserved.
</div>

</body>
</html>
