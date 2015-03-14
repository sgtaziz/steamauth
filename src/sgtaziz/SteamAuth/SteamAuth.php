<?php
namespace sgtaziz\SteamAuth;

require_once "Libraries/OpenID.php";
use LightOpenID;
use Redirect;
use SimpleXMLElement;

class SteamAuth
{
	private $OpenIDURL = 'https://steamcommunity.com/openid';
	private $OpenID = null;

	public function __construct()
	{
		$this->OpenID = new LightOpenID(url(''));
	}

	public function Auth()
	{
		if (!$this->OpenID->mode)
		{
			$this->OpenID->identity = $this->OpenIDURL;
			$this->OpenID->returnUrl = url('').$_SERVER['REQUEST_URI'];

			$this->RedirectTo($this->OpenID->authUrl());
		}
		elseif ($this->OpenID->mode == 'cancel')
		{
			return false;
		}
		elseif ($this->OpenID->validate())
		{
			$steamid64 = str_replace('http://steamcommunity.com/openid/id/', '', $this->OpenID->identity);

			if ($steamid64)
			{
				$xmlraw = file_get_contents( "http://steamcommunity.com/profiles/".$steamid64."/?xml=1" ); 
				$xml = new SimpleXMLElement($xmlraw);

				if (is_object($xml))
				{
					$httpsURL = 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/';
					preg_match('/^(.*)?\/avatars\/(.*)$/i', (string)$xml->{'avatarMedium'}, $newAvatarLink);
					preg_match('/^(.*)?\/avatars\/(.*)$/i', (string)$xml->{'avatarFull'}, $newAvatarFullLink);
					preg_match('/^(.*)?\/avatars\/(.*)$/i', (string)$xml->{'avatarIcon'}, $newAvatarSmallLink);
					$avatar = $httpsURL.$newAvatarLink[2];
					$avatarFull = $httpsURL.$newAvatarFullLink[2];
					$avatarSmall = $httpsURL.$newAvatarSmallLink[2];

					$user = (object)array();
					$user->steamid64 = (string)$xml->{'steamID64'};
					$user->name = (string)$xml->{'steamID'};
					$user->state = (string)$xml->{'onlineState'};
					$user->avatar = $avatar;
					$user->avatarFull = $avatarFull;
					$user->avatarSmall = $avatarSmall;
					$user->private = (int)$xml->{'visibilityState'} != 3;
					$user->vacbans = (string)$xml->{'vacBanned'};
					$user->tradebans = (string)$xml->{'tradeBanState'};
					$user->limited = (int)$xml->{'isLimitedAccount'} == 1;
					$user->customURL = (string)$xml->{'customURL'};
					$user->ingame = isset($xml->{'ingameInfo'});

					if ($user->ingame)
					{
						$user->ingamename = (string)$xml->{'gameName'};
						$user->ingamelink = (string)$xml->{'gameLink'};
						$user->ingameicon = (string)$xml->{'gameIcon'};
						$user->ingamelogo = (string)$xml->{'gameLogo'};
						$user->ingamelogosmall = (string)$xml->{'gameLogoSmall'};
					}

					$user->created = (string)$xml->{'memberSince'};

					return $user;
				}
			}
		}

		return false;
	}

	public static function RedirectTo( $url )
	{
		header( "Location: $url" ) ;
		die();
	}
}
