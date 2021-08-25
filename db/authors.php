<?php
require_once 'connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    if(isset($authors)){
        if(isset($authorsNumber)){
            $sqlNumber = "SELECT COUNT(*) AS NUMBER FROM authors";
            
            $result = mysqli_query($link, $sqlNumber);
            if(!$result){
                throw new Exception(mysqli_error($link));
            }
        
            echo '<div class="row_header"><header><h2>Authors<span class="black">[';
            
            if(mysqli_num_rows($result)>0){
                $row = mysqli_fetch_assoc($result);
                echo $row['NUMBER'];
            }
            else{
                echo 0;
            }
            
            echo ']</span></h2></header></div>';
        
            mysqli_free_result($result);
        }
        
        $sql = "SELECT authors.*, nationalities.NATIONALITY, genres.GENRE, COUNT(*) AS NUMBER FROM book_author "
                . "INNER JOIN authors ON authors.ID=book_author.AUTHOR_ID "
                . "INNER JOIN nationalities ON nationalities.ID=authors.NATIONALITY_ID "
                . "INNER JOIN books ON books.ID=book_author.BOOK_ID "
                . "INNER JOIN genres ON genres.ID=books.GENRE_ID "
                . "GROUP BY book_author.AUTHOR_ID";
        
        $result = mysqli_query($link, $sql);
        if(!$result){
            throw new Exception(mysqli_error($link));
        }
        
        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                echo '<div class="row_list">';
                echo '<label class="icon"><a><i class="material-icons">edit</i><span class="tooltip_top w-150">Edit the book</span></a></label>';
                echo '<div class="column">';
                echo '<a href="author.php?author_id='.$row['ID'].'"><img src="../db/image.php?id='.$row['ID'].'&table=authors" alt="book#'.$row['ID'].'" class="small"/></a>';
                echo '</div><div class="column_margin-top"><header><h3><a href="author.php?author_id='.$row['ID'].'" class="black">'.$row['FULL_NAME'].'</a></h3></header>';
                echo '<div class="column_margin-top">';
                echo '<span class="black">Books:</span>'.$row['NUMBER'].'<br>';
                echo '<span class="black">Genre:</span>'.$row['GENRE'].'<br>';
                echo '</div><div class="column_margin-top"><div class="ma"><div class="colorw ma">';
                echo 'aaa';                
                echo '</div></div></div></div></div>';    
            }
        }
        
        mysqli_free_result($result);
    }
    else if(isset ($books)){
        $sql = "SELECT authors.FULL_NAME, COUNT(*) FROM book_author "
                . "INNER JOIN authors ON authors.ID=book_author.AUTHOR_ID "
                . "GROUP BY book_author.AUTHOR_ID "
                . "LIMIT 4";
        
        $result = mysqli_query($link, $sql);
        if(!$result){
            throw new Exception(mysqli_error($link));
        }

        $num_rows = mysqli_num_rows($result);
        if($num_rows > 1){
            echo '<div class="row_filter"><div class="column_main">';
            echo '<h4>Authors</h4><div class="column_list">';
            
            while($row = mysqli_fetch_assoc($result)){
                echo '<label class="pointer"><input type="checkbox"/>'.$row['FULL_NAME'].'</label><br/>';
            }
            
            echo '</div></div></div>';
        }
    
        mysqli_free_result($result);
    }
    else {
        $sql = "SELECT * FROM authors";
        echo $sql;
    }
       
    mysqli_close($link);
} catch (Exception $e) {
    echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
    echo '<br>Developer information: '.$e;     
}
?>



