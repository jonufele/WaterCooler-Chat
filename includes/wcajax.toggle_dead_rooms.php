<?php

    if(WcPgc::myCookie('skip_dead_rooms') == '1') {
        WcPgc::wcUnsetCookie('skip_dead_rooms');
    } else {
        WcPgc::wcSetCookie('skip_dead_rooms', '1');
    }