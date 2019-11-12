<?php
//show_me_the_src_code.php

/*
 * console app para mostrar o source code
 * de qualquer URL recebido
 */

//constantes simbólicas, num esforço para +inteligibilidade
define ("QUANTIDADE_MIN_DE_ARGS", 2);
define ("INDEX_DO_URL", 1);

$bRecebiUmURLCujoSourceCodeEParaSerMostrado =
    ($argc>=QUANTIDADE_MIN_DE_ARGS)
    &&
    (!empty($argv[INDEX_DO_URL]));

if ($bRecebiUmURLCujoSourceCodeEParaSerMostrado){
    $url = $argv[1];
    $srcCode = file_get_contents($url);
    /*
     * trim (" a b c ") ---> "a b c"
     */
    $srcCode = trim($srcCode);
    echo
    strlen($srcCode)===0 ?
        "Não consegui obter o source code (URL online?)"
        :
        $srcCode;
}//if
else{
    echo "URL esperado não recebido.";
}//else