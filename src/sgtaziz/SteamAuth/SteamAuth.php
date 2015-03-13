<?php
namespace sgtaziz\SteamAuth;

require_once "Libraries/OpenID.php";
use LightOpenID;

class SteamAuth
{
	private $OpenIDURL = 'https://steamcommunity.com/openid';
	private $OpenID = null;

	public function __construct()
	{
		$this->OpenID = new LightOpenID($this->OpenIDURL);
	}

	public function Auth($returnUrl)
	{
		$this->OpenID->identity = $this->OpenIDURL;
		$this->OpenID->returnUrl = $returnUrl;

		/*location shit here*/
	}
}
