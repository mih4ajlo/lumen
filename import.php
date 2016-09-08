<?php
include "config.php";
include "functions.php";

//echo getMainCats();

?>



<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="UTF-8">
    <title>Importer</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/black-tie/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="script.js"></script>
</head>

<body>
    <div id="top">
        <div id="poruka">&nbsp;</div>
        Izaberite godinu sa kojom radite:
        <select id="year" name="year" size="1">

            <option value="2012">2012</option>
            <option value="2013">2013</option>
            <option value="2014">2014</option>
            <option value="2015" selected="selected">2015</option>
            <option value="2016">2016</option>
        </select>

        Izaberite tip dokumenta:
        <select id="tip" name="tip" size="1">
            <option value="" selected="selected"></option>
            <option value="izvestaj">izvestaj</option>
            <option value="dodatni">dodatni</option>
        </select>

        <button>Uporedi</button>
    </div>
    <div id="parsedNav"></div>
    <div id="parsedCont"></div>
    <div id="outNav"></div>
    <div id="outCont"></div>
    <div id="mainDocContent"></div>
</body>

</html>
