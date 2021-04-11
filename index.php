<?php
require_once "inc/functions.php";
$info = "";
$task = $_GET["task"] ?? ["all"];
$error = $_GET["error"] ?? ["0"];
if ($task == "seed") {
    seed(DB);
    $info = "Seeding is Complete!";
};

if ($task == "delete") {
    $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
    if ($id>0) {
        deleteStudent($id, DB);
        header("location: /lwhh/basic-crud/index.php?task=all");
    }
}

$fname = "";
$lname = "";
$roll = "";
if (isset($_POST["submit"])) {
    $fname = filter_input(INPUT_POST, "fname", FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, "lname", FILTER_SANITIZE_STRING);
    $roll = filter_input(INPUT_POST, "roll", FILTER_SANITIZE_STRING);
    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_STRING);

    if ($id) {
        // Update the existing student
        if ($fname != "" && $lname != "" && $roll != ""){
            $result = updateStudent($fname, $lname, $roll, $id, DB);
            if ($result) {
                header("location: /lwhh/basic-crud/index.php?task=all");
            }
            else {
                $error = 1;
            }
        }
    }else {
        // Add a new student
        if ($fname != "" && $lname != "" && $roll != "") {
            $result = addStudent($fname, $lname, $roll, DB);
            if ($result) {
                header("location: /lwhh/basic-crud/index.php?task=all");
            }
            else {
                $error = 1;
            }
        }
    }

    
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD Operation</title>

    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Hind+Siliguri:500,700">

    <!-- CSS Reset -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.css">

    <!-- Milligram CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css">

    <style>
        *{
            font-family: "Hind Siliguri", sans-serif;
        }
        body {
            overflow-x: hidden !important;
            margin-top: 50px;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            font-weight: bold;
            color: #505050;
        }

        a {
            font-weight: bold;
        }

        p {
            font-weight: normal;
        }

        /* Custom Buttons */
        button {
            user-select: none !important;
            -ms-user-select: none !important;
            -moz-user-select: none !important;
        }

        .btn-blue {
            background-color: #3171ff !important;
            color: #fff;
            border: 0px solid transparent;
        }

        .btn-red {
            background-color: #ff5e32 !important;
            color: #fff;
            border: 0px solid transparent;
        }

        .btn-green {
            background-color: #32b75a !important;
            color: #fff;
            border: 0px solid transparent;
        }

        td:last-child,
        th:last-child {
            text-align: right;
        }

        td:first-child,
        th:first-child {
            text-align: left;
        }

        td,
        th {
            text-align: center;
        }

        td {
            font-weight: normal;
        }

        nav {
            text-align: center;
            padding: 1.5rem 0;
            border-top: 2px solid grey;
            border-bottom: 2px solid grey;
            margin: 2rem auto;
        }

        header {
            text-align: center;
        }
    </style>
</head>

<body>
    <!-- Main Container -->
    <div class="contianer">
        <div class="row">
            <div class="column column-60 column-offset-20">
            
                <!-- Heading Section -->
                <header>
                    <div class="heading-intro">
                    <a href="/lwhh/basic-crud/index.php">
                        <h1>
                            PHP CRUD Operation
                        </h1>
                    </a>
                        <small>Create, Read, Update & Delete</small>
                        <hr />
                        <h2>কিতাবুল আন্দাজ প্রাথমিক বিশ্ববিদ্যালয়</h2>
                        <h4>স্টুডেন্ট ডেটাবেজ</h4>
                    </div>

                    <!-- Navbar -->
                    <nav>
                        <?php
                        include_once "inc/templates/nav.php";
                        ?>
                    </nav>

                    <!-- Action Status -->
                    <div class="info">
                        <p>
                            <?php
                            if ($info != "") {
                                echo "{$info}";
                            }
                            if ("1" == $error) {
                                echo "Duplicate Roll Number! Please try again...";
                            }
                            ?>
                        </p>
                    </div>
                </header>
                <div class="main-content row">
                    <?php
                    if ($task == "all") : {
                        }
                    ?>

                        <div class="column column-100 column-offset-100">
                            <?php
                            generate(DB);
                            ?>
                        </div>

                    <?php endif; ?>

                    <!-- Add Student Form -->

                    <?php
                    if ($task == "add") :
                    ?>
                        <div class="column column-100 column-offset-100">
                            <form action="/lwhh/basic-crud/index.php?task=add" method="POST">

                                <label for="fname">First Name:</label>
                                <input id="fname" name="fname" type="text" value="<?php echo $fname;?>">

                                <label for="lname" placeholder="Enter First Name">Last Name:</label>
                                <input id="lname" name="lname" type="text" value="<?php echo $lname;?>">

                                <label for="roll" placeholder="Enter Roll Number">Roll:</label>
                                <input id="roll" name="roll" type="number" value="<?php echo $roll;?>">

                                <button class="button btn-green" name="submit" type="submit">Add New Student</button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Edit Student Info Form -->
                    <?php
                    if ($task == "edit") :
                        $id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
                        $student = getStudent($id, DB);

                        if ($student):
                    ?>
                        <div class="column column-100 column-offset-100">
                            <form method="POST">
                                <input type="hidden" name ="id" value="<?php echo $id;?>">
                                <label for="fname">First Name:</label>
                                <input id="fname" name="fname" type="text" value="<?php echo $student["fname"];?>">

                                <label for="lname" placeholder="Enter First Name">Last Name:</label>
                                <input id="lname" name="lname" type="text" value="<?php echo $student["lname"];?>">

                                <label for="roll" placeholder="Enter Roll Number">Roll:</label>
                                <input id="roll" name="roll" type="number" min="1" max="999" value="<?php echo $student["roll"];?>">

                                <button class="button btn-green" name="submit" type="submit">Update</button>
                            </form>
                        </div>

                    <?php
                    endif;
                    endif;
                     ?>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript" src="assets/main.js"></script>
</body>

</html>