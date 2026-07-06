<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "myhmsdb");

if(isset($_POST['adsub'])){
    $username = $_POST['username1'];
    $password = $_POST['password2'];

    // FIX SQLi: Utilizzo dei Prepared Statements per il login degli admin
    $stmt = mysqli_prepare($con, "SELECT * FROM admintb WHERE username=? AND password=?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) == 1) {
        $_SESSION['username'] = $username;
        header("Location:admin-panel1.php");
    } else {
        echo("<script>alert('Invalid Username or Password. Try Again!');
              window.location.href = 'index.php';</script>");
    }
}

if(isset($_POST['update_data'])) {
    $contact = $_POST['contact'];
    $status = $_POST['status'];

    // FIX SQLi: Utilizzo dei Prepared Statements per l'update dei pagamenti
    $stmt = mysqli_prepare($con, "UPDATE appointmenttb SET payment=? WHERE contact=?");
    mysqli_stmt_bind_param($stmt, "ss", $status, $contact);
    $result = mysqli_stmt_execute($stmt);

    if($result) {
        header("Location:updated.php");
    }
}

function display_docs()
{
    global $con;
    $query = "SELECT * FROM doctb";
    $result = mysqli_query($con, $query);
    while($row = mysqli_fetch_array($result))
    {
        $name = $row['name'];
        // FIX XSS: Sanitizzazione dell'output
        $safe_name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        echo '<option value="'.$safe_name.'">'.$safe_name.'</option>';
    }
}

if(isset($_POST['doc_sub'])) {
    $name = $_POST['name'];

    // FIX SQLi: Utilizzo dei Prepared Statements per l'inserimento
    $stmt = mysqli_prepare($con, "INSERT INTO doctb(name) VALUES (?)");
    mysqli_stmt_bind_param($stmt, "s", $name);
    $result = mysqli_stmt_execute($stmt);

    if($result) {
        header("Location:adddoc.php");
    }
}
?>