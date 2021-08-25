<?php

require_once '../db/connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    if(filter_has_var(INPUT_GET, 'term')){
        $sql = "SELECT * FROM genres WHERE genres.GENRE LIKE '%".filter_input(INPUT_GET, 'term')."%' "
                . "ORDER BY genres.GENRE";       
        
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
                $r['label'] = $row['GENRE'];
                $data[] = $r;            
            }
        }
        else {
            $data['id'] = 0;
            $data['label'] = '';
        }
    
        echo json_encode($data);
    
        mysqli_free_result($result);
    }
    else if(isset ($m)){
        $sql = "SELECT genres.GENRE, COUNT(*) AS cNUMBER FROM books "
                . "INNER JOIN genres ON genres.ID=books.GENRE_ID "
                . "GROUP BY books.GENRE_ID "
                . "ORDER BY cNUMBER DESC "
                . "LIMIT 5";
        
        $result = mysqli_query($link, $sql);
        if(!$result){
            throw new Exception(mysqli_error($link));
        }

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 0){
            while($row = mysqli_fetch_assoc($result)){
                echo '<label class="checkbox"><input type="checkbox"/>'.$row['GENRE'].'</label><br/>';
            }
        }
    
        mysqli_free_result($result);
    }
    else {
        $sql = "SELECT * FROM genres "
                . "ORDER BY genres.GENRE";
        echo $sql;
    }
        
    mysqli_close($link);
    
} catch (Exception $e) {
    echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
    echo '<br>Developer information: '.$e; 
}
?>
