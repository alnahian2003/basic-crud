<?php
define("DB", "D:/XAMPP/htdocs/LWHH/basic-crud/data/db.txt"); // Database file

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
            echo "No Data to Show. Add new student's data first or seed to get some dummy data";
        } else{
            
        foreach ($students as $student) {
         ?>
         <tr>
            <td><?php printf("%s %s", $student["fname"],$student["lname"] );?></td>
            <td><?php printf("%s", $student["roll"]);?></td>
            <td><?php printf('<a class="button btn-blue" href="/lwhh/basic-crud/index.php?task=edit&id=%s">Edit</a> | <a class="button btn-red" href="/lwhh/basic-crud/index.php?task=edit&id=%s">Delete</a>',$student["id"],$student["id"]);?></td>
         </tr>
    <?php
        }
    }
    ?>
</table>
<?php
}

function addStudent($fname, $lname, $roll){
    $found = false;
    $serializedContent = file_get_contents(DB);
    $students = unserialize($serializedContent);

    foreach ($students as $_student) {
        if ($_student["roll"] == $roll) {
            $found = true;
            break;
        };
    };
    if (!$found) {
        $newId = count($students)+1;
        
        $newStudent = array(
            "id" =>$newId,
            "fname"=>$fname,
            "lname"=>$lname,
            "roll"=>$roll
        );
        
        array_push($students, $newStudent);
        $serializedData = serialize($students);
        file_put_contents(DB, $serializedData, LOCK_EX);
        return true;
    }
    return false;
}