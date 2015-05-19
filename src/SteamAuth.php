<?php namespace sgtaziz\SteamAuth;

use Config;
use sgtaziz\SteamAuth\Libraries\LightOpenID;

class SteamAuth
{

    /**
     * Steam's OpenID URL
     *
     * @var string
     */
    private $OpenIDURL = 'https://steamcommunity.com/openid';

    /**
     * Instance of LightOpenID
     *
     * @var null
     */
    private $OpenID = null;

    /**
     * Instantiate the Object
     */
    public function __construct()
    {
        $this->OpenID = new LightOpenID(url(''));
    }

    /**
     * Check for Steam Authorization
     */
    public function Auth()
    {
        if (!$this->OpenID->mode) {
            $this->OpenID->identity = $this->OpenIDURL;
            $this->OpenID->returnUrl = url('') . $_SERVER['REQUEST_URI'];

            $this->RedirectTo($this->OpenID->authUrl());
        } elseif ($this->OpenID->mode == 'cancel') {
            return false;
        } elseif ($this->OpenID->validate()) {
            $steamid64 = str_replace('http://steamcommunity.com/openid/id/', '', $this->OpenID->identity);

            if ($steamid64) {
                $json = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=' . Config::get('sgtaziz.steamauth.SteamAPIKey') . '&steamids=' . $steamid64);
                $json = json_decode($json, true);

                $user = $json["response"]["players"][0];

                return $user;
            }
        }

        return false;
    }

    /**
     * Redirect to the given URL
     *
     * @param string $url
     */
    public static function RedirectTo($url)
    {
        header("Location: $url");

        die();
    }
}
