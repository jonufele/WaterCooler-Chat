<?php

    if(!isset($this)) { die(); }

    $par = explode(WcPgc::myGet('id') . '|', $this->room->rawMsgList, 2);
    
    if(isset($par[1])) {
        $par2 = explode("\n", $par[1]);
        $edited_id = 'editbox_cont_' . WcPgc::myGet('id');
        echo WcGui::popTemplate(
            'wcchat.posts.edit_box',
            array(
                'BBCODE' => WcGui::popTemplate(
                    'wcchat.toolbar.bbcode',
                    array(
                        
                        'SMILIES' => WcGui::iSmiley($edited_id, $edited_id),
                        'FIELD' => $edited_id,
                        'CONT' => $edited_id,
                        'ATTACHMENT_UPLOADS' => ''
                    )
                ),
                'ID' => WcPgc::myGet('id'),
                'TAG' => WcPgc::myGet('tag'),
                'NOTE' => WcGui::popTemplate(
                    'wcchat.posts.edit_box.note',
                    array(
                        'TIMEOUT' => WcTime::parseIdle(
                            (time()-POST_EDIT_TIMEOUT)
                        )
                    ),
                    POST_EDIT_TIMEOUT != 0
                ),
                'CONTENT' => str_replace('<br>', "\n", $par2[0])
            )
        );
        
        sleep(1);
    }

?>