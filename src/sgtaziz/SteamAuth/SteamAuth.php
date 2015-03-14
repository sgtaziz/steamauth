<?php
namespace sgtaziz\SteamAuth;

require_once "Libraries/OpenID.php";
use LightOpenID;
use Redirect;
use SimpleXMLElement;

class SteamAuth
{
	private $OpenIDURL		= 'https://steamcommunity.com/openid';
	private $OpenID			= null;

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
				$json = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . config('steamauth.SteamAPIKey') . '&steamids=' . $steamid64); 
				$json = json_decode($json, true);

				$user = $json["response"]["players"][0];

				if ($user)
				{
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
