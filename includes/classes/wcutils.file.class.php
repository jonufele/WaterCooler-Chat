<?php

/**
 * WaterCooler Chat (File Utility Class)
 * 
 * @version 1.4
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 */
class WcFile {


    /**
     * Checks if non writable folders exist
     * 
     * @since 1.4
     */
    public static function hasNonWritableFolders() : bool|string {

        $output = '';
        $tmp1 = is_writable(WcChat::$roomDir);
        $tmp1_2 = is_writable(WcChat::$roomDir . 'archived/');
        $tmp2 = is_writable(WcChat::$dataDir);
        $tmp3 = is_writable(WcChat::$dataDir . 'tmp/');
        $tmp4 = is_writable(WcChat::$includeDirServer . 'files/');
        $tmp5 = is_writable(WcChat::$includeDirServer . 'files/attachments/');
        $tmp6 = is_writable(WcChat::$includeDirServer . 'files/avatars/');
        $tmp7 = is_writable(WcChat::$includeDirServer . 'files/thumb/');

        if(!$tmp1 || !$tmp1_2 || !$tmp2 || !$tmp3 || !$tmp4 || !$tmp5 || !$tmp6 || !$tmp7) {

            if(!$tmp1 || !$tmp1_2 || !$tmp2 || !$tmp3) {
                $output .= '<h2>Data Directories (Absolute Server Path)</h2>';
            }

            if(!$tmp1) { $output .= addslashes(WcChat::$roomDir) . "\n"; }
            if(!$tmp1_2) { $output .= addslashes(WcChat::$roomDir) . 'archived/' . "\n"; }
            if(!$tmp2) { $output .= addslashes(WcChat::$dataDir) . "\n"; }
            if(!$tmp3) { $output .= addslashes(WcChat::$dataDir . 'tmp/') . "\n"; }
            if(!$tmp4 || !$tmp5 || !$tmp6 || !$tmp7) {
                $output .= '<h2>File Directories (Relative Web Path)</h2>';
            }
            if(!$tmp4) { $output .= addslashes(WcChat::$includeDir . 'files/') . "\n"; }
            if(!$tmp5) { $output .= addslashes(WcChat::$includeDir . 'files/attachments/') . "\n"; }
            if(!$tmp6) { $output .= addslashes(WcChat::$includeDir . 'files/avatars/') . "\n"; }
            if(!$tmp7) { $output .= addslashes(WcChat::$includeDir . 'files/thumb') . "\n"; }

            return nl2br(trim($output));
        } else {
            return FALSE;
        }
    }

   /**
     * Writes to a file and locks write access
     * 
     * @since 1.1
     */
    public static function writeFile(
        string $file, string $content, string $mode, 
        bool $allow_empty_content = FALSE
    ) : void {
    
        if(strlen(trim($content)) > 0 || $allow_empty_content) {
            $handle = fopen($file, $mode);
            if($handle) {
                while (!flock($handle, LOCK_EX | LOCK_NB)) {
                    sleep(1);
                }
                fwrite($handle, $content);
                fflush($handle);
                flock($handle, LOCK_UN);

                fclose($handle);
            }
        }
    }

    /**
     * Writes an event to the event messages file (temporary messages)
     * 
     * @since 1.1
     */
    public static function writeEvent(
        string $user_name, string $msg, string $target = NULL, 
        bool $return_raw_line = FALSE
    ) : string {

        // Overwrite content if last modified time is higher than Idle catchWindow
        // (Enough time for all online users to get the update (Idle or not))
        if((time()-WcTime::parseFileMTime(EVENTL)) > WcChat::$catchWindow) { 
            $mode = 'w'; 
        } else { 
            $mode = 'a';
        }
        
        $towrite = 
            WcTime::parseMicroTime() . '|' . 
            base64_encode($user_name) . 
            (
                $target !== NULL ? 
                    '|' . (
                    strpos($target, '*') !== FALSE ? 
                        $target : 
                        base64_encode($target)
                    ) : 
                ''
            ) . 
            '|<b>' . $user_name . '</b> ' . 
            $msg . "\n"
        ;

        // Write event
        self::writeFile(EVENTL, $towrite, $mode);

        // Return raw line to be parsed to sender
        if($return_raw_line !== FALSE) {
            return $towrite;
        }
        return '';
    }

    /**
     * Reads a file while stripping troublesome characters
     * 
     * @since 1.4
     */
    public static function readFile(string $source) : string {
    
        return
            str_replace(
                array(
                    "\r",
                    "\t",
                    "\013",
                    "\014"
                ),
                '',
                file_get_contents($source)
            );
    }

    /**
     * Updates settings.php file
     * 
     * @since 1.3
     */
    public static function updateConf(array $array) : bool {

        $conf = self::readFile(WcChat::$includeDirServer . 'settings.php');
        $conf_bak = $conf;

        foreach($array as $key => $value) {
            $constant_value = constant($key);
            if($constant_value === TRUE) { $constant_value = 'TRUE'; }
            if($constant_value === FALSE) { $constant_value = 'FALSE'; }
            if(ctype_digit($value) || $value == 'TRUE' || $value == 'FALSE') {
                $conf = str_replace(
                    "define('{$key}', {$constant_value})", 
                    "define('{$key}', {$value})", 
                    $conf
                );
            } else {
                $conf = str_replace(
                    "define('{$key}', '{$constant_value}')", 
                    "define('{$key}', '{$value}')", 
                    $conf
                );
            }
        }
        $handle = fopen(WcChat::$includeDirServer . 'settings.php', 'w');
        fwrite($handle, $conf);
        fclose($handle);

        if(self::readFile(WcChat::$includeDirServer . 'settings.php') != $conf_bak) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Gets The File Extention
     * 
     * @since 1.3
     */
    public static function getFileExt(string $file_path) : string|bool {
    
        $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        if(WcUtils::hasData($ext)) {
            return $ext;
        } else {
            return FALSE;
        }
    }

}

?>
