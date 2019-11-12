<?php

/*
 * Apps de console para extrair informação do sistema de ficheiros
 * Ideias:
 * -> Qual o maior ficheiro no meu volume c:
 * -> Qual o maior ficheiro na minha pasta (e sub-pastas) c:\x\
 * -> Descobre todos os ficheiros criados hoje
 * Datas para ficheiros:
 * -> ctime (creation time)
 * -> mtime (modified time)
 * -> atime (access time)
 */

/*
 * Cautela:
 * em *nix o símbolo separador de níveis lógicos no FS é /
 * em Windows o símbolo separador de níveis lógicos no FS é \\
 * em Windows moderno pode ser que a / também seja suportado
 */
function getAllFileSystemObjectsStartingAt(
    //string $pStartPath //"c:\\thp"
    string $pStartPath, //"c:/thp/"
    //boolean $pDepthFirst
    bool $pbRecursive = true //se true, navega-se por sub-dirs
){
    $aRet = []; //Coleção de objetos do sistema de ficheiros (FS)
    $bCaution0 = file_exists($pStartPath);
    if($bCaution0){
        $oFSNavigator = new DirectoryIterator($pStartPath);
        if( $oFSNavigator){
            //tenho objetos, posso trabalhar
            foreach($oFSNavigator as $o){
                //$o é um objeto do sistema de ficheiros
                //O seu tipo de dados é DirectoryIterator
                $bIsDir = $o->isDir();
                $bIsDot = $o->isDot();
                $bIsFile = $o->isFile();
                if($bIsFile){
                    $aRet[] = clone($o); //!!!!
                }//if era um ficheiro simples
                if($pbRecursive && $bIsDir && !$bIsDot){
                    $strSubDir = $o->getRealPath(); //c:\\dir\\1.txt
                    $subEntries =
                        getAllFileSystemObjectsStartingAt(
                            $strSubDir,
                            $pbRecursive
                        );
                    $aRet = array_merge($aRet, $subEntries);
                    //foreach($subEntries as $subs) $ret[]=$sub;
                }//if
            }
        }//if
    }//if
    return $aRet;
}//getAllFileSYstemObjectsStartingAt

/*
 * Representar enquanto frase um só objetto do tipo DirectorIterator
 */
function fsoToString(
    DirectoryIterator $p
){
    $strRet = "";
    //$bCaution0 = $p!=null;
    $bCaution0 = !empty($p);
    if($bCaution0){
        $iSize = $p->getSize(); //tamanho em bytes
        $iSizeGbs = $iSize/1024/1024/1024;
        $aTime = date("Y-m-d H:i:s", $p->getATime());
        $cTime = date("Y-m-d H:i:s", $p->getCTime());
        $mTime = date("Y-m-d H:i:s", $p->getMTime());
        $strRealPath = $p->getRealPath();
        $strBaseName = $p->getBasename();
        $strExt = $p->getExtension();

        $strRet = sprintf(
            "%s [%d bytes(s) %d GBytes]\nCreated %s\nModified: %s\nAccessed: %s\n"."Real path: %s\nExtention: %s". PHP_EOL,
            $strBaseName,
            $iSize, $iSizeGbs,
            $cTime, $mTime, $aTime, $strRealPath, $strExt
        );
        return $strRet;
    }//bCaution0
    return $strRet;
}//fsoToString

/*
 * Representar enquanto frase uma coleção (array) de objetos DirectoryIterator
 * Cada objeto será representado via fSoToString
 */
function fsosToString(array $pFsos){
    $strRet = "";
    foreach($pFsos as $o) $strRet.= fsoToString ($o);
    return $strRet;
}//fsosToString

define("DOCUMENTOS_PATH", "C:\\Users\\thain\\Videos\\Séries");

$aCol = getAllFileSystemObjectsStartingAt(DOCUMENTOS_PATH, true);
//var_dump($aCol);
$strResult = fsosToString($aCol);
echo $strResult;