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

============================================================================
		       WHAT IS WATERCOOLER CHAT
============================================================================

   WaterCooler chat is a simple/easy to use, flat file database php/ajax chat system.


===========================================================================
                       FEATURES
===========================================================================

    - Does not require MySQL Database / Javascript Frameworks 
    - Multi Topic rooms
    - Private/Read-Only Rooms
    - multi user
    - password protected profiles
    - smilies/BBcode
    - open chat interface
    - Easy to customize themes (100% Html/Css)
    - private messages
    - moderator tools
    - Independent display and store buffers
    - Invite link
    - Start chat with previous conversations listed
    - Anti-Spam feature
    - Account Recovery via E-mail
    - Generate image thumbnails for faster loading
    - Attachment uploads
    - Smart archive system
    - Shared Chat Across the websites hosted on the same server
    - Private Conversation Rooms


===========================================================================
                       INSTALLATION
===========================================================================

   1 - Copy package contents to your webserver

   2 - Load the chat index.php on your browser (load the index.php first, even if you're going to embed on another page).

   3 - In case you see broken images, define INCLUDE_DIR constant in "settings.php" - relative path to chat directory from web root (on the very first run, the system will attempt to do this automatically)

   4 - Choose a name and login, set-up a password in your profile options in order to be assigned as the first moderator.

   5 - Customize master settings and themes to match your preferences.

   6 - Use index.embedded.php as reference if you want to embbed chat on another page.


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
   |- files                - User Generated files
      |- attachments
      |- avatars
      |- thumb
   |- includes             - Includes directory (Includes, Ajax server side)
   |- themes               - Themes
      |- "theme_name"
	 |+ images         - Theme images (templates, bbcode, smilies)
         |- style.css      - Styles
         |- templates.php  - Templates
   |- ajax.php             - Ajax Caller
   |- index.embedded.php   - Embedded chat example
   |- index.php            - Chat Index
   |- LICENSE.TXT          - License
   |- README.md            - Markdown Readme
   |- README.txt           - This file
   |- script.js            - Javascript / Ajax
   |- settings.php         - Raw settings file
   |- wcchat.class.php     - Chat class


============================================================================
                       RELEVANT NOTES
============================================================================
 
  - Master moderator status will be given to the first user that joins the chat with a password.

  - Moderators under edit mode: Hiding a message from the chat takes immediate effect on the other online users, while the unhide action only takes effect on user's next visit to the room.

  - In case microtime function is not available, disabling the anti spam restriction may generate non unique message ids which can trouble the hide/unhide feature.

  - To remove the copyright note, you must make a donation to the project (See below).

  - It is recommended to rename the data directory (or move it outside the web root) to ensure user's privacy. (don't forget to rename on settings.php as well)


============================================================================
                       DONATIONS
============================================================================

If you like this project, consider making a donation:

https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9ZHN6EUXWLAQG

Thank you for choosing WaterCooler Chat.

