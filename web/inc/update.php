<?php
require_once('conn.php');

$comment = $_POST['comment'];
$comment_case = $_POST['comment_case'];
$comment_position = $_POST['text_position'];
$background = $_POST['bgColor'];
$store_name = $_POST['store_name'];
$text_color = $_POST['text_color'];
$comment_link = $_POST['comment_link'];

$sql_update = 'UPDATE announcement_bar SET comment = "'.$comment.'", comment_link = "'.$comment_link.'", comment_case = "'.$comment_case.'", comment_position = "'.$comment_position.'", text_color = "'.$text_color.'", background = "'.$background.'" WHERE shop = "'.$store_name.'" ';

$result = mysqli_query($conn, $sql_update);
if ($conn->query($sql_update) === TRUE) {
    echo "<div class='data-updated'>New record created successfully</div>";
} else {
    echo "Error: " . $sql_update . "<br>" . $conn->error;
}
?>

