## Maintenance Mode

Welcome to yet another useful plugin called "Maintenance Mode".
Maintenance Mode, is essentially another whitelist plugin, which is easier to handle, and can allowicate more options for you to choose from that no other whitelist plugins offer.
Here's our current features:

- Customizable options, feature rich plugin.
- Customize the command names.
- Customize whether or not it should require permissions or if it should only rely on operators.
- Built-in commands, directly in game!
- You can now choose whether or not to use the admin flag within your whitelist messages.
- Edit your permission message directly in Config.yml file! It's as easy as that! ;D
- Require aliases, and now configurable aliases commanded sections. (whether or not to use aliases)
- This plugin also fixes an issue where the kick message wouldn't display. This is because we're relying on player join event, rather than preLogin() event. So no matter what you do, it'll directly kick you from the server with the correct message. Most plugins simply don't have a fix for this, but this plugin does. :D

- And so much more in the future!

## NOTE
- As of dev build #26, please update your config.yml file to have the option "delay". If you do not have this, then errors could occur.

## Plugin todo list
- [x] Complete Whitelist sequence.
- [ ] Add support for more customizabilities. Such as: Ban messsages, Other punishable messages??
- [ ] Revamp plugin to mark a new name along with even more customizable options.
- [ ] Directly edit options in-game!


## Plugin functionality
With this plugin, we morely rely on onJoin() event, directly when you join the server. We do this to ensure there's no bugged out messages, unlike when attempting to join a whitelisted server using preLogin() event. Other plugins simply don't have this functionality at the moment.

Another thing great about this plugin, is you can customize almost everything about this plugin. Commands, permissions, operators, aliases, permission-message, toggle maintenance mode on/off, whether to show kicked by admin message, and so much more!

