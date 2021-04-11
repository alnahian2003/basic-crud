<?php
define("DB", "data/db.txt"); // Database file

// Function of Seeding
function seed($fileName)
{
    $data = array(
        array("id"=>1,"fname" => "Abdullah", "lname" => "Al Nahian", "roll" => 1),
        array("id"=>2,"fname" => "Mahamud", "lname" => "Hasan", "roll" => 2),
        array("id"=>3,"fname" => "Jhankar", "lname" => "Mahbub", "roll" => 7),
        array("id"=>4,"fname" => "Mehbub", "lname" => "Rashid", "roll" => 12),
        array("id"=>5,"fname" => "Brad", "lname" => "Traversy", "roll" => 14),
    );

    $serializedData = serialize($data);
    file_put_contents($fileName, $serializedData, LOCK_EX);
}

// Function for Generating Report
function generate($fileName){

    $serializedContent = file_get_contents($fileName);
    $students = unserialize($serializedContent);

?>
    <table>
        <tr>
            <th>Name</th>
            <th>Roll</th>
            <th>Action</th>
        </tr>
        <?php
        if ($students == "") {
            echo "<p>No Data to Show. <a href=\"/lwhh/basic-crud/index.php?task=add\"> Add new </a> student's data first or <a href=\"/lwhh/basic-crud/index.php?task=seed\"> seed </a> to get some dummy data</p>";
        } else{
            
        foreach ($students as $student) {
         ?>
         <tr>
            <td><?php printf("%s %s", $student["fname"],$student["lname"] );?></td>
            <td><?php printf("%s", $student["roll"]);?></td>
            <td><?php printf('<a class="button btn-blue" href="/lwhh/basic-crud/index.php?task=edit&id=%s">Edit</a> | <a class="button btn-red delete" href="/lwhh/basic-crud/index.php?task=delete&id=%s">Delete</a>',$student["id"],$student["id"]);?></td>
         </tr>
    <?php
        }
    }
    ?>
</table>
<?php
}

function getNewId($students){
    $maxId = max(array_column($students, "id"));
    return $maxId+1;
    }

function addStudent($fname, $lname, $roll, $fileName){
    $found = false;
    $serializedContent = file_get_contents($fileName);
    $students = unserialize($serializedContent);

    foreach ($students as $_student) {
        if ($_student["roll"] == $roll) {
            $found = true;
            break;
        };
    };
    if (!$found) {
        $newId = getNewId($students);
        
        $newStudent = array(
            "id" =>$newId,
            "fname"=>$fname,
            "lname"=>$lname,
            "roll"=>$roll
        );
        
        array_push($students, $newStudent);
        $serializedData = serialize($students);
        file_put_contents($fileName, $serializedData, LOCK_EX);
        return true;
    }
    return false;
}

// Get Student to Edit

function getStudent($id, $fileName){
    $serializedContent = file_get_contents($fileName);
    $students = unserialize($serializedContent);

    foreach ($students as $student) {
        if ($student["id"] == $id) {
            return $student;
        };
    }
    return false;
}

// Update Student Function
function updateStudent($fname, $lname, $roll, $id, $fileName){
    $found = false;
    $serializedContent = file_get_contents($fileName);
    $students = unserialize($serializedContent);

    foreach ($students as $_student) {
        if ($_student["roll"] == $roll && $_student["id"]!= $id) {
            $found = true;
            break;
        };
    }

    if (!$found) {
        $students[$id-1]["fname"] = $fname;
        $students[$id-1]["lname"] = $lname;
        $students[$id-1]["roll"] = $roll;
    
        $serializedData = serialize($students);
        file_put_contents($fileName, $serializedData, LOCK_EX);
        
        return true;
    }
    return false;
}

// Delete a Data Function
function deleteStudent($id, $fileName){
    $serializedContent = file_get_contents($fileName);
    $students = unserialize($serializedContent);

    // unset($students[$id-1]);
    $i = 0;
    foreach ($students as $offset=>$student) {
        if ($student["id"]==$id) {
                unset($students[$offset]);
        };
    }

    $serializedData = serialize($students);
    file_put_contents($fileName, $serializedData, LOCK_EX);

}

