<?php

/**
 * WaterCooler Chat (Client-side/Server-side Utility Class)
 * 
 * @version 1.4
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 */
class WcPgc {

    /**
     * Handles POST requests
     * 
     * @since 1.1
     */
    public static function myPost(
        string $index, bool $bool = FALSE
    ) : string|bool {

        if (isset($_POST[$index])) {
            if($bool !== FALSE) { return TRUE; }
            if (WcUtils::hasData($_POST[$index])) {
                return WcUtils::parseName($_POST[$index]);
            }
        } elseif($bool !== FALSE) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles GET requests
     * 
     * @since 1.1
     */
    public static function myGet(
        string $index, bool $bool = FALSE
    ) : string|bool {

        if (isset($_GET[$index])) {
            if($bool !== FALSE) { return TRUE; }
            if (WcUtils::hasData($_GET[$index])) {
                return WcUtils::parseName($_GET[$index]);
            }
        } elseif($bool !== FALSE) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles COOKIE requests
     * 
     * @since 1.1
     */
    public static function myCookie(
        string $index, bool $bool = FALSE
    ) : string|bool {

        $index = WcChat::$wcPrefix . '_' . self::parseCookieName($index);
        
        if (isset($_COOKIE[$index])) {
            if($bool !== FALSE) { return TRUE; }
            if (WcUtils::hasData($_COOKIE[$index])) {
                return WcUtils::parseName($_COOKIE[$index]);
            }
        } elseif($bool !== FALSE) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles SESSION requests
     * 
     * @since 1.1
     */
    public static function mySession(
        string $index, bool $bool = FALSE
    ) : bool|string {
    
        $index = WcChat::$wcPrefix . '_' . $index;

        if (isset($_SESSION[$index])) {
            if($bool !== FALSE) { return TRUE; }
            if (WcUtils::hasData($_SESSION[$index])) {
                return WcUtils::parseName($_SESSION[$index]);
            }
        } elseif($bool !== FALSE) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles SERVER requests
     * 
     * @since 1.1
     */
    public static function myServer(
        string $index, bool $bool = FALSE
    ) : bool|string {

        if (isset($_SERVER[$index])) {
            if($bool !== FALSE) { return TRUE; }
            if (WcUtils::hasData($_SERVER[$index])) {
                return WcUtils::parseName($_SERVER[$index]);
            }
        } elseif($bool !== FALSE) {
            return FALSE;
        }
        
        return '';
    }
    
    /**
     * Sets a Cookie
     * 
     * @since 1.4
     */
    public static function wcSetCookie(
        string $name, string $value
    ) : void {
    
        setcookie(
            self::parseCookieName(WcChat::$wcPrefix . '_' . $name), 
            $value,
            time() + (86400 * WcChat::$cookieExpire), 
            '/'
        );
    }
    
    /**
     * Unsets a Cookie
     * 
     * @since 1.4
     */
    public static function wcUnsetCookie(string $name) : void {
    
        setcookie(
            self::parseCookieName(WcChat::$wcPrefix . '_' . $name), 
            '',
            time() - 3600, 
            '/'
        );
    }
    
    /**
     * Sets a Session variable
     * 
     * @since 1.4
     */
    public static function wcSetSession(
        string $name, string $value
    ) : void {
    
        $_SESSION[WcChat::$wcPrefix . '_' . $name] = $value;
    
    }
    
    /**
     * Unsets a Cookie
     * 
     * @since 1.4
     */
    public static function wcUnsetSession(string $name) : void {
        unset($_SESSION[WcChat::$wcPrefix . '_' . $name]);
    }
    
    /**
     * Parses the cookie name
     * 
     * @since 1.4
     */
    public static function parseCookieName(string $name) : string {
    
        return str_replace(
            array('.', '=', ',', ';', ' ', "\t", "\r", "\013", "\014"), 
            array('_', '_', '_', '_', '_', '', '', '', ''), 
            $name
        );    
    }

}

?>
