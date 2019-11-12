<?php
date_default_timezone_set("Europe/Lisbon");
//noticias do dia.php

/*
 * console app para consumir os URLs das noticias do dia, conforme publicadas no momento em https://observador.pt/
 */

define ("FONTE_NOTICIOSA", "http://observador.pt/");
define ("ONDE_RECORTAR", "href=\""); //escape da aspa
//define ("ONDE_RECORTAR", 'href="'); //escape da aspa | em php não é a mesma coisa

/*
 * Cautela, em PHP " e ' não são sinônimos:
 * $pi = 3.14
 * echo "$pi"; //frase avaliativa -> 3.14
 * echo '$pi'; //frase literal => $pi
 */

$srcCodeComAsNoticias = file_get_contents(FONTE_NOTICIOSA);

$bCautelaHouveRespostas = strlen($srcCodeComAsNoticias)>0;

if($bCautelaHouveRespostas){
    //havendo resposta, vou recortar da resposta, os URLs para as noticias do dia
    //$filtro="/2019/10/16"; //Problema é que amanhã é dia 17
    $aComAsPartesRecortadas =
    explode(ONDE_RECORTAR, $srcCodeComAsNoticias);
    //$arrayUrlsParaNoticias = array();

    $arrayUrlsParaNoticias = [];

    $iQuantosElementos = count($aComAsPartesRecortadas);
    for($idx=0+1; $idx<$iQuantosElementos; $idx++){
        $href = $aComAsPartesRecortadas[$idx];
        //enderçamento automagico = arrumação no #1 endereço disponível
            //$arrayUrlsParaNoticias[] = $href; //SEM FILTRO
            $href = trim($href);
        //problemas do href
        /*
        1) Url's relativas /seccao/cientia (Só queremos Url's absolutos)
        2) Identificar a aspa
        3) Só URL's do dia
        */

        //Será que href começa por http?
        $bAbsUrl = stripos( $href, "http")===0;
        if($bAbsUrl){
            //castrar onde aparecer a primeira aspa
            $href = substr($href, 0, stripos($href, "\""));
        }
        //castração feita, substring só até aspa obtida
        //Url do dia?

        $filtroDoDia = date("/Y/m/d/");
        $bFiltroAparece = stripos($href, $filtroDoDia)!==false;

        $bNovoHref = array_search($href, $arrayUrlsParaNoticias)===false;
        if($bFiltroAparece && $bNovoHref)
            $arrayUrlsParaNoticias[] = $href;
    }
    $filtroDoDia= date("/Y/m/d/"); //"2019/10/16/
    //var_dump ($aComAsPartesRecortadas);
    //var_dump ($arrayUrlsParaNoticias);

}