<?php
require_once 'connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);
try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);

    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }

    if((filter_has_var(INPUT_GET, 'id')) && (filter_has_var(INPUT_GET, 'table'))){
        $id = mysqli_real_escape_string($link, filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT));
        $table = mysqli_real_escape_string($link, filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING));
        $sql = "SELECT IMAGE FROM $table WHERE ID='$id'";
        $result = mysqli_query($link, $sql);
        if(!$result){
            throw new Exception(mysqli_error($link));
        }
    
        if(mysqli_num_rows($result)>0){
            while ($row = mysqli_fetch_assoc($result)){
                $imageData = $row["IMAGE"];
            }
            header("content-type: image/jpeg");
            echo $imageData;
        }
    }
    else {
        throw "";
    }

    mysqli_close($link);
   
} catch (Exception $ex) {
    echo 'Error! Failed to connect. Try again later.';
    echo 'Developer information: '.$ex;
}
?>