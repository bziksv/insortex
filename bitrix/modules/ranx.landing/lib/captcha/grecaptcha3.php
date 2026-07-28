<?

namespace Ranx\Landing\Captcha;

use \Bitrix\Main\Localization\Loc;
use \Ranx\Landing\Config;

Loc::loadMessages(__FILE__);


class Grecaptcha3 extends GrecaptchaBase
{

    public static function getOptions()
    {
        return [
            'GRECAPTCHA3_SITEKEY' => [
                'TITLE'   => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA3_OPTION_GRECAPTCHA3_SITEKEY_TITLE'
                ),
                'TYPE'    => 'string',
                'DEFAULT' => '',
            ],
            'GRECAPTCHA3_SECRETKEY' => [
                'TITLE'   => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA3_OPTION_GRECAPTCHA3_SECRETKEY_TITLE'
                ),
                'TYPE'    => 'string',
                'DEFAULT' => '',
            ],
            'GRECAPTCHA3_ACTION' => [
                'TITLE'   => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA3_OPTION_GRECAPTCHA3_ACTION_TITLE'
                ),
                'TYPE'    => 'string',
                'DEFAULT' => 'rxlandingform',
            ],
            'GRECAPTCHA3_MIN_SCORE' => [
                'TITLE'   => Loc::getMessage(
                    'RX_LANDING_LIB_CAPTCHA_GRECAPTCHA3_OPTION_GRECAPTCHA3_MIN_SCORE_TITLE'
                ),
                'TYPE'  => 'string',
                'DEFAULT' => '0.5',
            ],
        ];
    }


    public static function showFormField()
    {
        $errorText = htmlentities(Loc::getMessage('RX_LANDING_LIB_CAPTCHA_GRECAPTCHA3_FIELD_ERROR'));

        static::showNode([
            'sitekey'          => Config::get('GRECAPTCHA3_SITEKEY'),
            'action'           => Config::get('GRECAPTCHA3_ACTION'),
            'field-error-text' => $errorText
        ]);
    }


    protected static function getSecretKey()
    {
        return Config::get('GRECAPTCHA3_SECRETKEY');
    }


    protected static function verifyChallengeResult($result)
    {
        $minScore       = floatval(Config::get('GRECAPTCHA3_MIN_SCORE'));
        $challengeScore = floatval($result['score'] ?? 0.0);

        return parent::verifyChallengeResult($result) && ($challengeScore >= $minScore);
    }

}
