<?php
if(!defined("MAIN_PAGE")){
    header("Location: /");
    exit;
}
?>
<ul>
    <li>
        <a href="/?logout=1">Logout</a>
    </li>
    <li>
        <a href="/?regen=1">New key</a>
    </li>
</ul>
<h3>Powered by Vladislav Kolodka</h3>