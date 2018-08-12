# WaterCooler Chat 1.4

WaterCooler chat is a simple/easy to use, flat file database php/ajax chat system.

![Preview](https://github.com/jonufele/WaterCooler-Chat/blob/master/preview.jpg)

## Features

- *Does not require MySQL Database / Javascript Frameworks* 
- *Multi Topic rooms*
- *Private / Read-Only Rooms*
- *Multi user*
- *Password protected profiles*
- *Smilies/BBcode*
- *Open chat interface*
- *Easy to customize themes (100% Html/Css)*
- *Private messages*
- *Moderator tools*
- *Independent display and store buffers*
- *Invite link*
- *Start chat with previous conversations listed*
- *Anti-Spam feature*
- *Account Recovery via E-mail*
- *Generate image thumbnails for faster loading*
- *Attachment uploads*
- *Smart archive system*
- *Shared Chat Across the websites hosted on the same server*
- *Private Conversation Rooms*

## Installation

 1. Copy package contents to your webserver
 2. Load the chat index.php on your browser (load the index.php first, even if you're going to embed on another page)
 2. In case you see broken images, define INCLUDE_DIR constant in "*settings.php*" - relative path to chat directory from web root *(on the very first run, the system will attempt to do this automatically)*
 3. Choose a name and login, set-up a password in your profile options in order to be assigned as the first moderator.
 4. Customize master settings and themes to match your preferences.
 5. Use *index.embedded.php* as reference if you want to embbed chat on another page.

## Requirements

 - PHP >= 5.3.0
 - Ability to change permissions if necessary
 - PHP GD library (for generating image thumbnails)

## Folder Structure

- **data**	- *Data Directory (Can be renamed or moved outside web directory)*
	- **rooms** - *Room posts/definitions*
	- **tmp** - *Temporary definitions (user ping)*
- **files** - *User Generated files*
	- **attachments**
	- **avatars**
	- **thumb**
- **includes** - *Includes directory (Includes; Ajax Server Side)*
- **themes** - *Themes*
	- ***"theme_name"***
		- **images** - *Theme images (templates, bbcode, smilies)*
		- style.css - *Styles*
		- templates.php - *Templates*
- ajax.php - *Ajax Caller*
- index.embedded.php - *Embedded chat example*
- index.php - *Chat Index*
- LICENSE - *License terms*
- README.md - *This file*
- README.txt - *Readme (plain text)*
- script.js - *Javascript / Ajax*
- settings.php - *Raw Settings*
- wcchat.class.php - *Chat Class*


## Relevant Notes

- Master moderator status will be given to the first user that joins the chat with a password.
- Moderators under edit mode: Hiding a message from the chat takes immediate effect on the other online users, while the unhide action only takes effect on user's next visit to the room.
- In case microtime function is not available, disabling the anti spam restriction may generate non unique message ids which can trouble the hide/unhide feature.
- To remove the copyright note, you must make a donation to the project (See below).
- It is recommended to rename the data directory (or move it outside the web root) to ensure user's privacy. (don't forget to rename on settings.php as well).


## Donations

If you like this project, consider making a donation:

https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9ZHN6EUXWLAQG

Thank you for choosing WaterCooler Chat.