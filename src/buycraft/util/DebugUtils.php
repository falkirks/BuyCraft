<?php
namespace buycraft\util;

use buycraft\BuyCraft;

class DebugUtils{
    /*
     * If you want to turn debugging on set this to true.
     */
    const IS_DEBUGGING = false;
    /*
     * The below fields modify debugging behavior, they
     * won't do anything unless debugging is enabled above
     * or in buycraft\BuyCraft
     */
    const WRITE_TO_FILE = true;
    const ECHO_TO_CONSOLE = true;
    public static function message($str){
        if(DebugUtils::isDebugging()){
            if(DebugUtils::ECHO_TO_CONSOLE) {
                print $str . "\n";
            }
            if(DebugUtils::WRITE_TO_FILE){
                file_put_contents("buycraft.log", $str . "\n", FILE_APPEND | LOCK_EX);
            }
        }
    }
    public static function construct($class){
        DebugUtils::message("Constructed " . get_class($class));
    }
    public static function taskRegistered($class){
        DebugUtils::message("Registered " . get_class($class));
    }
    public static function taskCalled($class){
        DebugUtils::taskRunning($class);
    }
    public static function taskRunning($class){
        DebugUtils::message("Running " . get_class($class));
    }
    public static function taskComplete($class){
        DebugUtils::message("Completed " . get_class($class));
    }
    public static function requestOut($class, $url = "N/A"){
        DebugUtils::message("Outgoing request from " . get_class($class) . " to " . $url);
    }
    public static function isDebugging(){
        return BuyCraft::IS_DEBUGGING || DebugUtils::IS_DEBUGGING;
    }
}