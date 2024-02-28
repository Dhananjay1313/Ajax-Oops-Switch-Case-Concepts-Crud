<?php
include 'abc.php';

$servername = "localhost";
$username = "root";
$password = "";
$db = "ajaxoops";

$con = mysqli_connect($servername, $username, $password, $db);

$abc = new abc();

switch ($_POST['type']) {

    case 'edit':
        $abc = new abc();
        $id = $_POST['id'];
        $sql = "SELECT * FROM ajaxoops WHERE id='$id'";
        $bbb = $abc->getdata($sql);
        echo json_encode($bbb);
        // exit;
        break;

    case 'insert':
        $abc = new abc();

        $id = $_POST['id'];
        $fullname = $_POST['fullname'];
        $gender = $_POST['gender'];
        $hobbies = implode(',', $_POST['hobbies']);
        $computer = $_POST['computer'];
        $description = $_POST['description'];

        $image = $_FILES['image']['name'];
        if($image != ""){
        $target_dir = "C:/wamp64/www/new/ajaxoops/image/";
        $target = $target_dir . $image;
        $is_upload = move_uploaded_file($_FILES['image']['tmp_name'], $target);
        }   

        if ($id != "") {
            if($image != ""){
                $sql = "UPDATE ajaxoops SET fullname='$fullname', gender='$gender',hobbies='$hobbies',computer='$computer',description='$description',image='$image' WHERE id='$id'";
            }else{
                $sql = "UPDATE ajaxoops SET fullname='$fullname', gender='$gender',hobbies='$hobbies',computer='$computer',description='$description' WHERE id='$id'";
            }
          
            $result = $abc->execute($sql);

            if ($result) {
                $response['status'] = 1;
                $response['message'] = "Data Updated successfully!";
            } else {
                $response['message'] = "Something's Fishy";
            }
        } else {
            $sql = "INSERT INTO ajaxoops(fullname,gender,hobbies,computer,description,image)VALUES('$fullname','$gender','$hobbies','$computer','$description','$image')";
            $result = $abc->execute($sql);
            if ($result) {
                $response['status'] = 1;
                $response['message'] = "Data added successfully!";
            } else {
                $response['message'] = "Something's Fishy";
            }
        }
        echo json_encode($response);
        break;

    case 'list':
        $abc = new abc();

        $query = "SELECT * FROM ajaxoops";

        $result = $abc->getdata($query);

        $data = [];
        foreach ($result as $key => $value) {
          $path = "C:/wamp64/www/new/ajaxoops/image/". $value['image'];
          $data[] = [
            "fullname" => $value['fullname'],
            "gender" => $value['gender'],
            "hobbies" => $value['hobbies'],
            "computer" => $value['computer'],
            "description" => $value['description'],
            "image" => $path,
            "actions" => "<button class='btn btn-success' id='edit' data-id=" . $value['id'] . ">Edit</button><button class='btn btn-warning' id='delete' data-id=" . $value['id'] . ">Delete</button>"
          ];
        }
        
        echo json_encode(["data" => $data]);
        // $abc = [];
        // $row = '';
        // foreach ($result as $key => $value) {
        //     $image = $value['image'];
        //     $path = "../image/$image";
        //     $row .= "<tr>";
        //     $row .= "<td>" . $value['fullname'] . "</td>";
        //     $row .= "<td>" . $value['gender'] . "</td>";
        //     $row .= "<td>" . $value['hobbies'] . "</td>";
        //     $row .= "<td>" . $value['computer'] . "</td>";
        //     $row .= "<td>" . $value['description'] . "</td>";
        //     $row .= "<td><img id='imgval' style='height: 90px;width: 70px;' src=$path></td>";
        //     $row .= "<td><button class='btn btn-success' id='edit' data-id=" . $value['id'] . ">Edit</button><button class='btn btn-warning' id='delete' data-id=" . $value['id'] . ">Delete</button></td>";
        //     $row .= "</tr>";
        // }
        // $abc['tbody'] = $row;
        // echo json_encode($abc);
        break;

    case 'delete':

        $id = $_POST['id'];
        $sql = "SELECT image FROM ajaxoops WHERE id='$id'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $image = $row['image'];
        $target = "../image/" . $image;

        $sql = "DELETE FROM ajaxoops WHERE id='$id'";
        $aaa = mysqli_query($con, $sql);

        if (file_exists($target)) {
            unlink($target);
        }

        if ($aaa) {
            $response['status'] = 1;
            $response['message'] = "Data deleted successfully!";
        } else {
            $response['message'] = "Something's Fishy";
        }

        echo json_encode($response);
        break;
}
