<?php

/**
 * Index
 * 
 * @package WaterCooler Chat
 * @author João Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, João Ferreira
 * @since 1.1
 */

	include 'wcchat.class.php';    # Change this to match your server's path to "chat.class.php" file
	$chat = new WcChat();
      echo $chat->printIndex();

?>	
