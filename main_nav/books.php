<?php
session_start();

require_once '../db/connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    $sql_max = "SELECT YEAR(MAX(books.PUBLICATION_DATE)) AS YEAR FROM books ";
    $sql_min = "SELECT YEAR(MIN(books.PUBLICATION_DATE)) AS YEAR FROM books ";
    
    $result_min = mysqli_query($link, $sql_min);
    if(!$result_min){
        throw new Exception(mysqli_error($link));
    }

    if(mysqli_num_rows($result_min) > 0){
        $row = mysqli_fetch_assoc($result_min);
        $min = $row['YEAR'];                                   
    }
    mysqli_free_result($result_min);
    
    $result_max = mysqli_query($link, $sql_max);
    if(!$result_max){
        throw new Exception(mysqli_error($link));
    }
    
    if(mysqli_num_rows($result_max) > 0){
        $row = mysqli_fetch_assoc($result_max);
        $max = $row['YEAR'];                                   
    }
    mysqli_free_result($result_max);
    
    mysqli_close($link);    
} catch (Exception $e) {
    echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
    echo '<br>Developer information: '.$e; 
}

$books='books';
$booksNumber=true;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Niki"><!-- Author of a page -->
        <!--<meta http-equiv="refresh" content="30"> Refresh document every 30s -->       
        <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- setting the viewport to make website look good on all devices -->  
        <title>Library - Books</title>
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
                        <li><a href="#" class="white">books</a></li>
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
            <div class="row" style="width: 100%">
                <label class="icon">
                    <a href="addBook.php" class="black">
                        <i class="material-icons">add</i>
                        <span class="tooltip">Add a book</span>
                    </a>
                </label>
                <div class="column_main">
                    <div class="column_color">
                        <div class="row_header">                           
                            <header>                               
                                    <h2>Filters<span class="black pointer">[clean]</span></h2>                              
                            </header>
                        </div>
                        <?php include '../db/genres.php';?>
                        <?php include '../db/tags.php';?>
                        <div class="row_filter">
                            <div class="column_main" style="padding-right: 0px;">
                                <h4>Average ratings</h4>
                                <div class="column_list">
                                    <input type="range" style="width: 200px;" max="10" min="0" />
                                </div>
                            </div>
                        </div>
                        <div class="row_filter">
                            <div class="column_main">
                                <h4>Date of publication</h4>
                                <div class="column_list">
                                    <div class="row" style="margin-left: 10px;">
                                        <div class="column" style="text-align: center;">
                                            From: <br/>
                                            <input placeholder="Year" style="padding: 5px; margin-top: 5px; width: 50px" min="<?php echo $min?>" max="<?php echo $max?>" type="number"/>
                                        </div>
                                        <div class="column" style="text-align: center;">
                                            To: <br/>
                                            <input placeholder="Year" style="padding: 5px; margin-top: 5px; width: 50px" min="<?php echo $min?>" max="<?php echo $max?>" type="number"/>
                                        </div>
                                    </div>
                                    <div class="row" style=" text-align: center; margin-left: -15px;">
                                        <br/>
                                        In: <input placeholder="Year" style="padding: 5px; margin-top: 5px; width: 50px" min="<?php echo $min?>" max="<?php echo $max?>" type="number"/>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php include '../db/authors.php';?>
                        <?php include '../db/languages.php';?>
                        <?php include '../db/publishers.php';?>
                    </div>
                </div>
                <div class="column_main_list">
                    <div class="column_list">
                        <?php include '../db/books.php'?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>