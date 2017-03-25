<?php

class PseudoCrypt {
 
    /* Key: Next prime greater than 36 ^ n / 1.618033988749894848 */
    /* Value: modular multiplicative inverse */
    private static $golden_primes = array(
        '1'                  => '1',
        '23'                 => '11',
        '809'                => '809',
        '28837'              => '29485',
        '1038073'            => '179017',
        '37370153'           => '47534873',
        '1345325473'         => '264202849',
        '48431716939'        => '19727015779',
        '1743541808839'      => '1532265214711',
        '62767505117101'     => '67935388019749'
    );
 
    /* Ascii :                    0  9,         A  Z   */
    /* $chars = array_merge(range(48,57), range(65,90) */
    private static $chars36 = array(
        0=>48,1=>49,2=>50,3=>51,4=>52,5=>53,6=>54,7=>55,8=>56,9=>57,10=>65,
        11=>66,12=>67,13=>68,14=>69,15=>70,16=>71,17=>72,18=>73,19=>74,20=>75,
        21=>76,22=>77,23=>78,24=>79,25=>80,26=>81,27=>82,28=>83,29=>84,30=>85,
        31=>86,32=>87,33=>88,34=>89,35=>90
    );
 
    public static function base36($int) {
        $key = "";
        while(bccomp($int, 0) > 0) {
            $mod = bcmod($int, 36);
            $key .= chr(self::$chars36[$mod]);
            $int = bcdiv($int, 36);
        }
        return strrev($key);
    }
 
    public static function hash($num, $len = 5) {
        $ceil = bcpow(36, $len);
        $primes = array_keys(self::$golden_primes);
        $prime = $primes[$len];
        $dec = bcmod(bcmul($num, $prime), $ceil);
        $hash = self::base36($dec);
        return str_pad($hash, $len, "0", STR_PAD_LEFT);
    }
 
    public static function unbase36($key) {
        $int = 0;
        foreach(str_split(strrev($key)) as $i => $char) {
            $dec = array_search(ord($char), self::$chars36);
            $int = bcadd(bcmul($dec, bcpow(36, $i)), $int);
        }
        return $int;
    }
 
    public static function unhash($hash) {
        $len = strlen($hash);
        $ceil = bcpow(36, $len);
        $mmiprimes = array_values(self::$golden_primes);
        $mmi = $mmiprimes[$len];
        $num = self::unbase36($hash);
        $dec = bcmod(bcmul($num, $mmi), $ceil);
        return $dec;
    }
 
}