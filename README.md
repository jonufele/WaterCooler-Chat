# WaterCooler Chat 1.4

WaterCooler chat is a flat file database php/ajax chat system.
Some of its features: 
- *Multi Topic rooms*
- *Private Rooms*
- *multi user*
- *password protected profiles*
- *smilies/BBcode*
- *open chat interface*
- *Easy to customize themes (100% Html/Css)*
- *private messages*
- *moderator tools*
- *Independent display and store buffers*
- *Invite link*
- *Start chat with previous conversations listed*
- *Anti-Spam feature*
- *Account Recovery via E-mail*
- *Generate image thumbnails for faster loading*
- *Attachment uploads*

## Instalation

 1. Copy package contents to your webserver
 2. Define *INCLUDE_DIR* constant in "*settings.php*" - relative path to chat directory from caller page, in case it's on the same directory set it as empty)
 3. Load the chat on a browser and login, set-up a password in your profile options in order to be assigned as the first moderator.
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
- **themes** - *Themes*
	- ***"theme_name"***
		- **images** - *Theme images (templates, bbcode, smilies)*
		- *style.css* - Styles
		- *templates.php* - *Templates*
- *ajax.php* - *Ajax Caller*
- *index.embedded.php* - *Embedded chat example*
- *index.php* - *Chat Index*
- *LICENSE* - *License terms*
- *README.txt* - *This file*
- *script.js* - *Javascript / Ajax*
- *settings.php* - *Raw Settings*
- *wcchat.class.php* - *Chat Class*


## Relevant Notes

- The master moderator status will be given to the first user that joins the chat with a password
- The action of hiding a message from the chat takes immediate effect on the other online users, while the unhide action only takes effect on user's next visit to the room.
- It is not recommended to disable the anti spam restriction, for two reasons: the spam (duh), and the possibility of generating non unique message ids which can harm the hide/unhide feature.
- To remove the copyright note, you must make a donation to the project.
- It is recommended to rename the data directory (or move it outside the web root) to ensure user's privacy. (don't forget to rename on settings.php as well)

## Donations

If you like this project, consider making a donation:

https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=9ZHN6EUXWLAQG

Thank you for choosing WaterCooler Chat.