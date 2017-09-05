<?php
$con = mysqli_connect("db","sponsoren_crm_user","sponsoren_crm_password","sponsoren_crm");

// Check connection
if (mysqli_connect_errno())
{
    echo "Failed to connect to MySQL: " . exit();
} else {
    echo "Connection erstellt";
}
?>
