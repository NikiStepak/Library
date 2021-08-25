<?php
require_once 'connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    if(isset($books)){
        if(isset($booksNumber)){
            $sqlNumber = "SELECT COUNT(*) AS NUMBER FROM books";
            
            $result = mysqli_query($link, $sqlNumber);
            if(!$result){
                throw new Exception(mysqli_error($link));
            }
        
            echo '<div class="row_header"><header><h2>Books<span class="black">[';
            
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
        
        $sql = "SELECT books.*, publishers.PUBLISHER, languages.LANGUAGE, genres.GENRE, series.SERIES, book_author.AUTHOR_ID FROM books "
                . "INNER JOIN publishers ON publishers.ID=books.PUBLISHER_ID "
                . "INNER JOIN languages ON languages.ID=books.LANGUAGE_ID "
                . "INNER JOIN genres ON genres.ID=books.GENRE_ID "
                . "INNER JOIN series ON series.ID=books.SERIES_ID "
                . "INNER JOIN book_author ON book_author.BOOK_ID=books.ID";
        
        $result = mysqli_query($link, $sql);
        if(!$result){
            throw new Exception(mysqli_error($link));
        }
        
        if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_assoc($result)){
                echo '<div class="row_list">';
                echo '<label class="icon"><a class="black" href="../db/delete.php?id='.$row['ID'].'&table='.$books.'"><i class="material-icons">delete</i><span class="tooltip_top w-150">Delete the book</span></a></label>';
                echo '<label class="icon"><a><i class="material-icons">edit</i><span class="tooltip_top w-150">Edit the book</span></a></label>';
                echo '<div class="column"><a href="book.php?book_id='.$row['ID'].'"><img src="../db/image.php?id='.$row['ID'].'&table=books" alt="book#'.$row['ID'].'" class="small"/></a>';
                echo '</div><div class="column_margin-top"><header><h3><a href="book.php?book_id='.$row['ID'].'" class="black">'.$row['TITLE'].'</a></h3></header>';
                echo '<div class="column_margin-top">';
                echo '<span class="black">Author:</span><a href="author.php?author_id='.$row['AUTHOR_ID'].'" class="color">Cassandra Clare</a><br>';
                if($row['SERIES_ID']!=0){
                    echo '<span class="black">Series:</span>'.$row['SERIES'].'<br>';
                }
                echo '<span class="black">Genre:</span>'.$row['GENRE'].'<br>';
                echo '</div><div class="column_margin-top"><div class="ma"><div class="colorw ma">';
                echo 'aaa';                
                echo '</div></div></div></div></div>';    
            }
        }
        
        mysqli_free_result($result);
    }
    
    mysqli_close($link);
} catch (Exception $e) {
    echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
    echo '<br>Developer information: '.$e;     
}
?>



