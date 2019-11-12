<?php
//NasaHelper.php
//https://apod.nasa.gov/apod/ap950616.html
//https://apod.nasa.gov/apod/ap191106.html

class NasaHelper{
    const APOD_URL_BASE = "https://apod.nasa.gov/apod/";
    public function __construct($pTimeZone = false){
        $this->mTimeZone = $pTimeZone ? "Europe/Lisbon" : $pTimeZone;
        date_default_timezone_set($this->mTimeZone);
    }// __construct

    public static function auxIntToString(int $pInt, int $pNDigits) : string {
        $strInt = $pInt."";
        $iHowManyDigits = strlen($strInt);
        $bCanConvertWithoutLoss = $$iHowManyDigits<=$pNDigits;
        if($bCanConvertWithoutLoss){
            $ret = $strInt;
            while(strlen($ret)<$pNDigits) $ret = "0".$ret;
            return $ret;
        }//if
        else{
            //é necessário cortar símbolos
            $iHowManyToCut = $iHowManyDigits-$pNDigits;
            $ret = substr($strInt, $iHowManyToCut);
            return $ret;
        }
    }//auxIntToString

    const KEY_YEAR = "KEY_YEAR", KEY_MONTH="KEY_MONTH", KEY_DAY="KEY_DAY";
    public static function auxTimeStampToDateParts( $pTimeStamp = false){
        $timeStamp = $pTimeStamp===false ? time() : $pTimeStamp;
        $strTime = date("Y-m-d", $timeStamp); //"2019-11-06"
        $aTimeParts = explode("-", $strTime);
        $bCaution = is_array($aTimeParts) && count ($aTimeParts)===3;
        if($bCaution) {
            $strY = $aTimeParts[0];
            $strM = $aTimeParts[1];
            $strD = $aTimeParts[2];
            $ret[KEY_YEAR] = intval($strY);
            $ret[KEY_MONTH] = intval($strM);
            $ret[KEY_DAY] = intval($strD);
            return $ret;
        }//if
        return false;
    }//auxTimeStampToDateParts

    public function urlForDay($pY = false, $pM = false, $pD = false){
        $bSomeArgumentMissing = $pY === false || $pM === false || $pD === false;
        if($bSomeArgumentMissing){
            //Sem todos os componentes da data, recorro a data do sistema
            $myDate = self::auxTimeStampToDateParts();
        }else{
            //se tenho todos os componentes da data
            $timeStamp = mktime(0, 0, 0, $pM, $pD, $pY);
            $myDate = self::auxTimeStampToDateParts($timeStamp);
        }//if else
        //em posse da data argumentada, segmento-a
        $y = $myDate[self::KEY_YEAR]; //2018
        $m = $myDate[self::KEY_MONTH]; //12
        $d = $myDate[self::KEY_DAY]; //1

        $strYear = self::auxIntToString($y, 2); //"18"
        $strMonth = self::auxIntToString($m, 2); //"12"
        $strDay = self::auxIntToString($d, 2); //"01"

        $url = sprintf("%sap%s%s%s.html", self::APOD_URL_BASE, $strYear, $strMonth, $strDay);

        return $url;
    }
}//NasaHelper


$helper = new NasaHelper();
//NasaHelper::urlForDay(); // https://apod.nasa.gov/apod/ap191106.html
//NasaHelper::urlForDay(2018, 12, 25); //...
echo PHP_EOL;

echo NasaHelper::auxIntToString(2019,6);

echo PHP_EOL;

echo NasaHelper::auxIntToString(2019,2);

$strUrl = $helper->UrlForDay();
echo $strUrl