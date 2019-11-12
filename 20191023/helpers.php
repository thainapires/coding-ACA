<?php

/*
 * Apps consola para extrair informação do sistema de ficheiros.
 * Ideias:
 * - qual o maior ficheiro no meu volume c:
 * - qual o maior ficheiro na minha pasta (e sub-pastas) c:\x\
 * - descobre-me todos os ficheiros criados hoje
 * Datas para ficheiros:
 * - ctime (creation time)
 * - mtime (modified time)
 * - atime (access time)
 */

/*
 * cautela
 * em *nix o símbolo separador de níveis lógicos no FS é /
 * em Windows o símbolo separador de níveis lógicos no FS é \\
 * em Windows "moderno" pode ser que a / também seja suportada
 */
function getAllFileSystemObjectsStartingAt (
    string $pStartPath, //"d:/meus trabalhos/aca/"
    bool $pbRecursive = true //se true, navega-se por sub-dirs
)
{
    $aRet = []; //col de objetos do sistema de ficheiros (FS)
    $bCaution0 = file_exists($pStartPath);
    if ($bCaution0){
        $oFSNavigator = new DirectoryIterator($pStartPath);
        if ($oFSNavigator){
            //tenho objeto, posso trabalhar
            foreach($oFSNavigator as $o){
                //$o é um objeto do sistema de ficheiros
                //o seu tipo de dados é DirectoryIterator
                $bIsDir = $o->isDir();
                $bIsDot = $o->isDot();
                $bIsFile = $o->isFile();
                if ($bIsFile){
                    $aRet[] = clone($o);
                }//if era um ficheiro simples

                if ($pbRecursive && $bIsDir && !$bIsDot){
                    $strSubDir = $o->getRealPath(); //c:\\dir\\1.txt
                    $subEntries =
                        getAllFileSystemObjectsStartingAt(
                            $strSubDir,
                            $pbRecursive
                        );
                    $aRet = array_merge($aRet, $subEntries);

                    //foreach($subEntries as $sub) $ret[]=$sub;
                }//if
            }
        }//if
    }//if
    return $aRet;
}//getAllFileSystemObjectsStartingAt

/*
 * representar enquanto frase um só objecto do tipo DirectorIterator
 */
function fsoToString(
    DirectoryIterator $p
){
    $strRet = "";

    $bCaution0 = !empty($p);
    if ($bCaution0){
        $iSize = $p->getSize(); //tamanho em bytes
        $iSizeGbs = $iSize/1024/1024/1024;
        $aTime = date("Y-m-d H:i:s", $p->getATime());
        $cTime = date("Y-m-d H:i:s", $p->getCTime());
        $mTime = date("Y-m-d H:i:s", $p->getMTime());
        $strRealPath = $p->getRealPath();
        $strBasename = $p->getBasename();
        $strExt = $p->getExtension();

        $strRet = sprintf(
            "%s [%d byte(s) %d GBytes]\nCreated: %s\nModified: %s\nAccessed: %s\n".
            "Real path: %s\nExtension: %s".PHP_EOL,
            $strBasename,
            $iSize, $iSizeGbs,
            $cTime, $mTime, $aTime, $strRealPath, $strExt
        );
        return $strRet;
    }//bCaution0
    return $strRet;
}//fsoToString

/*
 * representar enquanto frase uma coleção (array) de objetos DirectoryIterator
 * cada objeto saberá representar-se via fsoToString
 */
function fsosToString(array $pFsos){
    $strRet = "";
    foreach($pFsos as $o) $strRet.= fsoToString ($o);
    return $strRet;
}//fsosToString

function sortFileSystemObjects(
    array &$p//, //passagem por referência, ou seja p vai pudar o arg q c ele casar
    //$pCriterion //criteria é o plural de criterion
){
    //user associate sort
    //uasort($p, $pCriterion);
    usort($p, "bySizeAsc");
}//sortFileSystemObjects

function bySizeAsc(
    $pa,
    $pb
){
    $iSizeA = $pa->getSize();
    $iSizeB = $pb->getSize();
    if ($iSizeA>$iSizeB) return +1;
    if ($iSizeA<$iSizeB) return -1;
    return 0;
}//bySizeAsc

function bySizeDesc(
    $pa,
    $pb
){
    $iSizeA = $pa->getSize();
    $iSizeB = $pb->getSize();
    if ($iSizeA>$iSizeB) return -1;
    if ($iSizeA<$iSizeB) return +1;
    return 0;
}//bySizeDesc

function byCreationDate(
    $pa,
    $pb
){
    $iCtimeA = $pa->getCTime();
    $iCtimeB = $pb->getCTime();
    if ($iCtimeA>$iCtimeB) return +1;
    if ($iCtimeA<$iCtimeB) return -1;
    return 0;
}//byCreationDate