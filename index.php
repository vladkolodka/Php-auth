<?php
define("MAIN_PAGE", 1);
require_once "data/authorization.class.php";
$system = new Authorization();

if(isset($_POST['send'])){
    if($system->login()){
        header("Location: /");
    }
} else if(isset($_GET['logout'])) {
    $system->logout();
    header("Location: /");
} else if(isset($_GET['regen'])) {
    $system->regen();
    header("Location: /");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Local script service</title>
</head>
<body style="background-color: #e8e8e8">
<?php
if($system->isAuthorized()) {
    require_once "user.php";
} else{
    require_once "guest.php";
    $system->printErrors();
}
?>
</body>
</html>
