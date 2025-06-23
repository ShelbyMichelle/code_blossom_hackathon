<?php

    $con=new mysqli('localhost',
                    'root',
                    '',
                    'newcrud_db');

    if(!$con){
        die(mysqli_error($con));
    }

?>