<?php

/**
 * WaterCooler Chat (Graphic User Interface Utility Class)
 * 
 * @version 1.4
 * @author Joao Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, Joao Ferreira
 */
class WcGui {

    /**
     * Populates Templates with data from an array
     * 
     * @since 1.1
     * @param string $model Template Model
     * @param array|null $data
     * @param bool|null $cond Condition to display
     * @param string $no_cond_content Content to display if condition is not met
     * @return string|void Html Template
     */
    public static function popTemplate(
        $model, $data = NULL, 
        $cond = NULL, $no_cond_content = NULL
    ) {
    
        $out = '';
        if (!isset($cond) || (isset($cond) && $cond == TRUE)) {
            // Import template model
            $out = WcChat::$templates[$model];

            // Replace provided tokens
            if (isset($data)) {
                if (is_array($data)) {
                    foreach ($data as $key => $value) {
                        $out = str_replace(
                            "{" . $key . "}", 
                            stripslashes($data[$key]), 
                            $out
                        );
                    }
                }
            }
            $out = trim($out, "\n\r");

            // Replace system tokens
            return str_replace(
                array(
                    '{CALLER}',
                    '{INCLUDE_DIR}',
                    '{INCLUDE_DIR_THEME}',
                    '{PREFIX}'
                ),
                array(
                    WcChat::$ajaxCaller,
                    WcChat::$includeDir,
                    WcChat::$includeDirTheme,
                    WcChat::$wcPrefix
                ),
                $out
            );
        } else {
            if($no_cond_content == NULL) {
                return '';
            } else {
                return $no_cond_content;
            }
        }
    }
    
    /**
     * Includes Theme Templates
     * 
     * @since 1.4
     * @return void
     */
    public static function includeTemplates() {
        $array = array(
            'global_sett', 'index', 'info', 'join',
            'login', 'main', 'posts', 'profile_sett',
            'rooms', 'static_msg', 'text_input', 'themes',
            'toolbar', 'topic', 'users'
        );
        
        foreach($array as $value) {
            include 
                WcChat::$includeDirServer . 'themes/' . 
                THEME . '/templates/wctplt.' . 
                $value . '.php'
            ;
        }
        
        return $templates;        
    }
    
    /**
     * Generates the Smiley interface
     * 
     * @since 1.1
     * @param string $field Target Html Id
     * @return string Html Template
     */
    public static function iSmiley($field, $cont) {
    
        $out = '';
        $s1 =
            array(
                '1' => ':)',
                '3' => ':o',
                '2' => ':(',
                '4' => ':|',
                '5' => ':frust:',
                '6' => ':D',
                '7' => ':p',
                '8' => '-_-',
                '10' => ':E',
                '11' => ':mad:',
                '12' => '^_^',
                '13' => ':cry:',
                '14' => ':inoc:',
                '15' => ':z',
                '16' => ':love:',
                '17' => '@_@',
                '18' => ':sweat:',
                '19' => ':ann:',
                '20' => ':susp:',
                '9' => '>_<'
            );

        // Parse smiley codes to html
        foreach($s1 as $key => $value) {
            $out .=
                self::popTemplate('wcchat.toolbar.smiley.item',
                    array(
                        'title' => $value,
                        'field' => $field,
                        'cont' => $cont,
                        'str_pat' => $value,
                        'str_rep' => 'sm' . $key . '.gif'
                    )
                );
        }

        return($out);

    }

    /**
     * Generates the theme List
     * 
     * @since 1.2
     * @return string Html Template
     */
    public static function parseThemes() {
    
        $options = '';
        foreach(glob(WcChat::$includeDirServer . 'themes/*') as $file) {
            $title = str_replace(
                WcChat::$includeDirServer . 'themes/', 
                '', 
                $file
            );
            if($title != '.' AND $title != '..') {
                $options .= self::popTemplate(
                    'wcchat.themes.option',
                    array(
                        'TITLE' => $title,
                        'VALUE' => $title,
                        'SELECTED' => (($title == THEME) ? ' SELECTED' : '')
                    )
                );
            }
        }
        
        return self::popTemplate(
            'wcchat.themes', 
            array(
                'OPTIONS' => $options, 
                'COOKIE_EXPIRE' => WcChat::$cookieExpire
            )
        );
    }
    
