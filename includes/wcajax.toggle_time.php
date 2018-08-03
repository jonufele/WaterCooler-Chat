<?php

// Shows/Hides TimeStamps

    if($this->myCookie('hide_time')) {
        $this->wcUnsetCookie('hide_time');
    } else {
        $this->wcSetCookie('hide_time', '1');
    }

?>