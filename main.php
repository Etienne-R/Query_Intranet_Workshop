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
    $configFile = file_get_contents('config.json');
    $configJson = json_decode($configFile);
    // print($configFile);
    return ($configJson);
}

function    mainFunction()
{
    $autolog = "";
    $autolog = recupAutologinFile("autologin.txt");
    // recupWeek();
    $request = getIntraJson($autolog);
    $response = doCurlRequest($request);
    $decodedJson = json_decode($response);
    $configJson = recupConfigFile();
    foreach ($decodedJson as $elem) {
        foreach($elem as $key => $value) {
            if ($key == $configJson->ModuleCode) {
                print($value);
            }
            print(" ");
            if ($key == $configJson->ModuleName) {
                print($value);
            }
        }
        print("\n");
    }
}

mainFunction()
?>