<?php
session_start();

/*if((!(isset($_SESSION['librarian']))) && (!($_SESSION['librarian']==true))){
    header('Location: ../index.php');
    exit();
          echo '<script>alert("a")</script>';
}*/

if(filter_has_var(INPUT_POST, 'addBook')){
    
    //$validation = false;
    
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $authorsID = filter_input(INPUT_POST, 'authorsID', FILTER_SANITIZE_NUMBER_INT);
    $seriesID = filter_input(INPUT_POST, 'seriesID', FILTER_SANITIZE_NUMBER_INT);
    $original = filter_input(INPUT_POST, 'original', FILTER_SANITIZE_STRING);
    $publisherID = filter_input(INPUT_POST, 'publisherID', FILTER_SANITIZE_NUMBER_INT);  
    $languageID = filter_input(INPUT_POST, 'languageID', FILTER_SANITIZE_NUMBER_INT);  
    $genreID = filter_input(INPUT_POST, 'genreID', FILTER_SANITIZE_NUMBER_INT);        
    $publication = filter_input(INPUT_POST, 'publication');
    $isbn = filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_NUMBER_INT);
    $pages = filter_input(INPUT_POST, 'pages', FILTER_SANITIZE_NUMBER_INT);
    
    echo '<script>alert("'.$title.', '.$authorsID.', '.$seriesID.', '.$original.', '
            .$publisherID.', '.$languageID.', '.$genreID.', '.$publication.', '
            .$isbn.', '.$pages.'")</script>';
    
    //Remember written data
    $_SESSION['r_title'] = $title;
    $_SESSION['r_original'] = $original;
    $_SESSION['r_isbn'] = $isbn;
    
    /*require_once '../db/connect.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try{
        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
        if(mysqli_connect_errno()){
            throw new Exception(mysqli_connect_errno());
        }
        else {     
            if(file_exists($_FILES["image"]["tmp_name"])){
                $imageData = mysqli_real_escape_string($link, file_get_contents($_FILES["image"]["tmp_name"]));
                $imageType = mysqli_real_escape_string($link, $_FILES["image"]["type"]);    
                if(substr($imageType, 0,5) != "image"){
                    $validation = false;
                    $_SESSION['e_image']="Only images are allowed";
                }
            }
            else{
                $validation = false;
                $_SESSION['e_image']="Select image";
            }
            
            // if all data are correct - create new user
            if($validation==true){
                $sql = "INSERT INTO books VALUES(NULL,'$title','$isbn','$publisher','$original','$language','$imageData', '$genre')"; 
                if(mysqli_query($link, $sql)){
                    $_SESSION['addBook'] = true;
                    header('Location: books.php');
                }
                else {
                    throw new Exception(mysqli_error($link));
                }
            }
            
            mysqli_close($link);
        }
    } catch (Exception $e){
        echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
        echo '<br>Developer information: '.$e;
    }*/
}

