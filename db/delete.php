<?php
require_once 'connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    if((filter_has_var(INPUT_GET, 'id')) && (filter_has_var(INPUT_GET, 'table'))){
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $table = filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING);
        
        $sql1 = "DELETE FROM book_author WHERE book_author.BOOK_ID=".$id;
        
        $result1 = mysqli_query($link, $sql1);
        if(!$result1){
            throw new Exception(mysqli_error($link));
        }
        
        $sql2 = "DELETE FROM ".$table." WHERE ".$table.".ID = ".$id;
        
        $result2 = mysqli_query($link, $sql2);
        if(!$result2){
            throw new Exception(mysqli_error($link));
        }
    }

    mysqli_close($link);
    
    header('Location: ../main_nav/books.php');
} catch (Exception $e) {
    echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
    echo '<br>Developer information: '.$e;     
}
?>