# Installation
1. Edit your `composer.json` to require `"sgtaziz/steamauth": "~1.0"`.
2. Run `composer update` to download and install the package.
3. Copy `vendor/sgtaziz/steamauth/src/config/steamauth.php` to `config/steamauth.php`.
4. Edit `steamauth.php` to include your Steam API key.
5. Open up `config/app.php` and add `'sgtaziz\SteamAuth\SteamAuthServiceProvider'` as a service provider. You must also add `'SteamAuth' => 'sgtaziz\SteamAuth\Facades\SteamAuth'` to aliases. 

# Documentation
Currently, the only real usable function is SteamAuth::Auth(). Example:
```php
<?php
	$user = SteamAuth::Auth();
	if ($user)
	{
		$name = $user['personaname'];
		$steamid64 = $user['steamid'];
		echo $name . ' has the steamid ' . $steamid64;
	}
```
SteamAuth::Auth() will automatically redirect the user to Steam's website to login. Once authenticated, it will return to the previous page and $user will be a valid variable if Steam was used successfully to authenticate the user.
SteamAuth::Auth() will also return an associative array with these values:

# Variables of $user
### Public Variables
1. `steamid` - 64bit SteamID of the user
2. `communityvisibilitystate` - 1 = Private | 2 = Friends Only | 3 = Public
3. `profilestate` - When set to one, means that the user has setup their Steam Community profile
4. `personaname` - The user's Steam alias
5. `lastlogoff` - Unix timestamp of when the user was last online
6. `commentpermission` - When available, it means that anyone can comment on the profile
7. `profileurl` - The URL of the user's profile
8. `avatar` - A small version of the user's avatar
9. `avatarmedium` - A medium version of the user's avatar
10. `avatarfull` - The highest quality version of the user's avatar
11. `personastate` - 0 = Offline | 1 = Online | 2 = Busy | 3 = Away | 4 = Snooze | 5 = Looking to Trade | 6 = Looking to Play

### Private Variables
1. `realname` - The user's "real name" set on their profile
2. `primaryclanid` - The user's primary group
3. `timecreated` - Unix timestamp of the account's creation date
4. `gameid` - If the user is in a game, the game ID of the game he is currently in
5. `gameserverip` - When possible, the IP of the server the user is currently in
6. `gameextrainfo` - The name of the game the user is currently playing

See [this][steamcommunitywiki] for more info.
[steamcommunitywiki]: https://developer.valvesoftware.com/wiki/Steam_Web_API#GetPlayerSummaries_.28v0002.29

# Note
This is not meant to be used as a way to authenticate users on your website, but simply a way to get their steam information. Once you do that, you may store it in your own database and use that plus SteamAuth to authenticate users.
