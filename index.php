<?php
session_start();

if(isset($_SESSION['r_name'])){
    unset ($_SESSION['r_name']);
}
if(isset($_SESSION['r_email'])){
    unset ($_SESSION['r_email']);
}
if(isset($_SESSION['r_login'])){
    unset ($_SESSION['r_login']);
}
if(isset($_SESSION['r_terms'])){
    unset ($_SESSION['r_terms']);
}

if(isset($_SESSION['e_name'])){
    unset ($_SESSION['e_name']);
}
if(isset($_SESSION['e_login'])){
    unset ($_SESSION['e_login']);
}
if(isset($_SESSION['e_email'])){
    unset ($_SESSION['e_email']);
}
if(isset($_SESSION['e_password1'])){
    unset ($_SESSION['e_password1']);
}
if(isset($_SESSION['e_password2'])){
    unset ($_SESSION['e_password2']);
}
if(isset($_SESSION['e_terms'])){
    unset ($_SESSION['e_terms']);
}
if(isset($_SESSION['e_bot'])){
    unset ($_SESSION['e_bot']);
}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Library - Main Page</title>
        <link rel="stylesheet" href="style/main_style.css" type="text/css">
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
                    echo '<li><a href="sign_nav/account.php">my account</a></li>' ;
                    echo '<li><a href="sign_nav/signOut.php">Sign Out</a></li>' ;
                    echo '</ul></li>';
                }
                elseif ((isset($_SESSION['librarian'])) && ($_SESSION['librarian']==true)) {
                    echo'<li><a href="#">'.$_SESSION['name'].'</a>';
                    echo '<ul>';
                    echo '<li><a href="sign_nav/signOut.php">Sign Out</a></li>' ;
                    echo '</ul></li>';
                }
                else {
                    echo '<li><a href="sign_nav/signUp.php">Sign up</a></li>';
                    echo '<li><a href="sign_nav/signIn.php">Sign in</a></li>';                      
                }
                ?>
            </ol>
        </nav>
        
        <header>
            <img src="image/logo.png" alt="Logo">LIBRARY     
        </header>
        
        <nav class="main">
            <ol>
                <li><a href="main_nav/books.php">books</a></li>
                <li><a href="main_nav/authors.php">authors</a></li>
                <?php
                if((isset($_SESSION['librarian'])) && ($_SESSION['librarian']==true)){
                    echo '<li><a href="#">librarian</a>';
                    echo '<ul>';
                    echo '<li><a href="main_nav/signLibrarian.php">Add librarian</a></li>' ;
                    echo '<li><a href="main_nav/addBook.php">Add book</a></li>' ;
                    echo '</ul></li>';
                }
                ?>
                <li><a href="#">contact</a></li>
            </ol>
        </nav>
        
        <div id="container_main">
            <?php
            if((isset($_SESSION['registered'])) && ($_SESSION['registered']==true)){
                unset($_SESSION['registered']); 
                echo"<p>Thank you for register</p><br>";
            }
            ?>
        </div>

    </body>
</html>
