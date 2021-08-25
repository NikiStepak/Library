<?php

require_once 'connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    if(filter_has_var(INPUT_GET, 'term')){
        $sql = "SELECT * FROM tags WHERE tags.TAG LIKE '%".filter_input(INPUT_GET, 'term')."%' "
                . "ORDER BY tags.TAG ASC LIMIT 2";       
        
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
                $r['label'] = $row['TAG'];
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
        $sql = "SELECT tags.TAG, COUNT(*) FROM book_tag "
                . "INNER JOIN tags ON tags.ID=book_tag.TAG_ID "
                . "GROUP BY book_tag.TAG_ID "
                . "LIMIT 4";
        
        $result = mysqli_query($link, $sql);
        if(!$result){
            throw new Exception(mysqli_error($link));
        }

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 1){
            echo '<div class="row_filter"><div class="column_main">';
            echo '<h4>Tags</h4><div class="column_list">';
            
            while($row = mysqli_fetch_assoc($result)){
                echo '<label class="pointer"><input type="checkbox"/>'.$row['TAG'].'</label><br/>';
            }
            
            echo '</div></div></div>';
        }
    
        mysqli_free_result($result);
    }
    else {
        $sql = "SELECT * FROM tags";
        echo $sql;
    }
        
    mysqli_close($link);
    
} catch (Exception $e) {
    echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
    echo '<br>Developer information: '.$e; 
}
?>
