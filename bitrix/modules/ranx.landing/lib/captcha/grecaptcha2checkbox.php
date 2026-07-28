<?

namespace Ranx\Landing\Captcha;

use \Bitrix\Main\Localization\Loc;
use \Ranx\Landing\Config;

Loc::loadMessages(__FILE__);


class Grecaptcha2Checkbox extends GrecaptchaBase
{

    public static function getOptions()
    {
        return [
            'GRECAPTCHA2CHECKBOX_SITEKEY' => [
                'TITLE'   => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2CHECKBOX_OPTION_GRECAPTCHA2CHECKBOX_SITEKEY_TITLE'
                ),
                'TYPE'    => 'string',
                'DEFAULT' => '',
            ],
            'GRECAPTCHA2CHECKBOX_SECRETKEY' => [
                'TITLE'   => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2CHECKBOX_OPTION_GRECAPTCHA2CHECKBOX_SECRETKEY_TITLE'
                ),
                'TYPE'    => 'string',
                'DEFAULT' => '',
            ],
            'GRECAPTCHA2CHECKBOX_SIZE' => [
                'TITLE' => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2CHECKBOX_OPTION_GRECAPTCHA2CHECKBOX_SIZE_TITLE'
                ),
                'TYPE'  => 'select',
                'LIST'  => [
                    'normal' => ['TITLE' => Loc::getMessage(
                        'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2CHECKBOX_OPTION_GRECAPTCHA2CHECKBOX_SIZE_NORMAL'
                    )],
                    'compact' => ['TITLE' => Loc::getMessage(
                        'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2CHECKBOX_OPTION_GRECAPTCHA2CHECKBOX_SIZE_COMPACT'
                    )],
                ],
                'DEFAULT' => 'normal',
            ],
            'GRECAPTCHA2CHECKBOX_THEME' => [
                'TITLE' => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2CHECKBOX_OPTION_GRECAPTCHA2CHECKBOX_THEME_TITLE'
                ),
                'TYPE'  => 'select',
                'LIST'  => [
                    'light' => ['TITLE' => Loc::getMessage(
                        'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2CHECKBOX_OPTION_GRECAPTCHA2CHECKBOX_THEME_LIGHT'
                    )],
                    'dark' => ['TITLE' => Loc::getMessage(
                        'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2CHECKBOX_OPTION_GRECAPTCHA2CHECKBOX_THEME_DARK'
                    )],
                ],
                'DEFAULT' => 'light',
            ],
        ];
    }


    public static function showFormField()
    {
        $labelText = htmlentities(Loc::getMessage('RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2CHECKBOX_FIELD_LABEL'));
        $errorText = htmlentities(Loc::getMessage('RX_LANDING_LIB_CAPTCHA_GRECAPTCHA2CHECKBOX_FIELD_ERROR'));

        static::showNode([
            'sitekey'          => Config::get('GRECAPTCHA2CHECKBOX_SITEKEY'),
            'size'             => Config::get('GRECAPTCHA2CHECKBOX_SIZE'),
            'theme'            => Config::get('GRECAPTCHA2CHECKBOX_THEME'),
            'field-label-text' => $labelText,
            'field-error-text' => $errorText
        ]);
    }


    protected static function getSecretKey()
    {
        return Config::get('GRECAPTCHA2CHECKBOX_SECRETKEY');
    }

}
