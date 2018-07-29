<?php

/**
 * Ajax Caller
 * 
 * @package WaterCooler Chat
 * @author João Ferreira <jflei@sapo.pt>
 * @copyright (c) 2018, João Ferreira
 * @since 1.1
 */

include __DIR__.'/wcchat.class.php';
$chat = new WcChat();
$chat->ajax();

?>