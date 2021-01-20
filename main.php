<?php

function    recupAutologinFile($fileName)
{
    $myAutologinFile = fopen($fileName, 'r');
    $line = fgets($myAutologinFile);
    fclose($myAutologinFile);
    return ($line);
}

recupAutologinFile("autologin.txt")

?>