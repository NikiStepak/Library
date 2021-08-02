<?php
session_start();

if((!(isset($_SESSION['librarian']))) && (!($_SESSION['librarian']==true))){
    header('Location: ../index.php');
    exit();
}

if(filter_has_var(INPUT_POST, 'addPublisher')){
    
    require_once '../connect.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try{
        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
        if(mysqli_connect_errno()){
            throw new Exception(mysqli_connect_errno());
        }
        else {     
            $publisher = filter_input(INPUT_POST, 'new_publisher', FILTER_SANITIZE_STRING);
    
            $sql = "INSERT INTO publishers VALUES(NULL,'$publisher')"; 
            if(!mysqli_query($link, $sql)){
                throw new Exception(mysqli_error($link));
            }
                        
            mysqli_close($link);
        }
    } catch (Exception $e){
        echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
        echo '<br>Developer information: '.$e;
    }
}

if(filter_has_var(INPUT_POST, 'addLanguage')){
    
    require_once '../connect.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try{
        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
        if(mysqli_connect_errno()){
            throw new Exception(mysqli_connect_errno());
        }
        else {     
            $language = filter_input(INPUT_POST, 'new_language', FILTER_SANITIZE_STRING);
    
            $sql = "INSERT INTO nationalities_languages VALUES(NULL,'$language')"; 
            if(!mysqli_query($link, $sql)){
                throw new Exception(mysqli_error($link));
            }
                        
            mysqli_close($link);
        }
    } catch (Exception $e){
        echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
        echo '<br>Developer information: '.$e;
    }
}

if(filter_has_var(INPUT_POST, 'addGenre')){
    
    require_once '../connect.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try{
        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
        if(mysqli_connect_errno()){
            throw new Exception(mysqli_connect_errno());
        }
        else {     
            $genre = filter_input(INPUT_POST, 'new_genre', FILTER_SANITIZE_STRING);
    
            $sql = "INSERT INTO genres VALUES(NULL,'$genre')"; 
            if(!mysqli_query($link, $sql)){
                throw new Exception(mysqli_error($link));
            }
                        
            mysqli_close($link);
        }
    } catch (Exception $e){
        echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
        echo '<br>Developer information: '.$e;
    }
}

if(filter_has_var(INPUT_POST, 'addNationality')){
    
    require_once '../connect.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try{
        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
        if(mysqli_connect_errno()){
            throw new Exception(mysqli_connect_errno());
        }
        else {     
            $nationality = filter_input(INPUT_POST, 'new_nationality', FILTER_SANITIZE_STRING);
    
            $sql = "INSERT INTO nationalities_languages VALUES(NULL,'$nationality')"; 
            if(!mysqli_query($link, $sql)){
                throw new Exception(mysqli_error($link));
            }
                        
            mysqli_close($link);
        }
    } catch (Exception $e){
        echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
        echo '<br>Developer information: '.$e;
    }
}

if(filter_has_var(INPUT_POST, 'ok')){
    
    $validation = true;
    
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $original = filter_input(INPUT_POST, 'original', FILTER_SANITIZE_STRING);
    $publisher = filter_input(INPUT_POST, 'publisher', FILTER_SANITIZE_NUMBER_INT);    
    $language = filter_input(INPUT_POST, 'language', FILTER_SANITIZE_NUMBER_INT);  
    $genre = filter_input(INPUT_POST, 'genre', FILTER_SANITIZE_NUMBER_INT);        

    $isbn = filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_NUMBER_INT);
    //check length
    if((strlen($isbn)<13)){
        $validation = false;
        $_SESSION['e_isbn']="ISBN is too short (13 characters)";
    }
    if((strlen($isbn)>13) ){
        $validation = false;
        $_SESSION['e_isbn']="ISBN is too long (13 characters)";
    }
    
    //Remember written data
    $_SESSION['r_title'] = $title;
    $_SESSION['r_original'] = $original;
    $_SESSION['r_isbn'] = $isbn;
    
    require_once '../connect.php';
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
    }
}

