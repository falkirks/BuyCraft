<?php
if(extension_loaded('phar')){
    $phar = new \Phar(__DIR__);
    date_default_timezone_set("UTC");
    echo "Checking for updates...\n";;
    $ch = curl_init("https://api.github.com/repos/" . $phar->getMetaData()["authors"][0] . "/" . $phar->getMetaData()["name"] . "/releases");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0"]);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
    curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $ret = json_decode(curl_exec($ch), true);
    curl_close($ch);
    
    if($ret[0]["tag_name"] != "v" . $phar->getMetaData()["version"]){
        echo "[!] There is a newer version on GitHub.\n\n";
        echo "Update details\n";
        echo "----------------\n";
        echo "Version: " . $ret[0]["tag_name"] . "\n";
        echo "Name: " . $ret[0]["name"] . "\n";
        echo "Details: " . $ret[0]["body"] . "\n\n";
        echo "[?] Would you like to update now?";
        if(trim(fgets(STDIN)) == "y") {
            $fp = fopen(__DIR__ . '/' . $ret[0]["assets"][0]["name"], 'w+');
            $ch = curl_init($ret[0]["assets"][0]["browser_download_url"]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 50);
            curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
            if($ret[0]["assets"][0]["name"] == basename(__FILE__)){
                echo "[!] Download complete. The new phar has replaced the old one.\n";
                echo "[!] Hope you enjoy the new version :)\n";
            }
            else{
                echo "[?] Download complete. Would you like to delete me?";
                if(trim(fgets(STDIN)) == "y") {
                    unlink(__FILE__);
                    echo "[!] Ouch! That's me cleaned up. Hope you enjoy the newer version :)\n";
                }
                else{
                    echo "[#] See ya later.\n";
                }
            }
        }
        else{
            echo "[#] Okay, bye then ;)\n";
        }
    }
    else{
        echo "[#] Your version is up to date.\n";
    }
}
