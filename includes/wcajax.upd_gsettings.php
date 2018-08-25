<?php

// Updates Global Settings

    if(!isset($this)) { die(); }

    if(!$this->user->hasPermission('GSETTINGS')) { die(); }

    // Set arrays with $_POST ids and batch process
    $arr = array();
    $gsettings_par = array(
        'TITLE', 'INCLUDE_DIR', 'REFRESH_DELAY', 'REFRESH_DELAY_IDLE', 'IDLE_START', 
        'OFFLINE_PING', 'CHAT_DSP_BUFFER', 'CHAT_STORE_BUFFER', 
        'CHAT_OLDER_MSG_STEP', 'ARCHIVE_MSG', 'BOT_MAIN_PAGE_ACCESS', 
        'ANTI_SPAM', 'IMAGE_MAX_DSP_DIM',  'IMAGE_AUTO_RESIZE_UNKN', 
        'VIDEO_WIDTH', 'VIDEO_HEIGHT', 'AVATAR_SIZE', 'DEFAULT_AVATAR', 
        'DEFAULT_ROOM', 'DEFAULT_THEME', 'INVITE_LINK_CODE', 'ACC_REC_EMAIL', 
        'ATTACHMENT_TYPES', 'ATTACHMENT_MAX_FSIZE', 'ATTACHMENT_MAX_POST_N',
        'POST_EDIT_TIMEOUT', 'MAX_DATA_LEN'
    );

    foreach($gsettings_par as $key => $value) {
        $arr[$value] = WcPgc::myPost('gs_'.strtolower($value));
    }

    $gsettings_perm = array(
        'GSETTINGS', 'ROOM_C', 'ROOM_E', 'ROOM_D', 'MOD', 'UNMOD', 
        'USER_E', 'USER_D', 'TOPIC_E', 'BAN', 'UNBAN', 'MUTE', 'UNMUTE', 
        'MSG_HIDE', 'MSG_UNHIDE', 'POST', 'POST_E', 'PROFILE_E', 'IGNORE', 
        'PM_SEND', 'PM_ROOM', 'LOGIN', 'ACC_REC', 'READ_MSG', 'ROOM_LIST', 
        'USER_LIST', 'ATTACH_UPL', 'ATTACH_DOWN'
    );

    $gsettings_perm2 = array('MMOD', 'MOD', 'CUSER', 'USER', 'GUEST');

    foreach($gsettings_perm as $key => $value) {
        $arr['PERM_'.$value] = '';
        foreach($gsettings_perm2 as $key2 => $value2) {
            if(
                WcPgc::myPost(
                    strtolower($value2) . '_' . strtolower($value)
                ) == '1'
            ) {
                $arr['PERM_' . $value] .= ' '.$value2;
            }
        }
        $arr['PERM_'.$value] = trim($arr['PERM_' . $value], ' ');
    }
    
    // Halt if errors exist
    $error = '';
    if(
        !ctype_digit(WcPgc::myPost('gs_refresh_delay')) || 
        intval(WcPgc::myPost('gs_refresh_delay')) < 1
    ) {
        $error = '- "Refresh Delay" must be an integer >= 1' . "\n";
    }
    
    if(
        !ctype_digit(WcPgc::myPost('gs_refresh_delay_idle')) || 
        intval(WcPgc::myPost('gs_refresh_delay_idle')) < 0
    ) {
        $error .= '- "Refresh delay (idle)" must be an integer >= 0' . "\n";
    }
    
    if(
        !ctype_digit(WcPgc::myPost('gs_idle_start')) || 
        intval(WcPgc::myPost('gs_idle_start')) < 60
    ) {
        $error .= '- "Idle Start" must be an integer >= 60' . "\n";
    }
    
    if(
        !ctype_digit(WcPgc::myPost('gs_offline_ping')) || 
        intval(WcPgc::myPost('gs_offline_ping')) < 1
    ) {
        $error .= '- "Offline Ping" must be an integer >= 1' . "\n";
    }
            
    if(
        !ctype_digit(WcPgc::myPost('gs_anti_spam')) || 
        intval(WcPgc::myPost('gs_anti_spam')) < 0
    ) {
        $error .= '- "Anti-SPAM" must be an integer >= 0' . "\n";
    }
    
    if(
        !ctype_digit(WcPgc::myPost('gs_chat_older_msg_step')) || 
        intval(WcPgc::myPost('gs_chat_older_msg_step')) < 1
    ) {
        $error .= '- "Older Message Load Step" must be an integer >= 1' . "\n";
    }
    
    if(
        intval(WcPgc::myPost('gs_chat_dsp_buffer')) > intval(WcPgc::myPost('gs_chat_store_buffer')) || 
        !ctype_digit(WcPgc::myPost('gs_chat_dsp_buffer')) || 
        !ctype_digit(WcPgc::myPost('gs_chat_store_buffer'))
    ) {
        $error .= '- "Chat Store Buffer" must be >= "Chat Display Buffer"' . "\n";
    }
    
    if(
        !ctype_digit(WcPgc::myPost('gs_post_edit_timeout')) || 
        intval(WcPgc::myPost('gs_post_edit_timeout')) < 0
    ) {
        $error .= '- "Post Edit Timeout" must be an integer >= 0' . "\n";
    }
    
    if(
        !ctype_digit(WcPgc::myPost('gs_max_data_len')) || 
        intval(WcPgc::myPost('gs_max_data_len')) < 0
    ) {
        $error .= '- "Max Data Len" must be an integer >= 0' . "\n";
    }
    
    if(WcPgc::myPost('gs_acc_rec_email')) {
        if(!filter_var(WcPgc::myPost('gs_acc_rec_email'), FILTER_VALIDATE_EMAIL)) {
            $error .= '- "Account Recovery Sender E-mail" is invalid!' . "\n";
        }
    }
    
    if(!file_exists(self::$roomDir . base64_encode(WcPgc::myPost('gs_default_room')) . '.txt')) {
        $error .= '- "Default Room" is invalid' . "\n";
    }
    
    if(!file_exists(self::$includeDirServer . 'themes/' . WcPgc::myPost('gs_default_theme'))) {
        $error .= '- "Default Theme" is invalid' . "\n";
    }
    
    
    if($error) { echo trim($error); die(); }

    // Update settings.php
    $res = WcFile::updateConf(
        array_merge(
            $arr,
            array(
                'LOAD_EX_MSG' => ((WcPgc::myPost('gs_load_ex_msg') == '1') ? 'TRUE' : 'FALSE'),
                'LIST_GUESTS' => ((WcPgc::myPost('gs_list_guests') == '1') ? 'TRUE' : 'FALSE'),
                'ARCHIVE_MSG' => ((WcPgc::myPost('gs_archive_msg') == '1') ? 'TRUE' : 'FALSE'),
                'BOT_MAIN_PAGE_ACCESS' => (
                    (WcPgc::myPost('gs_bot_main_page_access') == '1') ? 'TRUE' : 'FALSE'
                ),
                'GEN_REM_THUMB' => ((WcPgc::myPost('gs_gen_rem_thumb') == '1') ? 'TRUE' : 'FALSE'),
                'ATTACHMENT_UPLOADS' => (
                    (WcPgc::myPost('gs_attachment_uploads') == '1') ? 'TRUE' : 'FALSE'
                )        
            )
        )
    );

    // Parse update return value and print
    if($res === TRUE) {
        echo 'Settings Successfully Updated!';
    } else {
        echo 'Nothing to update!';
    }

?>