<?php

    if(!isset($this)) { die(); }
    
    if(
        WcUtils::hasData(WcPgc::myGet('new_data')) && 
        WcUtils::hasData(WcPgc::myGet('id'))
    ) {

        $new_id = base64_encode(time() . '-' . 
            $this->user->name) . '-' . 
            WcPgc::myGet('id');

        $post_owner = '';
        $par = explode('|', str_replace('*', '', WcPgc::myGet('id')));
        if(strpos($par[1], '-') !== FALSE) {
            $par2 = explode('-', $par[1]);
            $post_owner = base64_decode($par2[1]);
        } else {
            $post_owner = base64_decode($par[1]);
        }

        $time = $par[0];
        if(strpos($par[0], '-') !== FALSE) {
            $par3 = explode('-', $par[0]);
            $time = $par3[1];
        }
        
        $edit_permission = 
            (
                $this->user->hasPermission('POST_E', 'skip_msg') && 
                strpos($this->room->rawMsgList, WcPgc::myGet('id')) !== FALSE && 
                (
                    (
                        (time() < ($time + POST_EDIT_TIMEOUT) || POST_EDIT_TIMEOUT == 0) && 
                        $this->user->name == $post_owner
                    ) || $this->user->isMod
                )
            ) ? TRUE : FALSE;
            
        if(!$edit_permission) {
            echo "ERROR: Cannot Edit post! Possible causes:\n- Access denied\n- Invalid Message Id\n- Expired Edit Timeout\n- The message was archived/discarded";
            die();
        }
        
        $parsed_data =
            $new_id . '|' . str_replace(
                "\n", 
                '<br>', 
                strip_tags(
                    WcGui::parseBbcodeThumb(WcPgc::myGet('new_data'))    
                )
            );
   
        WcFile::writeFile(
            MESSAGES_LOC,
            preg_replace(
                '/^('.preg_quote(
                    (WcUtils::hasData(WcPgc::myGet('tag')) ? WcPgc::myGet('tag') . '-' : '').
                    WcPgc::myGet('id')
                ).')\|(.*)$/im', 
                str_replace(array('$0', '$1'), array('\$0', '\$1'), $parsed_data), 
                $this->room->rawMsgList
            ),
            'w'
        );
        
        $id_updated_msg = $this->user->name . '$' . str_replace('|', '$', WcPgc::myGet('id'));
        WcFile::writeFile(
            MESSAGES_UPDATED,
            str_replace(' ' . $id_updated_msg, '', $this->room->rawUpdatedMsgList) . 
            ' ' . $id_updated_msg,
            'w'
        );
        
        echo $this->room->parseMsg(
            array($parsed_data),
            0,
            'RETRIEVE',
            NULL,
            'RELOAD_MSG'
        );
        
        sleep(1);
    }

?>