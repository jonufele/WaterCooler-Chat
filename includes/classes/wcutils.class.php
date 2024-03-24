<?php

/**
 * WaterCooler Chat (Uncategorized Utilities Class)
 * 
 * @version 1.4
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 */
class WcUtils {

    /**
     * Checks if settings errors exist
     * 
     * @since 1.4
     */    
    public static function settingsHasErrors() : bool|string {
    
        // Halt if errors exist
        $errors = '';
        if(
            !is_int(REFRESH_DELAY) || 
            intval(REFRESH_DELAY) < 1
        ) {
            $errors = '- REFRESH_DELAY must be an integer >= 1' . "\n";
        }
        
        if(
            !is_int(REFRESH_DELAY_IDLE) || 
            intval(REFRESH_DELAY_IDLE) < 0
        ) {
            $errors .= '- REFRESH_DELAY_IDLE must be an integer >= 0' . "\n";
        }
        
        if(
            !is_int(IDLE_START) || 
            intval(IDLE_START) < 60
        ) {
            $errors .= '- IDLE_START must be an integer >= 60' . "\n";
        }
        
        if(
            !is_int(OFFLINE_PING) || 
            intval(OFFLINE_PING) < 1
        ) {
            $errors .= '- OFFLINE_PING must be an integer >= 1' . "\n";
        }
                
        if(
            !is_int(ANTI_SPAM) || 
            intval(ANTI_SPAM) < 0
        ) {
            $errors .= '- ANTI_SPAM must be an integer >= 0' . "\n";
        }
        
        if(
            !is_int(CHAT_OLDER_MSG_STEP) || 
            intval(CHAT_OLDER_MSG_STEP) < 1
        ) {
            $errors .= '- CHAT_OLDER_MSG_STEP must be an integer >= 1' . "\n";
        }
        
        if(
            intval(CHAT_DSP_BUFFER) > intval(CHAT_STORE_BUFFER) || 
            !is_int(CHAT_DSP_BUFFER) || 
            !is_int(CHAT_STORE_BUFFER)
        ) {
            $errors .= '- CHAT_STORE_BUFFER must be >= CHAT_DSP_BUFFER' . "\n";
        }
        
        if(
            !is_int(POST_EDIT_TIMEOUT) || 
            intval(POST_EDIT_TIMEOUT) < 0
        ) {
            $errors .= '- "POST_EDIT_TIMEOUT" must be an integer >= 0' . "\n";
        }
    
        if(
            !is_int(MAX_DATA_LEN) || 
            intval(MAX_DATA_LEN) < 0
        ) {
            $errors .= '- "MAX_DATA_LEN" must be an integer >= 0' . "\n";
        }
        
        if(ACC_REC_EMAIL) {
            if(!filter_var(ACC_REC_EMAIL, FILTER_VALIDATE_EMAIL)) {
                $errors .= '- ACC_REC_EMAIL is invalid!' . "\n";
            }
        }
        
        // Check if current room is valid, but only if the user lists exists
        // (to avoid the error on the very first run)
        if(
            !file_exists(WcChat::$roomDir . base64_encode(DEFAULT_ROOM) . '.txt') && 
            self::hasData((string)WcChat::$roomDir) && 
            file_exists(WcChat::$dataDir . 'users.txt')
            
        ) {
            $errors .= '- DEFAULT_ROOM is invalid' . "\n";
        }
        
        if(!file_exists(WcChat::$includeDirServer . 'themes/' . DEFAULT_THEME)) {
            $errors .= '- DEFAULT_THEME is invalid' . "\n";
        }
        
        return ($errors ? nl2br(trim($errors)) : FALSE);
    }

    /**
     * Checks bot access / Prevents bot login
     * 
     * @since 1.4
     */
    public static function botAccess() : void {
    
        if(BOT_MAIN_PAGE_ACCESS === FALSE || WcPgc::myPost('cname')) {
            require_once WcChat::$includeDirServer . "includes/bots.php";
            if (preg_match($wc_botcheck_regex, WcPgc::myServer('HTTP_USER_AGENT'))) {
                header("HTTP/1.0 403 Forbidden");
                echo WcGui::popTemplate('wcchat.botNoAccessNote');
                exit;
            }
        }
    }
    


   /**
     * Strips a name/var of troublesome characters
     * 
     * @since 1.4
     */
    public static function parseName(string $name) : string {
    
        return
            str_replace(
                array(
                    "\r",
                    "\t",
                    "\013",
                    "\014"
                ),
                '',
                $name
            );
    }

    /**
     * Parses an error message if it exists
     * 
     * @since 1.1
     */
    public static function parseError(string $error) : string {
    
        if(self::hasData($error)) {
            return WcGui::popTemplate('wcchat.error_msg', array('ERR' => $error));
        } else {
            return '';
        }
    }

    /**
     * Generates Mail Headers
     * 
     * @since 1.3
     */
    public static function mailHeaders(
        string $from, string $fname, string $to, string $toname
    ) {
        $headers = "MIME-Version: 1.0\n";
        $headers.= "From: ".$fname." <".$from.">\n";
        $headers .= "To: ".$toname."  <".$to.">\n";
        $headers .= "Reply-To: " . $from . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\n";
        $headers .= "Return-Path: <" . $from . ">\n";
        $headers .= "Errors-To: <" . $from . ">\n"; 
        $headers .= "Content-Type: text/plain; charset=iso-8859-1\n";

        return($headers);
    }

    /**
     * Generates A Random Number
     * 
     * @since 1.2
     */
    public static function randNumb(int $n) : string {
    
        $output = '';
          $salt = '0123456789AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';
          srand((int)((float)microtime()*1000000));
          $i = 0;
          while ($i < $n) {
            $num = rand() % 33;
            $tmp = substr($salt, $num, 1);
            $output .= $tmp;
            $i++;
        }
        return $output;
    }

    /**
     * Checks if a variable contains data
     * 
     * @since 1.1
     */
    public static function hasData(string $var) : bool {

        if (strlen($var) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

?>
