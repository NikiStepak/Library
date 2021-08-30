<?php
session_start();

/*if((!(isset($_SESSION['librarian']))) && (!($_SESSION['librarian']==true))){
    header('Location: ../index.php');
    exit();
          echo '<script>alert("a")</script>';
}*/

$addBook = true;

if(filter_has_var(INPUT_POST, 'addBook')){
    
    //$validation = false;   
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $series = filter_input(INPUT_POST, 'series', FILTER_SANITIZE_STRING);
    $original = filter_input(INPUT_POST, 'original', FILTER_SANITIZE_STRING);
    $publisher = filter_input(INPUT_POST, 'publisher', FILTER_SANITIZE_STRING);  
    $language = filter_input(INPUT_POST, 'language', FILTER_SANITIZE_STRING);  
    $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_STRING);        
    $publication = filter_input(INPUT_POST, 'publication');
    $isbn = filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_NUMBER_INT);
    $pages = filter_input(INPUT_POST, 'pages', FILTER_SANITIZE_NUMBER_INT);
    
    //Remember written data
    $_SESSION['r_title'] = $title;
    $_SESSION['r_original'] = $original;
    $_SESSION['r_isbn'] = $isbn;
    
    if ($publication==''){
        $publication = 'NULL';
    }     
    
    require_once '../db/connect.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try{
        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
        if(mysqli_connect_errno()){
            throw new Exception(mysqli_connect_errno());
        }
            
        //series
        $sql_check_series = "SELECT * FROM series WHERE series.SERIES LIKE '$series'";
        $result_check_series = mysqli_query($link, $sql_check_series);
        if(!$result_check_series){
            throw new Exception(mysqli_error($link));
        }
         
        if(mysqli_num_rows($result_check_series)>0){
            $row = mysqli_fetch_assoc($result_check_series);
            $seriesID = $row['ID'];
        }
        elseif ($series=='') {
            $seriesID = '0';
        }
        else{
            $sql1 = "INSERT INTO series VALUES(NULL, '$series')";
            
            if(!mysqli_query($link, $sql1)){
                throw new Exception(mysqli_error($link));
            }
            else {
                $seriesID = mysqli_insert_id($link);
            }
        }
        mysqli_free_result($result_check_series);
        
        //publisher
        $sql_check_publisher = "SELECT * FROM publishers WHERE publishers.PUBLISHER LIKE '$publisher'";
        $result_check_publisher = mysqli_query($link, $sql_check_publisher);
        if(!$result_check_publisher){
            throw new Exception(mysqli_error($link));
        }
         
        if(mysqli_num_rows($result_check_publisher)>0){
            $row = mysqli_fetch_assoc($result_check_publisher);
            $publisherID = $row['ID'];
        }
        elseif ($publisher=='') {
            $publisherID = 'NULL';
        }
        else{
            $sql1 = "INSERT INTO publishers VALUES(NULL, '$publisher')";
            
            if(!mysqli_query($link, $sql1)){
                throw new Exception(mysqli_error($link));
            }
            else {
                $publisherID = mysqli_insert_id($link);
            }
        }
        mysqli_free_result($result_check_publisher);
        
        //language
        $sql_check_language = "SELECT * FROM languages WHERE languages.LANGUAGE LIKE '$language'";
        $result_check_language = mysqli_query($link, $sql_check_language);
        if(!$result_check_language){
            throw new Exception(mysqli_error($link));
        }
         
        if(mysqli_num_rows($result_check_language)>0){
            $row = mysqli_fetch_assoc($result_check_language);
            $languageID = $row['ID'];
        }
        elseif ($language=='') {
            $languageID = 'NULL';
        }
        else{
            $sql1 = "INSERT INTO languages VALUES(NULL, '$language')";
            
            if(!mysqli_query($link, $sql1)){
                throw new Exception(mysqli_error($link));
            }
            else {
                $languageID = mysqli_insert_id($link);
            }
        }
        mysqli_free_result($result_check_language);
        
        //genre
        $sql_check_genre = "SELECT * FROM genres WHERE genres.GENRE LIKE '$genre'";
        $result_check_genre = mysqli_query($link, $sql_check_genre);
        if(!$result_check_genre){
            throw new Exception(mysqli_error($link));
        }
         
        if(mysqli_num_rows($result_check_genre)>0){
            $row = mysqli_fetch_assoc($result_check_genre);
            $genreID = $row['ID'];
        }
        elseif ($genre=='') {
            $genreID = 'NULL';
        }
        else{
            $sql1 = "INSERT INTO genres VALUES(NULL, '$genre')";
            
            if(!mysqli_query($link, $sql1)){
                throw new Exception(mysqli_error($link));
            }
            else {
                $genreID = mysqli_insert_id($link);
            }
        }
        mysqli_free_result($result_check_genre);
        
        $validation = true;
        if(file_exists($_FILES["image"]["tmp_name"])){
            $imageData = mysqli_real_escape_string($link, file_get_contents($_FILES["image"]["tmp_name"]));
            $imageType = mysqli_real_escape_string($link, $_FILES["image"]["type"]);    
            if(substr($imageType, 0,5) != "image"){
                $validation = false;
            }
        }
        else{
            $validation = false;
        }
        
        if(!$validation){
            $sql = "INSERT INTO books VALUES(NULL,'$title','$isbn','$publisherID','$original','$languageID', LOAD_FILE('D:/A/XAMPP/xampp/htdocs/library/image/book.jpg'), '$genreID', '$seriesID','$publication', '$pages', '', 0, 0)";
        }
        else {
            $sql = "INSERT INTO books VALUES(NULL,'$title','$isbn','$publisherID','$original','$languageID','$imageData', '$genreID', '$seriesID','$publication', '$pages', '', 0, 0)";
        }
         
        if(mysqli_query($link, $sql)){
            $bookID = mysqli_insert_id($link);
        }
        else {
            throw new Exception(mysqli_error($link));
        }
        
        foreach ($_POST['authors'] as $author) {        
            $sql_check = "SELECT * FROM authors WHERE authors.FULL_NAME LIKE '$author'";
            $result_check = mysqli_query($link, $sql_check);
            if(!$result_check){
                throw new Exception(mysqli_error($link));
            }
            
            if(mysqli_num_rows($result_check)>0){
                $row = mysqli_fetch_assoc($result_check);
                $authorID = $row['ID'];
                $sql1 = "INSERT INTO book_author VALUES(NULL, '$bookID', '$authorID')";
                
                if(!mysqli_query($link, $sql1)){
                    throw new Exception(mysqli_error($link));
                }
            }
            elseif ($author=='') {
                echo '<script>alert("author")</script>';
                exit();
            }

            mysqli_free_result($result_check);
        }
             
        mysqli_close($link);
    
    } catch (Exception $e){
        echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
        echo '<br>Developer information: '.$e;
    }
}