    /**
     * Parses BBCODE/Smilie Tags Into Html codes
     * 
     * @since 1.1
     * @param string $data
     * @param bool $down_perm
     * @param string $user_name          
     * @return string Parsed Msg
     */
    public static function parseBbcode($data, $down_perm, $user_name, $msg_id = NULL) {
    
        $search =
            array(
                '/\[b\](.*?)\[\/b\]/i',
                '/\[i\](.*?)\[\/i\]/i',
                '/\[u\](.*?)\[\/u\]/i',
                '/\[img](.*?)\[\/img\]/i',
                '/\[imga\|([0-9]+)x([0-9]+)\|([0-9]+)x([0-9]+)[|]?\](.*?)\[\/img\]/i',
                '/\[imga\|([0-9]+)x([0-9]+)\|([0-9]+)x([0-9]+)\|tn_([0-9A-Z]+)[|]?\](.*?)\[\/img\]/i',
                '/\[img\|([0-9]+)x([0-9]+)\|([0-9]+)x([0-9]+)[|]?\](.*?)\[\/img\]/i',
                '/\[img\|([0-9]+)x([0-9]+)\|([0-9]+)x([0-9]+)\|tn_([0-9A-Z]+)[|]?\](.*?)\[\/img\]/i',
                '/\[img\|([0-9]+)[|]?\](.*?)\[\/img\]/i',
                '/\[url\="(.*?)"\](.*?)\[\/url\]/i',
                '/\[attach_(.*?)_([0-9a-f]+)_([0-9]+)_([A-Za-z0-9 _\.]+)\]/i',
                '/https:\/\/www\.youtube\.com\/watch\?v=([0-9a-zA-Z-+_=]*)/i',
                '/\[YOUTUBE\]([0-9a-zA-Z-+_=]*?)\[\/YOUTUBE\]/i',
                '/(?<!href=\"|src=\"|\])((http|ftp)+(s)?:\/\/[^<>\s]+)/i'
               );

        // Download permission alert message
        $down_alert = (
            $down_perm ? 
            '' : 
            'onclick="alert(\'Your usergroup does not have permission to download arquives / full size images!\'); return false;"'
        );
        
        $all = ((WcPgc::myGet('mode') != 'send_msg') ? WcPgc::myGet('all') : 'ALL');

        $replace =
            array(
                '<b>\\1</b>',
                '<i>\\1</i>',
                '<u>\\1</u>',
                '<div style="margin: 10px">
                    <img src="\\1" class="thumb" onload="wc_scroll(\''.$all.'\')">
                </div>',
                '<div style="width: \\3px;" class="thumb_container">
                    <img src="' . WcChat::$includeDir . 'files/attachments/\\5" style="width: \\3px; height: \\4px;" class="thumb" onload="wc_scroll(\''.$all.'\')"><br>
                    <img src="' . WcChat::$includeDirTheme . 'images/attach.png">
                    <a href="' . ($down_perm ? WcChat::$includeDir . 'files/attachments/\\5' : '#') . '" target="_blank" ' . $down_alert . '>\\1 x \\2</a>
                </div>',
                '<div style="width: \\3px;" class="thumb_container">
                    <img src="' . WcChat::$includeDir . 'files/thumb/tn_\\5.jpg" class="thumb" onload="wc_scroll(\''.$all.'\')"><br>
                    <img src="' . WcChat::$includeDirTheme . 'images/attach.png">
                    <a href="' . ($down_perm ? WcChat::$includeDir . 'files/attachments/\\6' : '#') . '" target="_blank" ' . $down_alert . '>\\1 x \\2</a>
                </div>',
                '<div style="width: \\3px;" class="thumb_container">
                    <img src="\\5" style="width: \\3px; height: \\4px;" class="thumb" onload="wc_scroll(\''.$all.'\')"><br>
                    <a href="' . ($down_perm ? '\\5' : '#') . '" target="_blank" ' . $down_alert . '>\\1 x \\2</a>
                </div>',
                '<div style="width: \\3px;" class="thumb_container">
                    <img src="' . WcChat::$includeDir . 'files/thumb/tn_\\5.jpg" class="thumb" onload="wc_scroll(\''.$all.'\')"><br>
                    <a href="' . ($down_perm ? '\\6' : '#') . '" target="_blank" ' . $down_alert.'>\\1 x \\2</a>
                </div>',
                '<div style="width: \\1px;" class="thumb_container">
                    <img src="\\2" style="width: \\1px;" class="thumb" onload="wc_scroll(\''.$all.'\')"><br>
                    <a href="' . ($down_perm ? '\\2' : '#').'" target="_blank" ' . $down_alert . '>Unknown Dimensions</a>
                </div>',
                '<a href="\\1" target="_blank">\\2</a>',
                '<div style="margin:10px;">
                    <i>
                        <img src="' . WcChat::$includeDirTheme . 'images/attach.png"> 
                        <a href="' . ($down_perm ? WcChat::$includeDir . 'files/attachments/\\1_\\2_\\4' : '#') . '" target="_blank" ' . $down_alert . '>\\4</a> 
                        <span style="font-size: 10px">(\\3KB)</span>
                    </i>
                </div>',
                '<div id="im_'.$msg_id.'\\1">
                    <a href="#" onclick="wc_pop_vid(\''.$msg_id.'\\1\', \'\\1\', ' . VIDEO_WIDTH . ', ' . VIDEO_HEIGHT . '); return false;">
                        <img src="' . WcChat::$includeDirTheme . 'images/video_cover.jpg" class="thumb" style="margin: 10px; width: '.IMAGE_MAX_DSP_DIM.'px" onload="wc_scroll(\''.$all.'\')">
                    </a>
                </div>
                <div id="wc_video_'.$msg_id.'\\1" class="closed"></div>',
                '<div id="im_'.$msg_id.'\\1">
                    <a href="#" onclick="wc_pop_vid(\''.$msg_id.'\\1\', \'\\1\', ' . VIDEO_WIDTH . ', ' . VIDEO_HEIGHT . '); return false;">
                        <img src="' . WcChat::$includeDirTheme . 'images/play.png" style="float: left; position: relative; left: 20px; top: 5px;z-index: 3" onload="wc_scroll(\''.$all.'\')">
                        <img src="' . WcChat::$includeDir . 'files/thumb/tn_youtube_\\1.jpg" class="thumb" style="margin: 10px; position: relative; left: 0;" onload="wc_scroll(\''.$all.'\')">
                    </a>
                </div>
                <div id="wc_video_'.$msg_id.'\\1" class="closed"></div>',
                '<a href="\\0" target="_blank" style="font-size:10px;font-family:tahoma">\\0</a>'
            );

        $output = preg_replace($search, $replace, $data);

        // Process smilies next with the bbcode
        $smilies =
            array(
                '1' => ':)',
                '3' => ':o',
                '2' => ':(',
                '4' => ':|',
                '5' => ':frust:',
                '6' => ':D',
                '7' => ':p',
                '8' => '-_-',
                '10' => ':E',
                '11' => ':mad:',
                '12' => '^_^',
                '13' => ':cry:',
                '14' => ':inoc:',
                '15' => ':z',
                '16' => ':love:',
                '17' => '@_@',
                '18' => ':sweat:',
                '19' => ':ann:',
                '20' => ':susp:',
                '9' => '>_<'
            );


        foreach($smilies as $key => $value) {
            $output =
                str_replace(
                    $value,
                    self::popTemplate(
                        'wcchat.toolbar.smiley.item.parsed',
                        array('key' => $key)
                    ),
                    $output
                );
        }
        
        // Highligh current user references witin the text
        $output = str_replace(
            '@'.$user_name, 
            self::popTemplate(
                'wcchat.posts.curr_user_ref',
                array('NAME' => $user_name)
            ), 
            $output
        );

        return $output;
    }
    
