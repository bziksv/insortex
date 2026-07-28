<?

namespace Ranx\Landing\Captcha;

use \Bitrix\Main\Localization\Loc;
use \Ranx\Landing\Config;

Loc::loadMessages(__FILE__);


class Grecaptcha2Invisible extends GrecaptchaBase
{

    public static function getOptions()
    {
        return [
            'GRECAPTCHA2INVISIBLE_SITEKEY' => [
                'TITLE'   => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2INVISIBLE_OPTION_GRECAPTCHA2INVISIBLE_SITEKEY_TITLE'
                ),
                'TYPE'    => 'string',
                'DEFAULT' => '',
            ],
            'GRECAPTCHA2INVISIBLE_SECRETKEY' => [
                'TITLE'   => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2INVISIBLE_OPTION_GRECAPTCHA2INVISIBLE_SECRETKEY_TITLE'
                ),
                'TYPE'    => 'string',
                'DEFAULT' => '',
            ],
            'GRECAPTCHA2INVISIBLE_THEME' => [
                'TITLE' => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2INVISIBLE_OPTION_GRECAPTCHA2INVISIBLE_THEME_TITLE'
                ),
                'TYPE'  => 'select',
                'LIST'  => [
                    'light' => ['TITLE' => Loc::getMessage(
                        'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2INVISIBLE_OPTION_GRECAPTCHA2INVISIBLE_THEME_LIGHT'
                    )],
                    'dark' => ['TITLE' => Loc::getMessage(
                        'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2INVISIBLE_OPTION_GRECAPTCHA2INVISIBLE_THEME_DARK'
                    )],
                ],
                'DEFAULT' => 'light',
            ],
            'GRECAPTCHA2INVISIBLE_BADGE' => [
                'TITLE' => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2INVISIBLE_OPTION_GRECAPTCHA2INVISIBLE_BADGE_TITLE'
                ),
                'TYPE'  => 'select',
                'LIST'  => [
                    'inline' => ['TITLE' => Loc::getMessage(
                        'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2INVISIBLE_OPTION_GRECAPTCHA2INVISIBLE_BADGE_INLINE'
                    )],
                    'bottomright' => ['TITLE' => Loc::getMessage(
                        'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2INVISIBLE_OPTION_GRECAPTCHA2INVISIBLE_BADGE_BOTTOMRIGHT'
                    )],
                    'bottomleft' => ['TITLE' => Loc::getMessage(
                        'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2INVISIBLE_OPTION_GRECAPTCHA2INVISIBLE_BADGE_BOTTOMLEFT'
                    )],
                ],
                'DEFAULT' => 'inline',
            ],
        ];
    }


    public static function showFormField()
    {
        $errorText = htmlentities(Loc::getMessage('RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2INVISIBLE_FIELD_ERROR'));

        static::showNode([
            'sitekey'          => Config::get('GRECAPTCHA2INVISIBLE_SITEKEY'),
            'badge'            => Config::get('GRECAPTCHA2INVISIBLE_BADGE'),
            'theme'            => Config::get('GRECAPTCHA2INVISIBLE_THEME'),
            'field-error-text' => $errorText
        ]);
    }


    protected static function getSecretKey()
    {
        return Config::get('GRECAPTCHA2INVISIBLE_SECRETKEY');
    }

}
