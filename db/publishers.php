<?php

require_once 'connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    if(filter_has_var(INPUT_GET, 'term')){
        $sql = "SELECT * FROM publishers WHERE publishers.PUBLISHER LIKE '%".filter_input(INPUT_GET, 'term')."%' "
                . "ORDER BY publishers.PUBLISHER LIMIT 2";       
    
        $result = mysqli_query($link, $sql);
        if(!$result){
            throw new Exception(mysqli_error($link));
        }

        $num_rows = mysqli_num_rows($result);
        $data = array();
        if($num_rows > 0){
            foreach ($result as $row) {
                $r = array();
                $r['value'] = $row['ID'];
                $r['label'] = $row['PUBLISHER'];
                $data[] = $r;            
            }
        }
        $r = array();
        $r['id'] = 0;
        $r['label'] = filter_input(INPUT_GET, 'term', FILTER_SANITIZE_STRING);
        $data[] = $r; 
    
        echo json_encode($data);
    
        mysqli_free_result($result);
        
        mysqli_close($link);
    }
    else if(isset ($books) | isset ($authors)){
        $sql = "SELECT publishers.PUBLISHER, COUNT(*) FROM books "
                . "INNER JOIN publishers ON publishers.ID=books.PUBLISHER_ID "
                . "GROUP BY books.PUBLISHER_ID "
                . "LIMIT 4";
        
        $result = mysqli_query($link, $sql);
        if(!$result){
            throw new Exception(mysqli_error($link));
        }

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 1){
            echo '<div class="row_filter"><div class="column_main">';
            echo '<h4>Publishers</h4><div class="column_list">';
            
            while($row = mysqli_fetch_assoc($result)){
                echo '<label class="pointer"><input type="checkbox"/>'.$row['PUBLISHER'].'</label><br/>';
            }
            
            echo '</div></div></div>';
        }
    
        mysqli_free_result($result);
    }
    else {
        $sql = "SELECT * FROM publishers";
        echo $sql;
    }
    
} catch (Exception $e) {
    echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
    echo '<br>Developer information: '.$e; 
}
?>
