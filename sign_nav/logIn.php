<?php

session_start();

if((!filter_has_var(INPUT_POST, 'signIn'))){
    header('Location: ../index.php');
    exit();
}

require_once '../connect.php';
mysqli_report(MYSQLI_REPORT_STRICT);

try{
    $link = mysqli_connect($host, $db_user, $db_password, $db_name);
    
    if(mysqli_connect_errno()){
        throw new Exception(mysqli_connect_errno());
    }
    
    $login = htmlentities(filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING), ENT_QUOTES, "UTF-8");
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // check if login/email is in database
    $sql1 = sprintf("SELECT * FROM users WHERE LOGIN='%s' OR EMAIL='%s'", 
            mysqli_real_escape_string($link, $login),
            mysqli_real_escape_string($link, $login));
    $sql2 = sprintf("SELECT * FROM librarian WHERE LOGIN='%s'", 
            mysqli_real_escape_string($link, $login));
    
    $result1 = mysqli_query($link, $sql1);
    $result2 = mysqli_query($link, $sql2);
    if(!$result1){
        throw new Exception(mysqli_error($link));
    }
    elseif (!$result2) {      
        throw new Exception(mysqli_error($link));    
    }
    
    $num1 = mysqli_num_rows($result1);
    $num2 = mysqli_num_rows($result2);
    if($num1>0){
        $row = mysqli_fetch_assoc($result1);

        //if password is correct - Sign in
        if(password_verify($password, $row['PASSWORD'])){    
        
            $_SESSION['logged'] = true;
            $_SESSION['id'] = $row['USER_ID'];
            $_SESSION['name'] = $row['NAME'];
            $_SESSION['email'] = $row['EMAIL'];
            $_SESSION['login'] = $row['LOGIN'];
            $_SESSION['birth'] = $row['BIRTH_DATE'];
            $_SESSION['phone'] = $row['PHONE_NUMBER'];
            $_SESSION['adress'] = $row['ADRESS'];
            $_SESSION['validity'] = $row['VALIDITY'];
            
            unset($_SESSION['error']);
            mysqli_free_result($result1);
            header('Location: ../index.php');
        }
        else {
            $_SESSION['error'] = 'Wrong login or password.<br> Try again.';
            header('Location: signIn.php');
        }
    }
    elseif ($num2>0) {
        $row = mysqli_fetch_assoc($result2);

        //if password is correct - Sign in
        if(password_verify($password, $row['PASSWORD'])){    
        
            $_SESSION['librarian'] = true;
            
            $_SESSION['id'] = $row['USER_ID'];
            $_SESSION['name'] = $row['NAME'];
            $_SESSION['email'] = $row['EMAIL'];
            $_SESSION['login'] = $row['LOGIN'];
            
            unset($_SESSION['error']);
            mysqli_free_result($result2);
            header('Location: ../index.php');
        }
        else {
            $_SESSION['error'] = 'Wrong login or password.<br> Try again.';
            header('Location: signIn.php');
        }
    }
    else {
        $_SESSION['error'] = 'Wrong login or password.<br> Try again.';
        header('Location: signIn.php');
    }
    
    mysqli_close($link);

} catch (Exception $e){
    echo '<span style="color: red;">Error!!! Failed to connect. Try again later.</span>';
    echo '<br>Developer information: '.$e;
}
?>