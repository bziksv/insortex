<?

namespace Ranx\Landing\Captcha;

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);


class BitrixCaptcha extends CaptchaBase
{

    public static function showFormField()
    {
        $labelText = htmlentities(Loc::getMessage('RX_LANDING_LIB_CAPTCHA_BITRIXCAPTCHA_FIELD_LABEL'));
        $errorText = htmlentities(Loc::getMessage('RX_LANDING_LIB_CAPTCHA_BITRIXCAPTCHA_FIELD_ERROR'));

        static::showNode([
            'field-label-text' => $labelText,
            'field-error-text' => $errorText
        ]);
    }


    public static function verifyFormPost($formData)
    {
        $captchaWord = $formData['captcha_word'] ?? NULL;
        $captchaSid  = $formData['captcha_sid']  ?? NULL;

        return $GLOBALS['APPLICATION']->CaptchaCheckCode($captchaWord, $captchaSid);
    }

}
