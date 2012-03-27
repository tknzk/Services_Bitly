<?php
/**
 * example.php
 *
 */

//require_once 'Services/Bitly.php';
require_once '../Bitly.php';

$login = 'tknzk';
$apikey = 'R_217a01eee7732db5568ae350c18b88fd';
$domain = 'c.tknzk.net';

try {
    $bitly = new Services_Bitly($login,$apikey);
    //$bitly = new Services_Bitly($login,$apikey, $domain);
    $bitly->setBaseDomain($domain);
    //var_dump($bitly);
    //var_dump($bitly);
    $shorten = $bitly->shorten("http://openpear.org/package/Services_Bitly");
} catch (Services_Bitly_Exception $e) {
    echo $e->getMessage();
}
echo $shorten . "\n";

try {
    //$bitly = new Services_Bitly($login,$apikey, $domain);
    $bitly = new Services_Bitly($login,$apikey);
    $bitly->setBaseDomain($domain);
    $expand = $bitly->expand($shorten);
} catch (Services_Bitly_Exception $e) {
    echo $e->getMessage();
}
echo $expand . "\n";
//exit;



// j.mp対応

try {
    $bitly = new Services_Bitly($login,$apikey);
    $bitly->setBaseDomain('j.mp');
    $shorten = $bitly->shorten("http://openpear.org");
} catch (Services_Bitly_Exception $e) {
    echo $e->getMessage();
}
echo $shorten . "\n";

try {
    $bitly = new Services_Bitly($login,$apikey);
    $bitly->setBaseDomain('j.mp');
    //$bitly->setBaseDomain('c.tknzk.net');
    $expand = $bitly->expand($shorten);
} catch (Services_Bitly_Exception $e) {
    echo $e->getMessage();
}
echo $expand . "\n";

