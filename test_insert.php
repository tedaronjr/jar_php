<?php

if(isset($_POST["subject"]))

{

include("test_connect.php");

$subject = ($con, $_POST["subject"]);

$comment = ($con, $_POST["comment"]);

$query = "INSERT INTO comments(comment_subject, comment_text)VALUES ('$subject', '$comment')";

mysqli_query($con, $query);

}

?>