    /**
     * Parses a long posted message and generates toggle interface
     * 
     * @since 1.4
     * @param string $data
     * @return string Parsed Data
     */
    public static function parseLongMsg($data, $id) {
        
        $string_len = strlen($data);
        if($string_len > MAX_DATA_LEN && MAX_DATA_LEN > 0) {
            $cropped = substr($data, 0, MAX_DATA_LEN);
            return WcGui::popTemplate(
                'wcchat.posts.partial_content',
                array(
                    'ID' => $id,
                    'PARTIAL' => $cropped,
                    'FULL' => $data
                )
            );
        } else {
            return $data;
        }
            
    }
    
    /**
     * Parses links within a string and generates thumbnail bbcode tags
     * 
     * @since 1.4
     * @param string $data
     * @return string Parsed Data
     */
    public static function parseBBcodeThumb($text) {
        if(
            !preg_match('/(?<!=\"|\[IMG\]|[|]\])((http|ftp)+(s)?:\/\/[^<>\s]+)(.jpg|.jpeg|.png|.gif)([^ ]*)/i', $text) && 
            !preg_match('/\[IMG\](.*?)\[\/IMG\]/i', $text) && 
            !preg_match('/https:\/\/www\.youtube\.com\/watch\?v=([0-9a-zA-Z-+_=]*)([^ ]*)/i', $text)
        ) {
            $text = trim($text);
        } else {
            // Image(s) detected, try parse to more detailed tags with information about the image(s)
            $text = preg_replace_callback(
                '/(?<!=\"|\[IMG\]|[|]\])((http|ftp)+(s)?:\/\/[^<>\s]+(\.jpg|\.jpeg|\.png|\.gif)([^ ]*))/i',
                'WcImg::parseImg',
                trim($text)
            );
            $text = preg_replace_callback(
                '/\[IMG\](.*?)\[\/IMG\]/i',
                'WcImg::parseImg',
                trim($text)
            );
            $text = preg_replace_callback(
                '/https:\/\/www\.youtube\.com\/watch\?v=([0-9a-zA-Z-+_=]*)/',
                'WcImg::parseVideoImg',
                trim($text)
            );
        }
        
        return $text;
    }
    
}

?>