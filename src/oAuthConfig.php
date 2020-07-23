<?php

namespace MiniUpload;

class oAuthConfig implements \AmoCRM\OAuth\OAuthConfigInterface {

    public function getIntegrationId(): string
    {
        return "6ce6a4fe-6895-4b97-b87e-90e4946efee9";
    }

    public function getSecretKey(): string
    {
        return "lSE8bT1Cu2lCyER8q0lJ4tMytJxIzDvsZwcIPlcXiDaA5mF7sZ6tqbVouGmX5xkh";
    }

    public function getRedirectDomain(): string
    {
        return "https://2401a8fd2ba7.ngrok.io/";
    }
}