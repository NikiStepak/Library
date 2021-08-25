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
        <link rel="stylesheet" href="../style/text_style.css" type="text/css"> 
        <link rel="stylesheet" href="../style/table_style.css" type="text/css"> 
        <link rel="stylesheet" href="../style/form_style.css" type="text/css"> 
        <!-- <script> - JavaScript -->
        <meta charset="UTF-8">
        <meta name="author" content="Niki"><!-- Author of a page -->
        <meta name="viewport" content="width-device-width, initial-scale=1.0"><!-- setting the viewport to make website look good on all devices -->                
    </head>
    <body>
        <nav class="sign">
            <ol>
                <li><a href="signUp.php" class="white">Sign up</a></li>
                <li><a href="#" class="white">Sign in</a></li>            
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
                        <?php
                        if(isset($_SESSION['error'])){
                            echo '<div class="error">'.$_SESSION['error'].'</div>';
                            unset($_SESSION['error']);
                        }
                        ?>
                        <form id="loginForm" action="logIn.php" method="POST"></form> 
                        <p class="lines_input">
                            <input form="loginForm"type="text" name="login" placeholder="Login" onfocus="this.placeholder=''" onblur="this.placeholder='Login'"><br>
                            <input form="loginForm" type="password" name="password" placeholder="Password" onfocus="this.placeholder=''" onblur="this.placeholder='Password'"><br>
                            <input form="loginForm" type="submit" name="signIn" value="Sign in">
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
