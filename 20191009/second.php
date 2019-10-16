<?php

/*
 * Segunda Programa
 */

/*
 * argc = arguments counter
 * argv = argument value
 * argc é um nome de variável especial que permite para programas que funcione pela linha de comandos (console apps),
 * acesso ao número de argumentos que o processador de PHP está a receber
 */
$argc;
$argv;

/*
 * windows = CR + LF
 * nix = LF
 * Apple OS = CR
 */

//Para concaternar utiliza o .
echo "Quantidade de args recebidos: ".$argc;
echo PHP_EOL; //PHP End Of Line
var_dump ($argv);
echo $argv;