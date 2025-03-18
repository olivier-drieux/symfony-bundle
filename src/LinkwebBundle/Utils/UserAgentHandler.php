<?php

namespace App\LinkwebBundle\Utils;

use App\LinkwebBundle\Constant\Device;
use App\LinkwebBundle\Constant\UserAgent;

/**
 * Class UserAgent.
 */
readonly class UserAgentHandler
{
    /**
     * Provides a random User-Agent header depending on given device.
     *
     * @param string|null $device default set to null
     */
    public static function random(?string $device = null): string
    {
        if (Device::DESKTOP === $device) {
            return UserAgent::DESKTOP[\array_rand(UserAgent::DESKTOP)];
        }

        if (Device::MOBILE === $device) {
            return UserAgent::MOBILE[\array_rand(UserAgent::MOBILE)];
        }

        $all = [...UserAgent::DESKTOP, ...UserAgent::MOBILE];

        /** @var string $selection */
        $selection = $all[\array_rand($all)];

        return $selection;
    }

    /**
     * Provides the best User-Agent header to minimize data on given device.
     */
    public static function minimizeData(string $device): string
    {
        return Device::DESKTOP === $device ? UserAgent::DESKTOP[0] : UserAgent::MOBILE[0];
    }
}
