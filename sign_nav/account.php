<?php
session_start();

if((!isset($_SESSION['logged'])) || !($_SESSION['logged']==true)){
    header("Location: ../index.php");
    exit();
}

if(filter_has_var(INPUT_POST, 'signOut')){
    header("Location: signOut.php");
}


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
        <title>Library - Account</title>
        <link rel="stylesheet" href="../style/main_style.css" type="text/css">
        <link rel="stylesheet" href="../style/mysql_style.css" type="text/css">
        <!-- <script> - JavaScript -->
        <meta charset="UTF-8">
        <meta name="author" content="Niki"><!-- Author of a page -->
        <!--<meta http-equiv="refresh" content="30"> Refresh document every 30s -->       
        <meta name="viewport" content="width-device-width, initial-scale=1.0"><!-- setting the viewport to make website look good on all devices -->                
    </head>
    <body>
        <nav class="sign">
            <ol>
                <li>
                <?php
                echo'<a href="#">'.$_SESSION['name'].'</a>';
                ?>
                    <ul>
                        <li><a href="#">my account</a></li>
                        <li><a href="signOut.php">Sign Out</a></li>
                    </ul>
                </li>
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
            <div class="row">
                <div class="column">
                    <a href="#"><img src="../image/account.jpg" alt="Profil photo" height="300"></a>
                </div>
                <div class="column"> 
                    <?php
                        echo'<h1>'.$_SESSION['name'].'</h1>';
                        echo '<p style="text-transform: none;"><span>Login:</span> '.$_SESSION['login'].'<br>';
                        echo '<span>Email:</span> '.$_SESSION['email'].'</p>';
                        echo '<p><span>Date of birth:</span> '.$_SESSION['birth'].'<br>';
                        echo '<span>Phone number:</span> '.$_SESSION['phone'].'<br>';                        
                        echo '<span>Adress:</span> '.$_SESSION['adress'].'</p>';
                        echo '<p><span>Account is valid to:</span> '.$_SESSION['validity'].'</p>';                        
                    ?>
                </div>
                <div class="column">
                    <form action="signOut.php">
                        <button type="submit">Sign Out</button>
                    </form>
                    <br><br>
                    <form action="#">
                        <button type="submit">Edit profile</button>
                    </form>
                </div>
            </div>
        </div>

<!--        <div id="edit" class="modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <header class="container1">
                        <a href="#" class="closebtn">×</a>
                        <h2>Edit profile</h2>
                    </header>
                    <div class="container">
                        <form method="POST">
                            <input style="margin-bottom: 20px" type="text" name="new_genre">
                            <input type="submit" name="addGenre" value="ok">
                        </form>
                    </div>
                </div>
            </div>
        </div>
-->        
    </body>
</html>
