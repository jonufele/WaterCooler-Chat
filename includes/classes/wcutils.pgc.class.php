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
     * @param string $index
     * @return string|void Returns void if unset/empty, otherwise string
     */
    public static function myPost($index, $bool = NULL) {

        if (isset($_POST[$index])) {
            if($bool !== NULL) { return TRUE; }
            if (WcUtils::hasData($_POST[$index])) {
                return WcUtils::parseName($_POST[$index]);
            }
        } elseif($bool !== NULL) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles GET requests
     * 
     * @since 1.1
     * @param string $index
     * @return string|void Returns void if unset/empty, otherwise string
     */
    public static function myGet($index, $bool = NULL) {

        if (isset($_GET[$index])) {
            if($bool !== NULL) { return TRUE; }
            if (WcUtils::hasData($_GET[$index])) {
                return WcUtils::parseName($_GET[$index]);
            }
        } elseif($bool !== NULL) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles COOKIE requests
     * 
     * @since 1.1
     * @param string $index
     * @return string|void Returns void if unset/empty, otherwise string
     */
    public static function myCookie($index, $bool = NULL) {

        $index = WcChat::$wcPrefix . '_' . self::parseCookieName($index);
        
        if (isset($_COOKIE[$index])) {
            if($bool !== NULL) { return TRUE; }
            if (WcUtils::hasData($_COOKIE[$index])) {
                return WcUtils::parseName($_COOKIE[$index]);
            }
        } elseif($bool !== NULL) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles SESSION requests
     * 
     * @since 1.1
     * @param string $index
     * @return string|void Returns void if unset/empty, otherwise string
     */
    public static function mySession($index, $bool = NULL) {
    
        $index = WcChat::$wcPrefix . '_' . $index;

        if (isset($_SESSION[$index])) {
            if($bool !== NULL) { return TRUE; }
            if (WcUtils::hasData($_SESSION[$index])) {
                return WcUtils::parseName($_SESSION[$index]);
            }
        } elseif($bool !== NULL) {
            return FALSE;
        }
        
        return '';
    }

    /**
     * Handles SERVER requests
     * 
     * @since 1.1
     * @param string $index
     * @return string|void Returns void if unset/empty, otherwise string
     */
    public static function myServer($index, $bool = NULL) {

        if (isset($_SERVER[$index])) {
            if($bool !== NULL) { return TRUE; }
            if (WcUtils::hasData($_SERVER[$index])) {
                return WcUtils::parseName($_SERVER[$index]);
            }
        } elseif($bool !== NULL) {
            return FALSE;
        }
        
        return '';
    }
    
    /**
     * Sets a Cookie
     * 
     * @since 1.4
     * @param string $name
     * @param string $value     
     * @return void
     */
    public static function wcSetCookie($name, $value) {
    
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
     * @param string $name   
     * @return void
     */
    public static function wcUnsetCookie($name) {
    
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
     * @param string $name
     * @param string $value        
     * @return void
     */
    public static function wcSetSession($name, $value) {
    
        $_SESSION[WcChat::$wcPrefix . '_' . $name] = $value;
    
    }
    
    /**
     * Unsets a Cookie
     * 
     * @since 1.4
     * @param string $name   
     * @return void
     */
    public static function wcUnsetSession($name) {
    
        unset($_SESSION[WcChat::$wcPrefix . '_' . $name]);
    }
    
    /**
     * Parses the cookie name
     * 
     * @since 1.4
     * @param string $name
     * @return string
     */
    public static function parseCookieName($name) {
    
        return str_replace(
            array('.', '=', ',', ';', ' ', "\t", "\r", "\013", "\014"), 
            array('_', '_', '_', '_', '_', '', '', '', ''), 
            $name
        );    
    }

}

?>