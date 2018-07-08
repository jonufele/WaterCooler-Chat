**********************************************
----------------------------------------------
*  WaterCooler Chat 1.4                      *
----------------------------------------------
  Copyright (c) 2018
  v1.4 originally written by João Ferreira

----------------------------------------------
*  README                                    *
----------------------------------------------
**********************************************


===========================================================================
                       INSTALATION
===========================================================================

   1 - Copy package contents to your webserver

   3 - Define INCLUDE_DIR constant in "settings.php" - relative path to chat directory from caller page, in case it's on the same directory set it as empty)

   4 - Load the chat on a browser and login, set-up a password in your profile options in order to be assigned as the first moderator.

   5 - Customize master settings and themes to match your preferences.

   6 - Use index.embedded.php as reference if you want to embbed chat on another page.

============================================================================
		       WHAT IS WATERCOOLER CHAT
============================================================================

   WaterCooler chat is a flat file database php/ajax chat system.

============================================================================
                       REQUIREMENTS
============================================================================

  - PHP >= 5.3.0
  - Ability to change permissions if necessary
  - PHP GD library (for generating image thumbnails)

============================================================================
                       FOLDER STRUCTURE
============================================================================

   |- data                 - Data Directory (Can be renamed or moved outside web directory)
      |- Rooms             - Room posts/definitions
      |- tmp               - Temporary definitions (user ping)
   |- files                - User Gewnerated files
      |- attachments
      |- avatars
      |- thumb
   |- themes               - Themes
      |- "theme_name"
	 |+ images         - Theme images (templates, bbcode, smilies)
         |- style.css      - Styles
         |- templates.php  - Templates
   |- ajax.php             - Ajax Caller
   |- index.embedded.php   - Embedded chat example
   |- index.php            - Chat Index
   |- LICENSE.TXT          - License
   |- README.txt           - This file
   |- script.js            - Javascript / Ajax
   |- settings.php         - Raw settings file
   |- wcchat.class.php     - Chat class

============================================================================
                       RELEVANT NOTES
============================================================================
 
  - The master moderator status will be given to the first user that joins the chat with a password.

  - The action of hiding a message from the chat takes immediate effect on the other online users, while the unhide action only takes effect on user's next visit to the room.

  - It is not recommended to disable the anti spam restriction, for two reasons: the spam (duh), and the possibility of generating non unique message ids which can harm the hide/unhide feature.

  - To remove the copyright note, you must make a donation to the project.

  - It is recommended to rename the data directory (or move it outside the web root) to ensure user's privacy. (don't forget to rename on settings.php as well)


============================================================================
                       DONATIONS
============================================================================

If you like this project, consider making a donation:

https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9ZHN6EUXWLAQG

Thank you for choosing WaterCooler Chat.

