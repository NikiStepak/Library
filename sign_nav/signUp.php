<?php
session_start();

if((isset($_SESSION['logged'])) && ($_SESSION['logged']==true)){
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
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_STRING);
            $safe_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    //check email
    if((filter_var($safe_email,FILTER_VALIDATE_EMAIL)==false)|| ($safe_email!=$email)){
        $validation = false;
        $_SESSION['e_email']="Email is invalid";
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
    
    //check checkbox
    if(!(filter_has_var(INPUT_POST, 'terms'))){
        $validation = false;
        $_SESSION['e_terms']='Accept all terms.';        
    }
    
    //check reCaptcha
    $secret_key = "6LdpfqUbAAAAAJ4DgwGIxBU-9AjbN6Kqay2aciDC";
    $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.filter_input(INPUT_POST, 'g-recaptcha-response'));
    $response = json_decode($check);
    if($response->success == false){
        $validation = false;
        $_SESSION['e_bot']='Confirm that you are not bot';  
    }
    
    //Remember written data
    $_SESSION['r_name'] = $name;
    $_SESSION['r_email'] = $email;
    $_SESSION['r_login'] = $login;
    if(filter_has_var(INPUT_POST, 'terms')){
        $_SESSION['r_terms'] = true;
    }
    
    require_once '../db/connect.php';
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try{
        $link = mysqli_connect($host, $db_user, $db_password, $db_name);
        if(mysqli_connect_errno()){
            throw new Exception(mysqli_connect_errno());
        }
        else {
            // check if email exist
            $sql_email = "SELECT USER_ID FROM users WHERE EMAIL='$email'";
            $result_email = mysqli_query($link, $sql_email);
            if(!$result_email){
                throw new Exception (mysqli_error($link));
            }
            $num_email = mysqli_num_rows($result_email);
            if($num_email>0){
                $validation = false;
                $_SESSION['e_email']='Email is alreaady taken';  
            }
            
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
                $sql = "INSERT INTO users VALUES(NULL,'$name','$email','$login','$password_hash','0')"; 
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
        <title>Library - Sign Up</title>
        <!-- CSS -->
        <link rel="stylesheet" href="../style/main_style.css"> 
        <link rel="stylesheet" href="../style/text_style.css"> 
        <link rel="stylesheet" href="../style/form_style.css"> 
        <link rel="stylesheet" href="../style/table_style.css"> 
        <!-- <script> - JavaScript -->
        <meta charset="UTF-8">
        <meta name="author" content="Niki"><!-- Author of a page -->
        <meta name="viewport" content="width-device-width, initial-scale=1.0"><!-- setting the viewport to make website look good on all devices -->                
        <script src="https://www.google.com/recaptcha/api.js" async defer></script><!-- reCaptcha -->    
    </head>
    <body>        
        <nav class="sign">
            <ol>
                <li><a href="#" class="white">Sign up</a></li>'
                <li><a href="signIn.php" class="white">Sign in</a></li>                  
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
                        <li><a href="../main_nav/books.php" class="white">books</a></li>
                        <li><a href="../main_nav/authors.php" class="white">authors</a></li>
                    </ul>
                </li>
                <?php
                if((isset($_SESSION['librarian'])) && ($_SESSION['librarian']==true)){
                    echo '<li><a href="#" class="white">librarian</a>';
                    echo '<ul>';
                    echo '<li><a href="../main_nav/signLibrarian.php" class="white">Add librarian</a></li>' ;
                    echo '<li><a href="../main_nav/addBook.php" class="white">Add book</a></li>' ;
                    echo '</ul></li>';
                }
                ?>
                <li><a href="../main_nav/contact.php" class="white">contact</a></li>
            </ol>
        </nav>
        
        <div id="container_main">  
            <div class="row row_padding">              
                <div class="row mb-0">
                    <div class="column_half mb-0">
                        <!-- Sign Up form -->
                        <form id="registerForm" method="POST"></form>
                        <?php
                        if(isset($_SESSION['error'])){
                            echo '<div class="error">'.$_SESSION['error'].'</div>';
                            unset($_SESSION['error']);
                        }
                        ?>
                        <form id="loginForm" action="logIn.php" method="POST"></form> 
                        <p class="lines_input">
                            <input form="registerForm" type="text" name="name" value="<?php
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
                            <input form="registerForm" type="text" name="email" value="<?php 
                            if(isset($_SESSION['r_email'])){
                                echo $_SESSION['r_email'];
                                unset($_SESSION['r_email']);
                            }
                            ?>" placeholder="Email" onfocus="this.placeholder=''" onblur="this.placeholder='Email'"><br>
                            <?php
                            if(isset($_SESSION['e_email'])){
                                echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                                unset($_SESSION['e_email']);
                            }
                            ?>
                            <input form="registerForm" type="text" name="login" value="<?php 
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
                            <input form="registerForm" type="password" name="password1" placeholder="Password" onfocus="this.placeholder=''" onblur="this.placeholder='Password'"><br>
                            <?php
                            if(isset($_SESSION['e_password1'])){
                                echo '<div class="error">'.$_SESSION['e_password1'].'</div>';
                                unset($_SESSION['e_password1']);
                            }
                            ?>
                            <input form="registerForm" type="password" name="password2" placeholder="Confirm Password" onfocus="this.placeholder=''" onblur="this.placeholder='Confirm Password'"><br>
                            <?php
                            if(isset($_SESSION['e_password2'])){
                                echo '<div class="error">'.$_SESSION['e_password2'].'</div>';
                                unset($_SESSION['e_password2']);
                            }
                            ?>
                            <label class="pointer" style="margin-left: -30px; white-space: nowrap;">
                                <input form="registerForm" type="checkbox" name="terms"/> I agree to the terms of use and Data Privacy Policy
                            </label><br>
                            <?php
                            if(isset($_SESSION['e_terms'])){
                                echo '<div class="error">'.$_SESSION['e_terms'].'</div>';
                                unset($_SESSION['e_terms']);
                            }
                            ?>
                        </p>
                        
                        <div style="margin: auto; width: 300px;" class="g-recaptcha" data-sitekey="6LdpfqUbAAAAAIcTlvHv-m7o5Tna-ui5yUpobwfx"></div><!-- reCaptcha -->
                        <?php
                        if(isset($_SESSION['e_bot'])){
                            echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
                            unset($_SESSION['e_bot']);
                        }
                        ?>
                        <p class="lines_input">
                            <input form="registerForm" type="submit" name="signUp" value="Sign up">
                        </p>
                        
                    </div>
                    <div class="column_half column_color">
                        a
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

