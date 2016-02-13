<?php
if(!defined("MAIN_PAGE")){
    header("Location: /");
    exit;
}
?>
<h1 style="text-align: center">
Authorization
</h1>
<div style="margin: 0 auto; width: 178px;">
    <form method="post" action="index.php">
        <table>
            <tr>
                <td><input type="text" name="nick" placeholder="Username"></td>
            </tr>
            <tr>
                <td><input type="password" name="pass" placeholder="Password"></td>
            </tr>
            <tr>
                <td><input type="submit" name="send" value="Login" style="width: 100%"></td>
            </tr>
        </table>
    </form>
</div>
<h3 style="text-align: center">Powered by Vladislav Kolodka</h3>