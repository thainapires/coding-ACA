<?php

/*
Escrever uma solução que possa ser utilizada como "sistema de apontamentos"
Um apontamento é um texto qualquer, escrito pela linha de comandos.
A nossa solução deverá suportar o arquivos de apontamentos, complementando-os
com a data e hora.
Pretende-se uma utilização com esta sintaxe:

php ap.php bla bla bla

O resultado, debaixo do tapete, será o arquivo datado de "bla bla bla" numa
base de dados de apontamentos

*/

$iQuantidadeDeArgsRecebidos = $argc;
$strApontamento = " ";
$bHaTextoPJuntar = $iQuantidadeDeArgsRecebidos>1;

/*
 * é iterar pelos valores em $argv e juntá-los na forma de um só texto, que ficará em $strApontamento
 */

//Constante simbolica e de classe

//count($argv) -> Contar em php
define ("SLOT_DA_ULTIMA_PALAVRA", count($argv)-1); //Simbólica

if($bHaTextoPJuntar){
    for(
        $iSlot = 1;//(opcional) inicialização de var(s) de controlo
        $iSlot <= SLOT_DA_ULTIMA_PALAVRA;//(opcional) expressão booleana de continuidade
        $iSlot++
        //$iSlot=$iSlot+1
        //$iSlot+=1
        //(opcional) atualização de identificadores
    ){
        $strPalavra = $argv[$iSlot];
        //TODO: não acrescentar um espaço em branco, na última palavra
        $strApontamento .= $strPalavra." ";
    }
    echo "Apontamento colhido: ".$strApontamento;

}//if há texto para juntar

/* == === != !== */
/* < <= > >= == */
/* && || ! */

$bHaApontamentoGravarNaBD = !empty($strApontamento);

if ($bHaApontamentoGravarNaBD){
    //Gravar
    /*
     * formato da base de dados TSV (Tab Separated Values)
     * data-e-hora \t texto do apontamento \n
     * exemplo:
     * 2019-10-09 15:25:10\tAs plantas também se deslocal .\n
     */
}
