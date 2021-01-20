<?php

function    recupAutologinFile($fileName)
{
    $myAutologinFile = fopen($fileName, 'r');
    $line = fgets($myAutologinFile);
    fclose($myAutologinFile);
    return ($line);
}

function    recupWeek()
{
    $startDate = date("l d m Y", strtotime("-2 day"));
    $endDate = date("l d m Y", strtotime("+3 day"));
    print($startDate . " to ". $endDate . "\n");
}

function    getIntraJson($autolog)
{
    $startDate = date("Y-m-d", strtotime("-2 day"));
    $endDate = date("Y-m-d", strtotime("+4 day"));
    $request = $autolog . "/planning/load?format=json&start=" . $startDate . "&end=" . $endDate . "";
    // $request = "{$autolog}/planning/load?format=json&start=$({$startDate})&end=$({$endDate})";
    return ($request);
}

function    doCurlRequest($link)
{
    $request = curl_init();
    curl_setopt($request, CURLOPT_URL, $link);
    curl_setopt($request, CURLOPT_HEADER, false);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($request);
    curl_close($request);
    return ($response);
}

function    recupConfigFile()
{
    $configFile = file_get_contents('go_deeper.json');
    $configJson = json_decode($configFile);
    // print($configFile);
    return ($configJson);
}

function    getLongerName($decodedJson, $keyEgual)
{
    $memLen = 0;
    foreach ($decodedJson as $elem) {
            foreach($elem as $key => $value) {
                if ($key == $keyEgual) {
                    if ($memLen < strlen($value))
                        $memLen = strlen($value);
                }
            }
    }
    return ($memLen);
}

function    printTitleSection($jsonFile)
{
    foreach($jsonFile as $elem) {
        foreach ($elem as $key => $value) {
            if ($key == "TEXT") {
                echo $value;
                echo "\t\t";
            }
        }
    }
    echo "\n";
}

function    mainFunction()
{
    $autolog = "";
    $autolog = recupAutologinFile("../autologin.txt");
    // recupWeek();
    $request = getIntraJson($autolog);
    $response = doCurlRequest($request);
    $decodedJson = json_decode($response);
    $configJson = recupConfigFile();
    $maxLength = getLongerName($decodedJson, $configJson->ACTIVITI_TITLE->CONNECT_VAR_PLANNING);
    foreach ($decodedJson as $elem) {
        if ($elem->semester == $configJson->SEMESTER_VALUE[0] || $elem->semester == $configJson->SEMESTER_VALUE[1]) {
            foreach($elem as $key => $value) {
                if ($key == $configJson->MODULE->CONNECT_VAR_PLANNING && $configJson->MODULE->DISPLAY == true) {
                    echo $value;
                    echo "\t";
                }
                if ($key == $configJson->DATE_DEBUT->CONNECT_VAR_PLANNING && $configJson->DATE_DEBUT->DISPLAY == true) {
                    echo $value;
                    echo "\t";
                }
                if ($key == $configJson->DATE_FIN->CONNECT_VAR_PLANNING && $configJson->DATE_FIN->DISPLAY == true) {
                    echo $value;
                    echo "\t";
                }
                if ($key == $configJson->ACTIVITI_TITLE->CONNECT_VAR_PLANNING && $configJson->ACTIVITI_TITLE->DISPLAY == true) {
                    echo $value;
                    $len = strlen($value);
                    while ($len < $maxLength) {
                        echo " ";
                        $len++;
                    }
                }
                if ($key == $configJson->SECTION_REGISTERED->CONNECT_VAR_PLANNING && $configJson->SECTION_REGISTERED->DISPLAY == true) {
                    if ($value == null) {
                        echo "\e", $configJson->SECTION_REGISTERED->COLOR, "No", "\e[39m";
                    }
                    else {
                        echo "\e[32m", "Yes", "\e[39m";
                    }
                }
                if ($key == $configJson->MODULE_REGISTERED->CONNECT_VAR_PLANNING && $configJson->MODULE_REGISTERED->DISPLAY == true) {
                    echo "\t";
                    echo $value;
                }
            }
            echo "\n";
        }
    }
}

mainFunction()
?>