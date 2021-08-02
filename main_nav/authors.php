<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Library - Authors</title>
        <link rel="stylesheet" href="../style/main_style.css" type="text/css">
        <link rel="stylesheet" href="../style/mysql_style.css" type="text/css">
        <!-- <script> - JavaScript -->
        <meta charset="UTF-8">
        <meta name="author" content="Niki"><!-- Author of a page -->
        <!--<meta http-equiv="refresh" content="30"> Refresh document every 30s -->       
        <meta name="viewport" content="width-device-width, initial-scale=1.0"><!-- setting the viewport to make website look good on all devices -->                
    </head>
    <body>
        <nav class="sign">
            <ol>
                <?php
                if((isset($_SESSION['logged'])) && ($_SESSION['logged']==true)){
                    echo'<li><a href="#">'.$_SESSION['name'].'</a>';
                    echo '<ul>';
                    echo '<li><a href="../sign_nav/account.php">my account</a></li>' ;
                    echo '<li><a href="../sign_nav/signOut.php">Sign Out</a></li>' ;
                    echo '</ul></li>';
                }
                elseif ((isset($_SESSION['librarian'])) && ($_SESSION['librarian']==true)) {
                    echo'<li><a href="#">'.$_SESSION['name'].'</a>';
                    echo '<ul>';
                    echo '<li><a href="sign_nav/signOut.php">Sign Out</a></li>' ;
                    echo '</ul></li>';
                }
                else {
                    echo '<li><a href="../sign_nav/signUp.php">Sign up</a></li>';
                    echo '<li><a href="../sign_nav/signIn.php">Sign in</a></li>';                      
                }
                ?>
            </ol>
        </nav>
        
        <header>
            <img src="../image/logo.png" alt="Logo">LIBRARY     
        </header>
        
        <nav class="main">
            <ol>
                <li><a href="books.php">books</a></li>
                <li><a href="#">authors</a></li>
                <?php
                if((isset($_SESSION['librarian'])) && ($_SESSION['librarian']==true)){
                    echo '<li><a href="#">librarian</a>';
                    echo '<ul>';
                    echo '<li><a href="signLibrarian.php">Add librarian</a></li>' ;
                    echo '<li><a href="addBook.php">Add book</a></li>' ;
                    echo '</ul></li>';
                }
                ?>
                <li><a href="#">contact</a></li>
            </ol>
        </nav>
        
        <div id="container_main">
            <?php
            require_once '../connect.php';
            mysqli_report(MYSQLI_REPORT_STRICT);
            
            try {
                $link = mysqli_connect($host, $db_user, $db_password, $db_name);
                
                if(mysqli_connect_errno()){
                    throw new Exception(mysqli_connect_errno());
                }
                else{
                    $sql = "SELECT nationalities_languages.NAME, authors.* FROM authors INNER JOIN nationalities_languages ON authors.NATIONALITY=nationalities_languages.ID";
                    $result = mysqli_query($link, $sql);
                    if(!$result){
                        throw new Exception(mysqli_error($link));
                    }
                    
                    if(mysqli_num_rows($result)>0){
                        while($row = mysqli_fetch_assoc($result)){
                            echo '<div class="row">';
                            echo '<div class="column">';
                            echo '<img src="../image.php?id='.$row['ID'].'&table=authors" height=200px width=145px>';
                            echo '</div>';
                            echo '<div class="column">';
                            echo '<h1>'.$row['FULL_NAME'].'</h1>';
                            echo '<p><span>Nationality:</span> '.$row['NAME'].'<br>';
                            
                            echo '<span>Genre:</span> ';
                            $sql = "SELECT DISTINCT genres.GENRE FROM book_author "
                                    . "INNER JOIN books ON book_author.BOOK_ID=books.ID "
                                    . "INNER JOIN genres ON books.GENRE_ID=genres.ID "
                                    . "INNER JOIN authors ON book_author.AUTHOR_ID=authors.ID "
                                    . "WHERE book_author.AUTHOR_ID=1 "
                                    . "GROUP BY genres.GENRE";
                            
                            $genre_result = mysqli_query($link, $sql);
                            if(!$genre_result){
                                throw new Exception(mysqli_error($link));
                            }
                            if(mysqli_num_rows($genre_result)>0){
                                $i = false;
                                while($genre = mysqli_fetch_assoc($genre_result)){
                                    if($i){
                                        echo ',';
                                    }
                                    echo ' '.$genre['GENRE'];
                                    if(!$i){
                                        $i = true;
                                    }
                                }
                            }
                            mysqli_free_result($genre_result);
                            echo '</p></div></div>';
                        }
                    }
                    
                    mysqli_close($link);
                }
            } catch (Exception $ex) {
                echo 'Error! Failed to connect. Try again later.';
                echo 'Developer information: '.$ex;
            }
            ?>
        </div>
        
    </body>
</html>
