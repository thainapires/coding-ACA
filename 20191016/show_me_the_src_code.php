<?php

//noticias do dia.php

/*
 * SHOW THE SOURCE CODE OF A GIVEN URL
 */

//CONSTANTES SIMBOLICAS
define("QTD_MIN_DE_ARGS", 2);
define("INDEX_DO_URL", 1);

$bRecebiUmUrlCujoSrcCodeEParaSerMostrado = ($argc>=QTD_MIN_DE_ARGS ) && (!empty($argv[INDEX_DO_URL]));

if($bRecebiUmUrlCujoSrcCodeEParaSerMostrado){
    $url = $argv[1];
    $srcCode = file_get_contents($url);
    $srcCode = trim($srcCode);
    echo
    strlen($srcCode)===0 ?
        "Não consegui obter o source code (URL online?)" : $srcCode;
}//if
else{
    echo "URL esperado não recebido.";
}//else