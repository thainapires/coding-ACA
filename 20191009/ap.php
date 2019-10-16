<?php
date_default_timezone_set("Europe/Lisbon");
define ("SLOT_DA_ULTIMA_PALAVRA", count($argv)-1); //Simbólica
define ("BD", "APONTAMENTOS.BD");

$iQuantidadeDeArgsRecebidos = $argc;
$strApontamento = " ";
$bHaTextoPJuntar = $iQuantidadeDeArgsRecebidos>1;

if($bHaTextoPJuntar){
    for(
        $iSlot = 1;
        $iSlot <= SLOT_DA_ULTIMA_PALAVRA;
        $iSlot++
    ){
        $strPalavra = $argv[$iSlot];

        if($iSlot==SLOT_DA_ULTIMA_PALAVRA)
            $strApontamento .= $strPalavra;
        else
            $strApontamento .= $strPalavra." ";
    }
    echo "Apontamento colhido: ".$strApontamento;
}

$bHaApontamentoGravarNaBD = !empty($strApontamento);

if ($bHaApontamentoGravarNaBD){
    //Gravar
    $strDataHora = date("Y-m-d H:i:s"); //2019-10-09 15:21:25
    $strLinha = $strDataHora."\t".$strApontamento."\n"; //Primeira maneira

    $strLinha = sprintf( //sprintf = string print formatted
    "%s\t%s".PHP_EOL,
        $strDataHora,
        $strApontamento
    ); //Segunda maneira

    $iBytesEscritosOuFalseSeFalhar =
        file_put_contents(BD, $strLinha, FILE_APPEND);
}//if

$strMensagemDeSumario = ($iBytesEscritosOuFalseSeFalhar!==false)
    ?
    PHP_EOL."OK, apontamento gravado! ($iBytesEscritosOuFalseSeFalhar bytes escritos)!"
    :
    PHP_EOL."Falhou o registro do apontamento";

echo $strMensagemDeSumario;
