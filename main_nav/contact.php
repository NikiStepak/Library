<?php
session_start();

require_once '../db/connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try {
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    $sql = "SELECT * FROM contact";
    
    $result = mysqli_query($link, $sql);
    if(!$result){
        throw new Exception(mysqli_error($link));
    }
    
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);
    }
    
    mysqli_free_result($result);
    
    mysqli_close($link);
} catch (Exception $e) {
    echo '<span class="error">Error!!! Failed to connect. Try again later.</span>';
    echo '<br>Developer information: '.$e;   
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Niki"><!-- Author of a page -->
        <!--<meta http-equiv="refresh" content="30"> Refresh document every 30s -->       
        <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- setting the viewport to make website look good on all devices -->  
        <title>Library - Contact</title>
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
                        <li><a href="books.php" class="white">books</a></li>
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
                <li><a href="#" class="white">contact</a></li>
            </ol>
        </nav>
        
        <div id="container_main">
            <div class="row">              
                <div class="column_main">
                    <div class="column_color">
                        <label class="icon">
                            <i class="material-icons">create</i>
                            <span class="tooltip">Edit</span>
                        </label>
                        <div class="column">
                            <img src="../image/library.jpg" alt="library" class="biggest"/>
                        </div>
                        <div class="column">
                            <div class="column_margin-top">
                                <div class="row_header">
                                    <header class="border">
                                        <h1><?php echo $row['NAME']; ?></h1>
                                    </header>
                                </div>
                                <div class="column_margin-top">
                                    <p class="lines">
                                        <span class="black">Email:</span><?php echo $row['EMAIL']; ?><br/>
                                        <span class="black">Phone number:</span><?php echo $row['PHONE']; ?><br/>
                                        <span class="black">Adress:</span><?php echo $row['ADRESS']; ?><br/>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

