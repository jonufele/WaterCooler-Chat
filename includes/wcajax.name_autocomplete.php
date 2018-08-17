<?php

// Auto-completes a user name

    if(!isset($this)) { die(); }

    $hint = WcPgc::myGet('hint');
    
    if(WcUtils::hasData($hint)) {
        $words = explode(' ', trim($hint));
        $current_word = str_replace('@', '', $words[count($words)-1]);
        $users = WcPgc::mySession('autocomplete');
        
        if(
            strpos(
                strtolower($users), 
                ',' . strtolower($current_word)
            ) !== FALSE
        ) {
            list($tmp, $tmp2) = explode(
                ',' . strtolower($current_word), 
                strtolower($users), 
                2
            );
            list($rem_name, $tmp3) = explode(',', $tmp2);
            echo $rem_name;   
        }
    }

?>