if(filter_has_var(INPUT_POST, 'addAuthor')){
       
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $nationality = filter_input(INPUT_POST, 'nationality', FILTER_SANITIZE_STRING);
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
        
        $sql_check = "SELECT * FROM nationalities WHERE nationalities.NATIONALITY LIKE '$nationality'";
        $result_check = mysqli_query($link, $sql_check);
        if(!$result_check){
            throw new Exception(mysqli_error($link));
        }
         
        if(mysqli_num_rows($result_check)>0){
            $row = mysqli_fetch_assoc($result_check);
            $nationalityID = $row['ID'];
        }
        elseif ($nationality=='') {
            $nationalityID = 'NULL';
        }
        else{
            $sql1 = "INSERT INTO nationalities VALUES(NULL, '$nationality')";
            
            if(!mysqli_query($link, $sql1)){
                throw new Exception(mysqli_error($link));
            }
            else {
                $nationalityID = mysqli_insert_id($link);
            }
        }
        mysqli_free_result($result_check);

        $validation = true;
        if(file_exists($_FILES["image"]["tmp_name"])){
            $imageData = mysqli_real_escape_string($link, file_get_contents($_FILES["image"]["tmp_name"]));
            $imageType = mysqli_real_escape_string($link, $_FILES["image"]["type"]);    
            if(substr($imageType, 0,5) != "image"){
                $validation = false;
            }
        }
        else{
            $validation = false;
        }
        
        if(!$validation){
            $sql = "INSERT INTO authors VALUES(NULL,'$name', '$nationalityID', LOAD_FILE('D:/A/XAMPP/xampp/htdocs/library/image/author.jpg'), '$birth')";
        }
        else {
            $sql = "INSERT INTO authors VALUES(NULL,'$name', '$nationalityID', '$imageData', '$birth')";
        }
         
        if(mysqli_query($link, $sql)){
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
        <link rel="stylesheet" href="../style/chosen_style.css" type="text/css">        
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <!-- <script> - JavaScript -->    
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
        <script src="../js/input_autocomplete.js"></script>
        <script src="../js/input_image.js"></script>
        <script>
            window.onload = function(){
                $(".default").css("width", "200px");
            }
        </script>
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
        
        <form id="addBookForm" method="POST" enctype="multipart/form-data"></form>
        <form id="addAuthorForm" method="POST" enctype="multipart/form-data"></form>
        
        <div id="container_main">
            <div class="row row_padding">
                <div class="row row_color mb-0">
                    <button form="addBookForm" name="addBook" class="icon">
                        <i class="material-icons">check</i>
                        <span class="tooltip w-150">Add the book</span>
                    </button>
                    <div class="column">
                        <input form="addBookForm" name="image" id="book_file" capture type="file" accept="image/*">
                        <img id="book_image" src="../image/new_book.jpg" alt="add book" class="big border pointer" />
                    </div>
                    <div class="column_main_list mb-0" style="margin-top: 0px; padding-top: 0px;">
                        <div class="row_header">
                            <header>
                                <h2><input id="title_input" name="title" form="addBookForm" type="text" placeholder="Enter the book's title..." class="title" required></h2>
                            </header>
                        </div>
                        <div class="row mb-0">
                            <div class="column_half mb-0">
                                <p class="lines_input">
                                    <select form="addBookForm" data-placeholder="Enter the book's authors..." multiple id="authors_input" name="authors[]">
                                        <option value=""></option>
                                        <?php include '../db/authors.php';?>
                                    </select>
                                    <label class="icon">
                                        <a class="black" href="#addAuthorModal">
                                            <i class="material-icons">add</i>
                                            <span class="tooltip w-150">Add new author</span>
                                        </a>
                                    </label><br/>
                                    <input id="series_input" name="series" form="addBookForm" style="margin-top: 0px" type="text" placeholder="Enter the book's series...">
                                    <input id="volume" style=" margin-top: 0px; margin-left: 10px; width: 40px;" min="1" type="hidden" placeholder="vol."><br>
                                    <input id="genre_input" name="genre" form="addBookForm" type="text" placeholder="Enter the book's genre..." required><br>
                                    <input id="original_input" name="original" form="addBookForm" type="text" placeholder="Enter the book's original title..."><br>
                                </p>
                                <p class="lines_input">
                                    <input id="publisher_input" name="publisher" form="addBookForm" type="text" placeholder="Enter the book's publisher..." required><br>
                                    <input id="publication_input" name="publication" form="addBookForm" type="text" onfocus="(this.type='date')" placeholder="Enter the book's date of publication..." required><br>
                                    <input name="language" form="addBookForm" id="language_input" type="text" placeholder="Enter the book's language..." required><br>
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
                                <input form="addAuthorForm" id="author_file" name="image" capture type="file" accept="image/*">
                                <img id="author_image" src="../image/new_author.jpg" alt="add author image" class="small border pointer">
                            </div>
                            <div class="column mb-0" style="margin-top: 15px;">
                                <div class="row_header">
                                    <header>
                                        <h2><input name="name" form="addAuthorForm" type="text" placeholder="Enter the author's full name..." class="title" required></h2>
                                    </header>
                                </div>
                                <div class="row mb-0">
                                    <div class="column_half mb-0">
                                        <p class="lines_input">
                                            <input id="nationality_input" style="margin-top: 0px" name="nationality" form="addAuthorForm" type="text" placeholder="Enter the author's nationality...">
                                            <input id="birth_input" name="birth" form="addAuthorForm" type="text" onfocus="(this.type='date')" placeholder="Enter the author's birth date..."><br>
                                        </p>
                                    </div>
                                    <div class="column_half mb-0">
                                        <p class="lines">
                                            <span id="nationality_span"></span><span id="nationality_text"></span>
                                            <span id="birth_span"></span><span id="birth_text"></span>
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

