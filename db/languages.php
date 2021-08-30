<?php

require_once 'connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    if(filter_has_var(INPUT_GET, 'term')){
        $sql = "SELECT * FROM languages WHERE languages.LANGUAGE LIKE '%".filter_input(INPUT_GET, 'term')."%' "
                . "ORDER BY languages.LANGUAGE LIMIT 2";
        
        $result = mysqli_query($link, $sql);
        if(!$result){
            throw new Exception(mysqli_error($link));
        }

        $num_rows = mysqli_num_rows($result);
        $data = array();    
        if($num_rows > 0){
            foreach ($result as $row) {
                $r = array();
                $r['id'] = $row['ID'];
                $r['label'] = $row['LANGUAGE'];
                $data[] = $r;            
            }
        }
        $r = array();
        $r['id'] = 0;
        $r['label'] = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        $data[] = $r; 
    
        echo json_encode($data);
    
        mysqli_free_result($result);
    }
    else if(isset ($books) | isset ($authors)){
        $sql = "SELECT languages.LANGUAGE, COUNT(*) FROM books "
                . "INNER JOIN languages ON languages.ID=books.LANGUAGE_ID "
                . "GROUP BY books.LANGUAGE_ID "
                . "LIMIT 4";
        
        $result = mysqli_query($link, $sql);
        if(!$result){
            throw new Exception(mysqli_error($link));
        }

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 1){
            echo '<div class="row_filter"><div class="column_main">';
            echo '<h4>Languages</h4><div class="column_list">';
                                    
            while($row = mysqli_fetch_assoc($result)){
                echo '<label class="pointer"><input type="checkbox"/>'.$row['LANGUAGE'].'</label><br/>';
            }
            
            echo '</div></div></div>';
        }
    
        mysqli_free_result($result);
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
