<?php

require_once 'connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    if(filter_has_var(INPUT_GET, 'term')){
        $sql = "SELECT * FROM nationalities WHERE nationalities.NATIONALITY LIKE '%".filter_input(INPUT_GET, 'term')."%' "
                . "ORDER BY nationalities.NATIONALITY LIMIT 2";
        
        $result = mysqli_query($link, $sql);
        if(!$result){
            throw new Exception(mysqli_error($link));
        }

        $data = array();    
        if(mysqli_num_rows($result) > 0){
            foreach ($result as $row) {
                $r = array();
                $r['id'] = $row['ID'];
                $r['label'] = $row['NATIONALITY'];
                $data[] = $r;            
            }
        }
        
        $sql_check = "SELECT * FROM nationalities WHERE nationalities.NATIONALITY LIKE '".filter_input(INPUT_GET, 'term')."'";
        
        $result_check = mysqli_query($link, $sql_check);
        if(!$result_check){
            throw new Exception(mysqli_error($link));
        }

        if(mysqli_num_rows($result_check) == 0){
            $r = array();
            $r['id'] = 0;
            $r['label'] = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
            $data[] = $r; 
        }

        echo json_encode($data);
    
        mysqli_free_result($result);
        mysqli_free_result($result_check);
    }
    else {
        $sql = "SELECT * FROM languages "
                . "ORDER BY languages.LANGUAGE";
        echo $sql;
    }
    
    mysqli_close($link);
    
} catch (Exception $e) {
    echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
    echo '<br>Developer information: '.$e; 
}
?>
