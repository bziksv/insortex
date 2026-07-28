<?

namespace Ranx\Landing\Captcha;

use \Bitrix\Main\Localization\Loc;
use Ranx\Landing\Config;


class CaptchaManager
{
    protected const CAPTCHA_CLASSES = [
        '\Ranx\Landing\Captcha\BitrixCaptcha',
        '\Ranx\Landing\Captcha\Grecaptcha2Checkbox',
        '\Ranx\Landing\Captcha\Grecaptcha2Invisible',
        '\Ranx\Landing\Captcha\Grecaptcha3',
    ];

    protected static $captchaClasses;
    protected static $currentCaptchaClass;


    public static function getCaptchaClasses()
    {
        if(!isset(static::$captchaClasses))
        {
            static::$captchaClasses = [];
            foreach (static::CAPTCHA_CLASSES as $captchaClass)
                static::$captchaClasses[$captchaClass::getCode()] = $captchaClass;
        }

        return static::$captchaClasses;
    }


    public static function getCaptchaClassByCode($code)
    {
        return (static::getCaptchaClasses()[$code]) ?? NULL;
    }


    public static function getCurrentCaptchaClass()
    {
        if(!isset(static::$currentCaptchaClass))
        {
            $code = Config::get('CAPTCHA_TYPE');
            static::$currentCaptchaClass = static::getCaptchaClassByCode($code);
        }

        return static::$currentCaptchaClass;
    }


    public static function getOptions()
    {
        $typesList       = [];
        $specificOptions = [];

        foreach (static::getCaptchaClasses() as $code => $captchaClass)
        {
            $typesList[$code] = ['TITLE' => $captchaClass::getTitle()];

            foreach ($captchaClass::getOptions() as $key => $option)
            {
                $option['SHOW_IF'] = [
                    'USE_CAPTCHA'  => true,
                    'CAPTCHA_TYPE' => $code,
                ];

                $specificOptions[$key] = $option;
            }
        }

        $commonOptions = [
            'USE_CAPTCHA' => [
                'TITLE'   => Loc::getMessage('RX_LANDING_LIB_CAPTCHA_CAPTCHAMANAGER_OPTION_USE_CAPTCHA_TITLE'),
                'TYPE'    => 'checkbox',
                'DEFAULT' => false,
            ],
            'CAPTCHA_TYPE' => [
                'TITLE'   => Loc::getMessage('RX_LANDING_LIB_CAPTCHA_CAPTCHAMANAGER_OPTION_CAPTCHA_TYPE_TITLE'),
                'TYPE'    => 'select',
                'DEFAULT' => array_keys($typesList)[0],
                'LIST'    => $typesList,
                'DOC'     => 'https://help.landing-demo.ru/articles/209-249-520--kak-podklyuchit-google-recaptcha/',
                'SHOW_IF' => [
                    'USE_CAPTCHA' => true,
                ]
            ],
        ];

        return $commonOptions + $specificOptions;
    }


    public static function isCaptchaEnabled()
    {
        return Config::get('USE_CAPTCHA');
    }

}
