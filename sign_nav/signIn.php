<?php
session_start();
if((isset($_SESSION['logged'])) && ($_SESSION['logged']==true)){
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Library - Sign In</title>
        <link rel="stylesheet" href="../style/main_style.css" type="text/css"> 
        <link rel="stylesheet" href="../style/form_style.css" type="text/css"> 
        <!-- <script> - JavaScript -->
        <meta charset="UTF-8">
        <meta name="author" content="Niki"><!-- Author of a page -->
        <meta name="viewport" content="width-device-width, initial-scale=1.0"><!-- setting the viewport to make website look good on all devices -->                
    </head>
    <body>
        <nav class="sign">
            <ol>
                <li><a href="signUp.php">Sign up</a></li>
                <li><a href="#">Sign in</a></li>                   
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
                    echo '<li><a href="../main_nav/signLibrarian.php">Add librarian</a></li>' ;
                    echo '<li><a href="../main_nav/addBook.php">Add book</a></li>' ;
                    echo '</ul></li>';
                }
                ?>
                <li><a href="#">contact</a></li>
            </ol>
        </nav>
        
        <div id="container_main">            
            <?php
            if(isset($_SESSION['error'])){
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            }
            ?>
            <form action="logIn.php" method="POST">
                <input type="text" name="login" placeholder="Login" onfocus="this.placeholder=''" onblur="this.placeholder='Login'"><br>
                <input type="password" name="password" placeholder="Password" onfocus="this.placeholder=''" onblur="this.placeholder='Password'"><br>
                <input type="submit" name="signIn" value="Sign in">
            </form>           
        </div>
    </body>
</html>
