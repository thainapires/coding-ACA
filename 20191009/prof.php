<?php
date_default_timezone_set("Europe/Lisbon");
//http://socrates.io/#aca191009

/*
 * constantes simbólicas
 * associações de símbolos a expressões
 */
define ("SLOT_DA_ULTIMA_PALAVRA", count($argv)-1);
define ("BD", "APONTAMENTOS.BD");

/*
 * escrever uma solução que possa ser utilizada como
 * "sistema de apontamentos"
 * Um apontamento é um texto qualquer, escrito pela linha
 * de comandos.
 * A nossa solução deverá suportar o arquivo de apontamentos,
 * complementado-os com a data e hora.
 * Pretende-se uma utilização com esta sintaxe:
 * php ap.php bla bla bla bla
 * o resultado, debaixo do tapete, será o arquivo datado
 * de "bla bla bla bla"
 * numa base de dados de apontamentos
 */

$iQuantidadeDeArgumentosRecebidos = $argc;
$strApontamento = "";
$bHaTextoParaJuntar = $iQuantidadeDeArgumentosRecebidos>1;

/*
 * é iterar pelos valores em $argv em juntá-los na forma
 * de um só texto, que ficará em $strApontamento
 */

if ($bHaTextoParaJuntar){
    for
    (
        $iSlot = 1;//inicialização de var(s) de controlo
        $iSlot <= SLOT_DA_ULTIMA_PALAVRA; //expressão booleana de continuidade
        $iSlot++
        //$iSlot=$iSlot+1,
        //$iSlot+=1 //atualização de identificadores
    ){
        $strPalavra = $argv[$iSlot];
        //TODO: não acrescentar um espaço em branco, na última palavra
        $strApontamento .= $strPalavra." ";
    }//for

    echo "Apontamento colhido: ".$strApontamento;
}//if há texto para juntar

/* == === != !== */
/* < <= > >= == */
/* && || ! */

$bHaApontamentoParaGravarNaBaseDeDados = !empty($strApontamento);

if ($bHaApontamentoParaGravarNaBaseDeDados){
    //gravar
    /*
     * formato da base de dados TSV (Tab Separated Values)
     * data-e-hora \t texto do apontamento \n
     * exemplo:
     * 2019-10-09 15:25:10\tAs plantas também se deslocam.\n
     */
    $strDataHora = date("Y-m-d H:i:s"); //2019-10-09 15:31:25
    $strLinha = $strDataHora."\t".$strApontamento."\n";
    /* sprintf = string print formatted */
    $strLinha = sprintf(
        "%s\t%s".PHP_EOL,
        $strDataHora,
        $strApontamento
    );

    $iBytesEscritosOuFalseSeFalhar =
        file_put_contents(
            BD,
            $strLinha,
            FILE_APPEND
        );
}//if

echo ($iBytesEscritosOuFalseSeFalhar!==false ?
    "OK, apontamento gravado!" : "Catástrofe, ativar plano Z!");