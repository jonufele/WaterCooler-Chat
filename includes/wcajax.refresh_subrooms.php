<?php

// Refreshes Room List

    if(!isset($this)) { die(); }

    $list = $this->room->parseList(TRUE);
    $sicon = $this->room->parseSubRoomIcon(
		(strpos($list, 'nmsg.png') !== FALSE ? TRUE : FALSE)
	);
    echo $list . '____' . $sicon[0];

?>