if(filter_has_var(INPUT_POST, 'add_author')){
    
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_NUMBER_INT);
    $book = $_SESSION['bookID'];
    
    require_once '../connect.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try{
        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
        if(mysqli_connect_errno()){
            throw new Exception(mysqli_connect_errno());
        }
        else {                 
            $sql = "INSERT INTO book_author VALUES(NULL,'$book','$author')"; 
                if(mysqli_query($link, $sql)){
                    $_SESSION['addBook'] = true;
                    header('Location: addBookNext.php');
                }
                else {
                    throw new Exception(mysqli_error($link));
                }
            
            
            mysqli_close($link);
        }
    } catch (Exception $e){
        echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
        echo '<br>Developer information: '.$e;
    }
}

if(filter_has_var(INPUT_POST, 'add_newAuthor')){
    
    $validation = true;
    
    $name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
    $nationality = filter_input(INPUT_POST, 'nationality', FILTER_SANITIZE_NUMBER_INT);    
    $book = $_SESSION['bookID'];
    
    //Remember written data
    $_SESSION['r_full_name'] = $name;
    
    require_once '../connect.php';
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
                $sql = "INSERT INTO authors VALUES('','$name','$nationality','$imageData')"; 
                if(!mysqli_query($link, $sql)){
                    throw new Exception(mysqli_error($link));
                }
            }
            
            $sql = "SELECT ID FROM authors ORDER BY id DESC LIMIT 1"; 
            $result = mysqli_query($link, $sql);
            if(!$result){              
                throw new Exception(mysqli_error($link));
            }
            
            $row = mysqli_fetch_assoc($result);
            $author = $row['ID'];
           
            if(!mysqli_query($link, "INSERT INTO book_author VALUES(NULL,'$book','$author')")){
                throw new Exception(mysqli_error($link));
            }
            
            mysqli_close($link);
        }
    } catch (Exception $e){
        echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
        echo '<br>Developer information: '.$e;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Library - Add Book</title>
        <link rel="stylesheet" href="../style/main_style.css"> 
        <link rel="stylesheet" href="../style/form_style.css"> 
        <link rel="stylesheet" href="../style/modal_style.css"> 
        <link rel="stylesheet" href="../style/mysql_style.css"> 
        <!-- <script> - JavaScript -->
        <meta charset="UTF-8">
        <meta name="author" content="Niki"><!-- Author of a page -->
        <meta name="viewport" content="width-device-width, initial-scale=1.0"><!-- setting the viewport to make website look good on all devices -->                
        <script src="https://www.google.com/recaptcha/api.js" async defer></script><!-- reCaptcha -->    
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
                <li><a href="../main_nav/books.php">books</a></li>
                <li><a href="../main_nav/authors.php">authors</a></li>
                <?php
                if((isset($_SESSION['librarian'])) && ($_SESSION['librarian']==true)){
                    echo '<li><a href="#">librarian</a>';
                    echo '<ul>';
                    echo '<li><a href="#">Add librarian</a></li>' ;
                    echo '<li><a href="addBook.php">Add book</a></li>' ;
                    echo '</ul></li>';
                }
                ?>
                <li><a href="#">contact</a></li>
            </ol>
        </nav>
        
        <div id="container_main">
            <div class="row">
            <div class="column">
            <form id="1" method="POST" enctype="multipart/form-data"></form>
            <input form="1" type="text" name="title" value="<?php
                if(isset($_SESSION['r_title'])){
                    echo $_SESSION['r_title'];
                    unset($_SESSION['r_title']);
                }
                ?>" placeholder="Title" onfocus="this.placeholder=''" onblur="this.placeholder='Title'"><br>
                
            <input form="1" type="text" name="original" value="<?php 
                if(isset($_SESSION['r_original'])){
                    echo $_SESSION['r_original'];
                    unset($_SESSION['r_original']);
                }
                ?>" placeholder="Original Title" onfocus="this.placeholder=''" onblur="this.placeholder='Original Title'"><br>

            <select form="1" name="publisher" required>
                <option style="color: #dc9276;" value="">Select publisher</option>
                    <?php
                    require_once '../connect.php';
                    mysqli_report(MYSQLI_REPORT_STRICT);
                    
                    try {
                        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
                        if(mysqli_connect_errno()){
                            throw new Exception(mysqli_connect_errno());
                        }
                        
                        $sql = "SELECT * FROM publishers ";
                        $result = mysqli_query($link, $sql);
                        if(!$result){
                            throw new Exception(mysqli_error($link));
                        }
                        
                        if(mysqli_num_rows($result)>0){
                            while($row = mysqli_fetch_assoc($result)){
                                echo '<option value='.$row['ID'].'>'.$row['PUBLISHER'].'</option>';                                
                            }
                            mysqli_free_result($result);
                        }
                     
                        mysqli_close($link);
                    } catch (Exception $ex) {
                        echo '<div class="error">Error! Failed to connect. Try again later.';
                        echo 'Developer information:'.$ex.'</div>';
                    }
                    ?>
            </select>
            <form action="#add_publisher">
                <button type="submit">Add</button><br>
            </form>
                
            <select form="1" name="language" required>
                <option style="color: #dc9276;" value="">Select language</option>
                    <?php
                    require_once '../connect.php';
                    mysqli_report(MYSQLI_REPORT_STRICT);
                    
                    try {
                        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
                        if(mysqli_connect_errno()){
                            throw new Exception(mysqli_connect_errno());
                        }
                        
                        $sql = "SELECT * FROM nationalities_languages";
                        $result = mysqli_query($link, $sql);
                        if(!$result){
                            throw new Exception(mysqli_error($link));
                        }
                        
                        if(mysqli_num_rows($result)>0){
                            while($row = mysqli_fetch_assoc($result)){
                                echo '<option value='.$row['ID'].'>'.$row['NAME'].'</option>';                                
                            }
                            mysqli_free_result($result);
                        }
                     
                        mysqli_close($link);
                    } catch (Exception $ex) {
                        echo '<div class="error">Error! Failed to connect. Try again later.';
                        echo 'Developer information:'.$ex.'</div>';
                    }
                    ?>
            </select>
            
            <form action="#add_language">
                <button type="submit">Add</button><br>
            </form>
            
            <select form="1" name="genre" required>
                <option style="color: #dc9276;" value="">Select genre</option>
                    <?php
                    require_once '../connect.php';
                    mysqli_report(MYSQLI_REPORT_STRICT);
                    
                    try {
                        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
                        if(mysqli_connect_errno()){
                            throw new Exception(mysqli_connect_errno());
                        }
                        
                        $sql = "SELECT * FROM genres";
                        $result = mysqli_query($link, $sql);
                        if(!$result){
                            throw new Exception(mysqli_error($link));
                        }
                        
                        if(mysqli_num_rows($result)>0){
                            while($row = mysqli_fetch_assoc($result)){
                                echo '<option value='.$row['ID'].'>'.$row['GENRE'].'</option>';                                
                            }
                            mysqli_free_result($result);
                        }
                     
                        mysqli_close($link);
                    } catch (Exception $ex) {
                        echo '<div class="error">Error! Failed to connect. Try again later.';
                        echo 'Developer information:'.$ex.'</div>';
                    }
                    ?>
            </select>
            
            <form action="#add_genre">
                <button type="submit">Add</button><br>
            </form>
            
            <input form="1" type="text" name="isbn" placeholder="ISBN" onfocus="this.placeholder=''" onblur="this.placeholder='ISBN'" <?php 
                if(isset($_SESSION['r_isbn'])){
                    echo 'value='.$_SESSION['r_isbn'];
                    unset($_SESSION['r_isbn']);
                }
                ?>><br>
                <?php
                if(isset($_SESSION['e_isbn'])){
                    echo '<div class="error">'.$_SESSION['e_isbn'].'</div>';
                    unset($_SESSION['e_isbn']);
                }
                ?> 
            
            <input form="1" type="file" name="image"><br>
                <?php
                if(isset($_SESSION['e_image'])){
                    echo '<div class="error">'.$_SESSION['e_image'].'</div>';
                    unset($_SESSION['e_image']);
                }
                ?> 
            
            <input form="1" type="submit" name="ok" value="OK">
            
            </div>
            <div class="column">

            <form method="POST">
                <select name="author" required>
                    <option value="">Select author</option>
                    <?php
                    require_once '../connect.php';
                    mysqli_report(MYSQLI_REPORT_STRICT);
                    
                    try {
                        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
                        if(mysqli_connect_errno()){
                            throw new Exception(mysqli_connect_errno());
                        }
                        
                        $sql = "SELECT * FROM authors ";
                        $result = mysqli_query($link, $sql);
                        if(!$result){
                            throw new Exception(mysqli_error($link));
                        }
                        
                        if(mysqli_num_rows($result)>0){
                            while($row = mysqli_fetch_assoc($result)){
                                echo '<option value='.$row['ID'].'>'.$row['FULL_NAME'].'</option>';                                
                            }
                            mysqli_free_result($result);
                        }
                     
                        mysqli_close($link);
                    } catch (Exception $ex) {
                        echo '<div class="error">Error! Failed to connect. Try again later.';
                        echo 'Developer information:'.$ex.'</div>';
                    }
                    ?>
                </select><br>
                <input type="submit" name="add_author" value="Add author">
            </form>
            </div>
            <div class="column">

            
            <form method="POST" enctype="multipart/form-data">
                <input type="text" name="full_name" value="<?php
                                if(isset($_SESSION['r_full_name'])){
                    echo $_SESSION['r_full_name'];
                    unset($_SESSION['r_full_name']);
                }
                
                ?>" placeholder="Full name" onfocus="this.placeholder=''" onblur="this.placeholder='Full name'"><br>
                
                <select name="nationality" required>
                    <option value="">Select nationality</option>
                    <?php
                    require_once '../connect.php';
                    mysqli_report(MYSQLI_REPORT_STRICT);
                    
                    try {
                        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
                        if(mysqli_connect_errno()){
                            throw new Exception(mysqli_connect_errno());
                        }
                        
                        $sql = "SELECT * FROM nationalities_languages";
                        $result = mysqli_query($link, $sql);
                        if(!$result){
                            throw new Exception(mysqli_error($link));
                        }
                        
                        if(mysqli_num_rows($result)>0){
                            while($row = mysqli_fetch_assoc($result)){
                                echo '<option value='.$row['ID'].'>'.$row['NAME'].'</option>';                                
                            }
                            mysqli_free_result($result);
                        }
                     
                        mysqli_close($link);
                    } catch (Exception $ex) {
                        echo '<div class="error">Error! Failed to connect. Try again later.';
                        echo 'Developer information:'.$ex.'</div>';
                    }
                    ?>
                </select>
                <button><a href="#add_nationality">Add</a></button><br>
                
                <input type="file" name="image"><br>
                <?php
                if(isset($_SESSION['e_image'])){
                    echo '<div class="error">'.$_SESSION['e_image'].'</div><br>';
                    unset($_SESSION['e_image']);
                }
                ?> 
                <input type="submit" name="add_newAuthor" value="Add new author">                
            </form>
            </div>
            </div>
        </div>
        
        <div id="add_publisher" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <a href="#" class="closebtn">×</a>
                    <div class="modal_container">
                        <h2>Add publisher</h2>
                        <form method="POST">
                            <input type="text" name="new_publisher"><br>
                            <input style="margin-bottom: 20px;" type="submit" name="addPublisher" value="OK">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="add_language" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <a href="#" class="closebtn">×</a>
                    <div class="modal_container">
                        <h2>Add language</h2>
                        <form method="POST">
                            <input type="text" name="new_language"><br>
                            <input style="margin-bottom: 20px" type="submit" name="addLanguage" value="OK">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="add_genre" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <a href="#" class="closebtn">×</a>
                    <div class="modal_container">
                        <h2>Add genre</h2>
                        <form method="POST">
                            <input type="text" name="new_genre"><br>
                            <input style="margin-bottom: 20px" type="submit" name="addGenre" value="OK">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="add_nationality" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <header class="container">
                        <a href="#" class="closebtn">×</a>
                        <h2>Add nationality</h2>
                    </header>
                    <div class="container">
                        <form method="POST">
                            <input style="margin-bottom: 20px" type="text" name="new_nationality">
                            <input type="submit" name="addNationality" value="ok">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>