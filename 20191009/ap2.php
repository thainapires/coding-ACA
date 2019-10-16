<?php
date_default_timezone_set("Europe/Lisbon");
define ("SLOT_DA_ULTIMA_PALAVRA", count($argv)-1);
define ("BD", "APONTAMENTOS.BD");
define ("FALHA_APONTAMENTO_VAZIO", -1);

//Instalar o xDebugger que é um debug de PHP
//XAMPP

function colherApontamento(){
    global $argc, $argv;
    $iQuantidadeDeArgumentosRecebidos = $argc;
    $strApontamento = "";
    $bHaTextoParaJuntar = $iQuantidadeDeArgumentosRecebidos>1;

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
            //DONE
            if ($iSlot===SLOT_DA_ULTIMA_PALAVRA)
                $strApontamento .= $strPalavra;
            else
                $strApontamento .= $strPalavra." ";
        }//for
    }//if há texto para juntar
    return $strApontamento;
}//colherApontamento

function gravarApontamento(string $pApontamento):int
{
    $bHaApontamentoParaGravarNaBaseDeDados = !empty($pApontamento);

    if ($bHaApontamentoParaGravarNaBaseDeDados){
        //gravar
        /*
         * formato da base de dados TSV (Tab Separated Values)
         * data-e-hora \t texto do apontamento \n
         * exemplo:
         * 2019-10-09 15:25:10\tAs plantas também se deslocam.\n
         */
        $strDataHora = date("Y-m-d H:i:s"); //2019-10-09 15:31:25
        $strLinha = $strDataHora."\t".$pApontamento."\n";
        /* sprintf = string print formatted */
        $strLinha = sprintf(
            "%s\t%s".PHP_EOL,
            $strDataHora,
            $pApontamento
        );

        $iBytesEscritosOuFalseSeFalhar =
            file_put_contents(
                BD,
                $strLinha,
                FILE_APPEND
            );

        return $iBytesEscritosOuFalseSeFalhar;
    }//if
    return FALHA_APONTAMENTO_VAZIO;
}//gravarApontamento

$strApontamento = colherApontamento();
$iBytes = gravarApontamento($strApontamento);

//$iBytes = gravarApontamento(colherApontamento());

$strMensagemDeSumario = ($iBytes!==false)
    ?
    PHP_EOL."OK, apontamento gravado ($iBytes bytes escritos)!"
    :
    PHP_EOL."Falhou o registo do apontamento.";

echo $strMensagemDeSumario;