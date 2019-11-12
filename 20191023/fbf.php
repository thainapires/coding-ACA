<?php

/*
 * console app: Find Biggest File
 * What does it do? traverses a starting path, looking for the biggest file in it "FBF"
 * fbf "n:\v"
 */

//Se houver erros, podem passar desapercebidos
//include "c:\\coisas de outros\\etc.php";
//include_once "d:/coida de maria/maria.php"; //Não repete importações

//Abortam o processo de importação se houver erros
//require "e://mais tralha//2.php";
require_once "helpers.php"; //Sempre veremos essa, não faz repetições e se houver erros aborta

if(!empty($argv[1])){
    $strPath = $argv[1];
    $aFsos = getAllFileSystemObjectsStartingAt($strPath);
    //echo fsosToString($aFsos); //Listar todos os objs
    /*
     * TODO: identificar o maior dos objetos
     */
    sortFileSystemObjects(
        $aFsos//, //o array a ser sorted
        //"bySizeAsc" //Nome de uma function que vai assistir no sort
    );

    $iSlotOfTheFinalObject = count($aFsos)-1;
    $oBiggestSize = $aFsos[$iSlotOfTheFinalObject];
    echo fsosToString($oBiggestSize);
}else{
    echo "Please input a start path.";
}



