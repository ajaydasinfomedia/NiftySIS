<?php 

$id=$_REQUEST['id']

$username = "root";
$password = "";
$dbname = "hoki";

// Create connection
$conn = new mysqli($username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT post_title FROM wp_post where ID=".$id;
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "Post Title: " . $row["post_title"];
    }
} else {
    echo "0 results";
}
$conn->close();
?> 


?>