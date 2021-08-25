<?php
session_start();

if(!filter_has_var(INPUT_GET, 'book_id')){
    header("Location: ../index.php");
    exit();
}

require_once '../db/connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    $sql = "SELECT books.*, genres.GENRE, publishers.PUBLISHER, languages.LANGUAGE, series.SERIES, book_author.AUTHOR_ID FROM books "
            . "INNER JOIN genres ON books.GENRE_ID=genres.ID "
            . "INNER JOIN publishers ON books.PUBLISHER_ID=publishers.ID "
            . "INNER JOIN languages ON books.LANGUAGE_ID=languages.ID "
            . "INNER JOIN series ON books.SERIES_ID=series.ID "
            . "INNER JOIN book_author ON book_author.BOOK_ID=books.ID "
            . "WHERE books.ID=".filter_input(INPUT_GET, 'book_id', FILTER_SANITIZE_NUMBER_INT);
    
    $result = mysqli_query($link, $sql);
    if(!$result){
        throw new Exception(mysqli_error($link));
    }
    
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
    }
    
    mysqli_free_result($result);
    
    mysqli_close($link);
} catch (Exception $e) {
    echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
    echo '<br>Developer information: '.$e;    
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Niki"><!-- Author of a page -->
        <!--<meta http-equiv="refresh" content="30"> Refresh document every 30s -->       
        <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- setting the viewport to make website look good on all devices -->  
        <title>Library - <?php echo $row['TITLE']; ?></title>
        <link rel="stylesheet" href="../style/main_style.css" type="text/css">
        <link rel="stylesheet" href="../style/text_style.css" type="text/css">
        <link rel="stylesheet" href="../style/table_style.css" type="text/css">
        <link rel="stylesheet" href="../style/image_style.css" type="text/css">
        <link rel="stylesheet" href="../style/tooltip_style.css" type="text/css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <!-- <script> - JavaScript -->             
    </head>
    <body>
        <nav class="sign">
            <ol>
                <?php
                if((isset($_SESSION['logged'])) && ($_SESSION['logged']==true)){
                    echo'<li><a href="#" class="white">'.$_SESSION['name'].'</a>';
                    echo '<ul>';
                    echo '<li><a href="../sign_nav/account.php" class="white">my account</a></li>' ;
                    echo '<li><a href="../sign_nav/signOut.php" class="white" class="white">Sign Out</a></li>' ;
                    echo '</ul></li>';
                }
                elseif ((isset($_SESSION['librarian'])) && ($_SESSION['librarian']==true)) {
                    echo'<li><a href="#" class="white">'.$_SESSION['name'].'</a>';
                    echo '<ul>';
                    echo '<li><a href="../sign_nav/signOut.php" class="white">Sign Out</a></li>' ;
                    echo '</ul></li>';
                }
                else {
                    echo '<li><a href="../sign_nav/signUp.php" class="white">Sign up</a></li>';
                    echo '<li><a href="../sign_nav/signIn.php" class="white">Sign in</a></li>';                      
                }
                ?>
            </ol>
        </nav>
        
        <header>
            <h1><img src="../image/logo.png" alt="Logo">LIBRARY</h1>     
        </header>
        
        <nav class="main">
            <ol>
                <li><a href="../index.php" class="white">Home</a>
                <li><a href="#" class="white">catalog</a>
                    <ul>
                        <li><a href="books.php" class="white">books</a></li>
                        <li><a href="authors.php" class="white">authors</a></li>
                    </ul>
                </li>
                <?php
                if((isset($_SESSION['librarian'])) && ($_SESSION['librarian']==true)){
                    echo '<li><a href="#" class="white">librarian</a>';
                    echo '<ul>';
                    echo '<li><a href="signLibrarian.php" class="white">Add librarian</a></li>' ;
                    echo '<li><a href="addBook.php" class="white">Add book</a></li>' ;
                    echo '</ul></li>';
                }
                ?>
                <li><a href="contact.php" class="white">contact</a></li>
            </ol>
        </nav>
        
        <div id="container_main">
            <div class="row row_padding">
                <div class="row row_color">
                    <label class="icon">
                        <i class="material-icons">edit</i>
                        <span class="tooltip">Edit</span>
                    </label>
                    <div class="column">
                        <?php echo '<img src="../db/image.php?id='.$row['ID'].'&table=books" class="big">';?>
                    </div>
                    <div class="column_main_list">
                        <div class="row_header">
                            <header>
                                <h2><?php echo $row['TITLE'];?></h2>
                            </header>
                        </div>
                        <div class="row">
                            <div class="column_half">
                                <p class="lines">
                                    <span class="black">Author:</span><a href="author.php?author_id=<?php echo $row['AUTHOR_ID'];?>" class="color">Cassandra Clare</a><br>
                                    <?php if($row['SERIES_ID']!=0){
                                        echo'<span class="black">Series:</span>'.$row['SERIES'].'<br/>';
                                    }?>
                                    <span class="black">Genre:</span><?php echo $row['GENRE']; ?><br>
                                    <span class="black">Original Title:</span><?php echo $row['ORIGINAL_TITLE']; ?><br>
                                </p>
                                <p class="lines">
                                    <span class="black">Publisher:</span><?php echo $row['PUBLISHER']; ?><br>
                                    <span class="black">Date of publication:</span><?php echo $row['PUBLICATION_DATE']; ?><br>
                                    <span class="black">Language:</span><?php echo $row['LANGUAGE']; ?><br>
                                    <span class="black">Pages:</span><?php echo $row['PAGES']; ?><br>
                                    <span class="black">ISBN:</span><?php echo $row['ISBN']; ?><br>
                                    <span class="black">Tags:</span>anioły demony<br>
                                </p>
                            </div>
                            <div class="column_half">
                                <div class="ratings_block">
                                    <h4 class="center">Avarage rating:</h4>
                                    <h4 class="center">
                                        <span class="color40">
                                            <i class="material-icons">grade</i>
                                            <?php 
                                            if($row['RATINGS_AMOUNT']>0){
                                                echo round($row['RATINGS_SUM']/$row['RATINGS_AMOUNT'], 2);
                                            }
                                            else {echo '0';} 
                                            ?>
                                        </span>/ 10
                                    </h4>   
                                    <p class="line"><?php echo $row['RATINGS_AMOUNT']; ?> ratings</p>
                                </div>
                                <div class="ratings_block">
                                    <h4 class="center">Rate this book:</h4>  
                                    <p class="line">
                                        <i class="material-icons md-20">grade</i>
                                        <i class="material-icons md-20">grade</i>
                                        <i class="material-icons md-20">grade</i>
                                        <i class="material-icons md-20">grade</i>
                                        <i class="material-icons md-20">grade</i>
                                        <i class="material-icons md-20">grade</i>
                                        <i class="material-icons md-20">grade</i>
                                        <i class="material-icons md-20">grade</i>
                                        <i class="material-icons md-20">grade</i>
                                        <i class="material-icons md-20">grade</i>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>