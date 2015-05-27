<?php namespace Sgtaziz\SteamAuth;

use Config;
use Sgtaziz\SteamAuth\Libraries\LightOpenID;

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
                $url = 'http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?';
                $query = http_build_query([
                        'key'      => Config::get('sgtaziz.steamauth.SteamAPIKey'),
                        'steamids' => $steamid64,
                    ]);

                $json = file_get_contents($url . $query);
                $json = json_decode($json, true);

                $user = $json['response']['players'][0];
                $user = json_decode(json_encode($user));

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
    public function RedirectTo($url)
    {
        header("Location: $url");

        die();
    }
}
