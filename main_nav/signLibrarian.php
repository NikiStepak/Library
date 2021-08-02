<?php
session_start();

if((!(isset($_SESSION['librarian']))) && (!($_SESSION['librarian']==true))){
    header('Location: ../index.php');
    exit();
}

if(filter_has_var(INPUT_POST, 'signUp')){
    
    $validation = true;
    
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    //check length
    if((strlen($name)<3)){
        $validation = false;
        $_SESSION['e_name']="Name is too short (minimum is 3 characters)";
    }
    if((strlen($name)>20) ){
        $validation = false;
        $_SESSION['e_name']="Name is too long (maxmum is 20 characters)";
    }
    
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    //check type of characters
    if(!(ctype_alnum($login))){
        $validation = false;
        $_SESSION['e_login']="Login may only contain alphanumeric characters";
    }
    //check length 
    if((strlen($login)<3)){
        $validation = false;
        $_SESSION['e_login']="Name is too short (minimum is 3 characters)";
    }
    if((strlen($login)>30) ){
        $validation = false;
        $_SESSION['e_login']="Name is too long (maximum is 30 characters)";
    }
    
    $password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);
    $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);
    //check length
    if((strlen($password1)<8)){
        $validation = false;
        $_SESSION['e_password1']="Password is too short (minimum is 8 characters)";
    }
    if((strlen($password1)>30) ){
        $validation = false;
        $_SESSION['e_password1']="Password is too long (maximum is 30 characters)";
    }
    // check if password1 == password2
    if($password1!=$password2){
        $validation = false;
        $_SESSION['e_password2']="Posswords didn't match. Try again.";
    }
    // hash function
    $password_hash = password_hash($password1, PASSWORD_DEFAULT);
    
    //Remember written data
    $_SESSION['r_name'] = $name;
    $_SESSION['r_login'] = $login;
    
    require_once '../connect.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try{
        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
        if(mysqli_connect_errno()){
            throw new Exception(mysqli_connect_errno());
        }
        else {            
            // check if login exist
            $sql_login1 = "SELECT USER_ID FROM users WHERE LOGIN='$login'";
            $result_login1 = mysqli_query($link, $sql_login1);
            if(!$result_login1){
                throw new Exception (mysqli_error($link));
            }
            $num_login1 = mysqli_num_rows($result_login1);
            if($num_login1>0){
                $validation = false;
                $_SESSION['e_login']='Login is alreaady taken';  
            }
            // check if login exist
            $sql_login2 = "SELECT ID FROM librarian WHERE LOGIN='$login'";
            $result_login2 = mysqli_query($link, $sql_login2);
            if(!$result_login2){
                throw new Exception (mysqli_error($link));
            }
            $num_login2 = mysqli_num_rows($result_login2);
            if($num_login2>0){
                $validation = false;
                $_SESSION['e_login']='Login is alreaady taken';  
            }
            
            // if all data are correct - create new user
            if($validation==true){
                $sql = "INSERT INTO librarian VALUES(NULL,'$name','$login','$password_hash')"; 
                if(mysqli_query($link, $sql)){
                    $_SESSION['registered'] = true;
                    header('Location: ../index.php');
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
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Library - Add Librarian</title>
        <link rel="stylesheet" href="../style/main_style.css"> 
        <link rel="stylesheet" href="../style/form_style.css"> 
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
            <form method="POST">
                <input type="text" name="name" value="<?php
                if(isset($_SESSION['r_name'])){
                    echo $_SESSION['r_name'];
                    unset($_SESSION['r_name']);
                }
                ?>" placeholder="Name" onfocus="this.placeholder=''" onblur="this.placeholder='Name'"><br>
                <?php
                if(isset($_SESSION['e_name'])){
                    echo '<div class="error">'.$_SESSION['e_name'].'</div>';
                    unset($_SESSION['e_name']);
                }
                ?>                
                <input type="text" name="login" value="<?php 
                if(isset($_SESSION['r_login'])){
                    echo $_SESSION['r_login'];
                    unset($_SESSION['r_login']);
                }
                ?>" placeholder="Login" onfocus="this.placeholder=''" onblur="this.placeholder='Login'"><br>
                <?php
                if(isset($_SESSION['e_login'])){
                    echo '<div class="error">'.$_SESSION['e_login'].'</div>';
                    unset($_SESSION['e_login']);
                }
                ?>
                <input type="password" name="password1" placeholder="Password" onfocus="this.placeholder=''" onblur="this.placeholder='Password'"><br>
                <?php
                if(isset($_SESSION['e_password1'])){
                    echo '<div class="error">'.$_SESSION['e_password1'].'</div>';
                    unset($_SESSION['e_password1']);
                }
                ?>
                <input type="password" name="password2" placeholder="Confirm Password" onfocus="this.placeholder=''" onblur="this.placeholder='Confirm Password'"><br>
                <?php
                if(isset($_SESSION['e_password2'])){
                    echo '<div class="error">'.$_SESSION['e_password2'].'</div>';
                    unset($_SESSION['e_password2']);
                }
                ?>
                <input type="submit" name="signUp" value="Sign up">
            </form>
        </div>
    </body>
</html>

