<?php

/*
 * Helper para consumos de prostylex.org
 */

class Prostylex {
    const AUDIOBOOKS_URL = "https://prostylex.org/torrents.php?cat=53";
    const MARK_TORRENT_PAGE = "torrents-details.php?id=";
    const PROXYLEX_BASE_URL = "https://prostylex.org/";
    const CUTTING_MARK = "&hit=";

    // Padrão: https://prostylex.org/torrents-details.php?id=
    //https://prostylex.org/torrents-details.php?id=331891&hit=1

    /*
     * Recebe uma URL e retorna o conteúdo server-side, servido a partir da URL
     */

    public function consumeUrl(string $pUrl){
        $curlHandler = curl_init();
        $bResult = curl_setopt($curlHandler, CURLOPT_URL, $pUrl);
        $bResult = curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, false);
        $bResult = curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
        $bResult = curl_setopt($curlHandler, CURLOPT_BINARYTRANSFER, true);
        $bin = curl_exec($curlHandler);
        return $bin;
    }//consumeUrl

    //:array : Estabelece retorno da função
    //Array com todos os "a's"

    public function getHyperlinksFromDOMFromSource(string $pHtml) : array{
        $ret = [];
        $bCaution = is_string($pHtml) && strlen($pHtml)>0;
        if($bCaution){
            $oDOM = new DOMDocument();
            if($oDOM){
                @$bSuccessOrFalse = $oDOM->loadHTML($pHtml);
                if ($bSuccessOrFalse){
                    $as = $oDOM->getElementsByTagName("a");
                     // anchor <a href="http://xpto/d/file">âncora</a>
                    foreach ($as as $a){
                        $href = $a->getAttribute('href');
                        $anchor = $a->nodeValue;
                        $ret[] = array("href" => $href, "anchor" => $anchor);
                    }//foreach
                }//if
            }//if
        }//if
        return $ret;
    }//getHyperLinksFromDOMFromSource

    public function getTorrents($pAllLinks){ //may include undesired content
        $ret = [];
        $hrefs = [];
        foreach ($pAllLinks as $aDuoHrefAnchor){
            $anchor = $aDuoHrefAnchor["anchor"];
            $href = $aDuoHrefAnchor["href"];
            //$bHrefIsForTorrent = stripos($href, Prostylex::MARK_TORRENT_PAGE)!==false;
            $bHrefIsForTorrent = stripos($href, self::MARK_TORRENT_PAGE)!==false;
            if($bHrefIsForTorrent){
                $href = self::PROXYLEX_BASE_URL.$href;
                $iCuttingMarkPos = stripos($href, self::CUTTING_MARK);
                $bMustCut = $iCuttingMarkPos!==false;
                if($bMustCut){
                    $href = substr($href, 0, $iCuttingMarkPos);
                }//if
                //$hrefs[] = $href; - apagou
                //array_search($href, $ret) === false; //sempre true que seria, maneira errada de procurar
                $bNewHref = array_search($href, $hrefs) === false;
                if($bNewHref){
                    $ret[] = array(
                        "anchor" => $anchor,
                        "href" =>$href
                    );
                    $hrefs[] = $href;
                }//if
            }
        }//foreach
        return $ret;
    }//getTorrents

    public function presentTorrents($paTorrents){
        $ret = "";
        foreach($paTorrents as $t){
            $anchor = $t["anchor"];
            $href = $t["href"];
            $str = sprintf("%s%s".PHP_EOL, $anchor, $href);
            $ret.=$str;
        }
        return $ret;
    }

    public function presentTorrentsAsHtml($paTorrents){
        $ret = "<ol>";
        foreach($paTorrents as $t){
            $anchor = $t["anchor"];
            $href = $t["href"];
            $str = sprintf("<li><a href='%s'>%s</a></li>".PHP_EOL, $anchor, $href);
            $ret.=$str;
        }
        $ret .="</ol>";
        return $ret;
    }
}//Prostylex

/*
define ("TEST_URL", "https://arturmarques.com/edu/aca/");
$o = new Prostylex();
$html = $o->consumeUrl(TEST_URL);
echo $html;
*/

$o = new Prostylex();
$html = $o->consumeUrl(Prostylex::AUDIOBOOKS_URL);
$as = $o->getHyperlinksFromDOMFromSource($html);
$ts = $o->getTorrents($as);
$output = $o->presentTorrents($ts);
echo $output;

/*
$ts = $o->getTorrents($o->getHyperlinksFromDOMFromSource
    ($o->consumeUrl(Prostylex::AUDIOBOOKS_URL)));
echo $o->presentTorrentsAsHtml($ts);
*/


/*
define ("TEST_URL", "https://arturmarques.com/edu/aca/");
$o = new Prostylex();
$html = $o->consumeUrl(TEST_URL);
echo $html;
*/