if(filter_has_var(INPUT_POST, 'addAuthor')){
    
    
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $nationalityID = filter_input(INPUT_POST, 'nationalityID', FILTER_SANITIZE_NUMBER_INT);
    $image = null;
    $birth = filter_input(INPUT_POST, 'birth');
    if ($birth==''){
        $birth = 'NULL';
    }     
    
    require_once '../db/connect.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try{
        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
        if(mysqli_connect_errno()){
            throw new Exception(mysqli_connect_errno());
        }
        
        if($nationalityID==0){
            $nationality = filter_input(INPUT_POST, 'nationality', FILTER_SANITIZE_STRING);
            $sql1 = "INSERT INTO nationalities_languages VALUES(NULL, '$nationality')";
            if(!mysqli_query($link, $sql1)){
                throw new Exception(mysqli_error($link));
            }
            else {
                $nationalityID = mysqli_insert_id($link);
            }
        }
        
        $sql2 = "INSERT INTO authors VALUES(NULL,'$name','$nationalityID', NULL, $birth)"; 
        if(mysqli_query($link, $sql2)){
            header('Location: addBook.php');
        }
        else {
            throw new Exception(mysqli_error($link));
        }
        
        mysqli_close($link);
    } catch (Exception $e){
        echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
        echo '<br>Developer information: '.$e;
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Niki"><!-- Author of a page -->
        <!--<meta http-equiv="refresh" content="30"> Refresh document every 30s -->       
        <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- setting the viewport to make website look good on all devices -->  
        <title>Library - Add Book</title>
        <link rel="stylesheet" href="../style/main_style.css" type="text/css">
        <link rel="stylesheet" href="../style/text_style.css" type="text/css">
        <link rel="stylesheet" href="../style/table_style.css" type="text/css">
        <link rel="stylesheet" href="../style/image_style.css" type="text/css">
        <link rel="stylesheet" href="../style/tooltip_style.css" type="text/css">
        <link rel="stylesheet" href="../style/modal_style.css" type="text/css">
        <link rel="stylesheet" href="../style/form_style.css" type="text/css">        
        <link rel="stylesheet" href="../style/ui_style.css" type="text/css">        
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <!-- <script> - JavaScript -->    
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="../js/auto.js"></script>
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
                    echo '<li><a href="#" class="white">Add book</a></li>' ;
                    echo '</ul></li>';
                }
                ?>
                <li><a href="contact.php" class="white">contact</a></li>
            </ol>
        </nav>
        
        <form id="addBookForm" method="POST"></form>
        <form id="addAuthorForm" method="POST"></form>
        
        <div id="container_main">
            <div class="row row_padding">
                <div class="row row_color mb-0">
                    <button form="addBookForm" name="addBook" class="icon">
                        <i class="material-icons">check</i>
                        <span class="tooltip w-150">Add the book</span>
                    </button>
                    <div class="column">
                        <input form="addBookForm" type="image" src="../image/new_book.jpg" alt="add book" class="big border">
                    </div>
                    <div class="column_main_list mb-0" style="margin-top: 0px; padding-top: 0px;">
                        <div class="row_header">
                            <header>
                                <h2><input name="title" form="addBookForm" type="text" placeholder="Enter the book's title..." class="title" required></h2>
                            </header>
                        </div>
                        <div class="row mb-0">
                            <div class="column_half mb-0">
                                <p class="lines_input">
                                    <input id="authors_input" name="authors" form="addBookForm" style="width: 250px;" type="text" placeholder="Enter the book's authors..." required>
                                    <input id="authorsID_auto" name="authorsID" form="addBookForm" type="hidden" value="0">
                                    <label class="icon">
                                        <a class="black" href="#addAuthorModal">
                                            <i class="material-icons">add</i>
                                            <span class="tooltip w-150">Add new author</span>
                                        </a>
                                    </label><br>
                                    <input id="series_input" name="series" form="addBookForm" type="text" placeholder="Enter the book's series...">
                                    <input id="volume" style="margin-left: 15px; width: 40px;" min="1" type="hidden" placeholder="vol.">
                                    <input id="seriesID_auto" name="seriesID" form="addBookForm" type="hidden" value="0"><br/>
                                    <input id="genre_input" name="genre" form="addBookForm" type="text" placeholder="Enter the book's genre..." required>
                                    <input id="genreID_auto" name="genreID" form="addBookForm" type="hidden" value="0"><br>
                                    <input id="original_input" name="original" form="addBookForm" type="text" placeholder="Enter the book's original title..."><br>
                                </p>
                                <p class="lines_input">
                                    <input id="publisher_input" name="publisher" form="addBookForm" type="text" placeholder="Enter the book's publisher..." required>
                                    <input id="publisherID_auto" name="publisherID" form="addBookForm" type="hidden" value="0"><br>
                                    <input id="publication_input" name="publication" form="addBookForm" type="text" onfocus="(this.type='date')" placeholder="Enter the book's date of publication..." required><br>
                                    <input name="language" form="addBookForm" id="language_input" type="text" placeholder="Enter the book's language..." required>
                                    <input name="languageID" form="addBookForm" id="languageID_auto" type="hidden" value="0"><br>
                                    <input id="pages_input" name="pages" form="addBookForm" type="number" placeholder="Enter the amount of book's pages..." required><br>
                                    <input id="isbn_input" name="isbn" form="addBookForm" type="text" placeholder="Enter the book's ISBN..." required><br>
                                    <input id="tags_input" name="tags" form="addBookForm" type="text" placeholder="Enter the book's tags..."><br>
                                </p>
                            </div>
                            <div class="column_half mb-0">
                                <p class="lines">
                                    <span id="authors_span"></span><span id="authors_text"></span>
                                    <span id="series_span"></span><span id="series_text"></span>
                                    <span id="genre_span"></span><span id="genre_text"></span>
                                    <span id="original_span"></span><span id="original_text"></span>
                                </p>
                                <p class="lines">
                                    <span id="publisher_span"></span><span id="publisher_text"></span>
                                    <span id="publication_span"></span><span id="publication_text"></span>
                                    <span id="language_span"></span><span id="language_text"></span>
                                    <span id="pages_span"></span><span id="pages_text"></span>
                                    <span id="isbn_span"></span><span id="isbn_text"></span>
                                    <span id="tags_span"></span><span id="tags_text"></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        
        <div id="addAuthorModal" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <a href="#" class="closebtn">×</a>
                    <div class="modal_container">
                        <div class="row">
                            <div class="column">
                                <input form="addAuthorForm" type="image" src="../image/new_author.jpg" alt="add author image" class="small border">
                            </div>
                            <div class="column_main_list mb-0">
                                <div class="row_header">
                                    <header>
                                        <h2><input name="name" form="addAuthorForm" type="text" placeholder="Enter the author's full name..." class="title" required></h2>
                                    </header>
                                </div>
                                <div class="row mb-0">
                                    <div class="column_half mb-0">
                                        <p class="lines_input">
                                            <input id="nationality_auto" name="nationality" form="addAuthorForm" type="text" placeholder="Enter the author's nationality..." required>
                                            <input id="nationalityID_auto" name="nationalityID" form="addAuthorForm" type="hidden" value="0"> <br/>
                                            <input name="birth" form="addAuthorForm" type="text" onfocus="(this.type='date')" placeholder="Enter the author's birth date..."><br>
                                        </p>
                                    </div>
                                    <div class="column_half mb-0">
                                        <p class="lines">
                                            <span class="black">Nationality:</span><br>
                                            <span class="black">Birth date:</span><br/>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <button form="addAuthorForm" name="addAuthor" class="icon">
                                <i class="material-icons">check</i>
                                <span class="tooltip w-150">Add the author</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>