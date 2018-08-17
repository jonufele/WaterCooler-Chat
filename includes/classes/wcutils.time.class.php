<?php

/**
 * WaterCooler Chat (Time Utility Class)
 * 
 * @version 1.4
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 */
class WcTime {

    /**
     * Retrieves default catchWindow (window of time to catch events/pings)
     * 
     * @since 1.1
     * @param string $forced_idle
     * @return int
     */
    public static function getCatchWindow() {         
    
        return 
        (
            REFRESH_DELAY_IDLE != 0 ? 
            intval(REFRESH_DELAY_IDLE/1000) : 
            intval(REFRESH_DELAY/1000)
        ) + 
        OFFLINE_PING;   
    }
    
    /**
     * Gets microtime (If not available uses time() instead)
     * 
     * @since 1.4
     * @return int
     */
    public static function parseMicroTime() {

        if (function_exists('microtime')) {
            list($ms, $s) = explode(' ', microtime());
            return ($s+$ms);
        } else {
            return time();
        }
    }
    
    /**
     * Gets filemtime with clean cache
     * 
     * @since 1.4
     * @return int
     */
    public static function parseFileMTime($file) {
        // Be sure to call "clearstatcache()" before calling this function
        // in case the file's modification time is modfified more than once
        // during a single page execution
        return filemtime($file);
    }
    
    /**
     * Parses the difference between two timestamps into a user-friendly string
     * 
     * @since 1.1
     * @param string $date
     * @param string|null $mode
     * @return string
     */
    public static function parseIdle($date, $mode = NULL) {
    
        if($mode == NULL) {
            $it = $timesec = time()-$date;
        } else {
            $it = $timesec = $date-time();
        }

        $str = '';
        $ys = 60 * 60 * 24 * 365;

        $year = intval($timesec/$ys);
        if($year >= 1) {
            $timesec = $timesec%$ys;
        }

        $month = intval($timesec/2628000);
        if($month >= 1) {
            $timesec = $timesec%2628000;
        }
    
        $days = intval($timesec/86400);
        if($days >= 1) {
            $timesec = $timesec%86400;
        }

        $hours = intval($timesec/3600);
        if($hours >= 1) {
            $timesec = $timesec%3600;
        }

        $minutes = intval($timesec/60);
        if($minutes >= 1) {
            $timesec = $timesec%60;
        }

        $par_count = 0;

        if($year > 0) {
            $str = $year . 'Y';
            $par_count++;
        }
        if($month > 0) {
            $str .= ' ' . $month . 'M';
            $par_count++;
        }
        if($days > 0 && $par_count < 2) {
            $str .= ' ' . $days . 'd';
            $par_count++;
        }
        if($hours > 0 && $par_count < 2) {
            $str .= ' ' . $hours . 'h';
            $par_count++;
        }
        if($minutes > 0 && $par_count < 2 && $it < (60*60*2)) {
            $str .= ' ' . $minutes . 'm';
            $par_count++;
        }
        if($it < 60) {
            $str = $it . 's';
        }

        return(trim($str));
    }
    
    /**
     * Stores/Retrieves a Last Read point
     * 
     * @since 1.1
     * @param string $mode
     * @param string|null $sufix Tied to the current room if not specified
     * @return void|int
     */
    public static function handleLastRead($mode, $sufix = NULL) {

        $id = 'lastread' . 
            (
                $sufix !== NULL ? 
                '_' . $sufix : 
                '_' . WcPgc::mySession('current_room')
            );
            
        switch($mode) {
            case 'store':
                WcPgc::wcSetSession($id, self::parseMicroTime());
                WcPgc::wcSetCookie($id, self::parseMicroTime());
            break;
            case 'read':
                if(WcPgc::myCookie($id) && !WcPgc::mySession($id)) {
                    WcPgc::wcSetSession($id, WcPgc::myCookie($id));
                }
                return WcPgc::mySession($id);
            break;
        }
    }

}

?>