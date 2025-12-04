<?php

/**
 * Get a setting value
 * 
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function getSetting($key, $default = null)
{
    return \App\Models\Setting::getValue($key, $default);
}

/**
 * Get the application name
 * 
 * @return string
 */
function appName()
{
    return getSetting('app_name', config('app.name'));
}

/**
 * Get the application logo
 * 
 * @return string
 */
function appLogo()
{
    return getSetting('app_logo', '/images/logo.png');
}

/**
 * Get the support email
 * 
 * @return string
 */
function supportEmail()
{
    return getSetting('support_email', env('MAIL_FROM_ADDRESS'));
}
