<?php

function    recupAutologinFile($fileName)
{
    $myAutologinFile = fopen($fileName, 'r');
    $line = fgets($myAutologinFile);
    fclose($myAutologinFile);
    return ($line);
}

function    recupWeek(&$startDate, &$endDate)
{
    $startDate = date("l d m Y", strtotime("-2 day"));
    $endDate = date("l d m Y", strtotime("+3 day"));
    print($startDate . " to ". $endDate . "\n");
}

function    getIntraJson($autolog)
{
    $startDate = date("Y-m-d", strtotime("-2 day"));
    $endDate = date("Y-m-d", strtotime("+4 day"));
    print($startDate . "\n");
    print($endDate . "\n");
    $request = $autolog . "/planning/load?format=json&start=$(" . $startDate . ")&end=$(" . $endDate . ")";
    // print($request . "\n");
}

function    mainFunction()
{
    $autolog = "";
    $autolog = recupAutologinFile("autologin.txt");
    recupWeek($startDate, $endDate);
    getIntraJson($autolog);
}

mainFunction()
?>