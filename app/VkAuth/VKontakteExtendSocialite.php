<?php

namespace App\VkAuth;

use SocialiteProviders\Manager\SocialiteWasCalled;

class VKontakteExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite(
            'vkontakte', 'App\VkAuth\Provider'
        );
    }
}