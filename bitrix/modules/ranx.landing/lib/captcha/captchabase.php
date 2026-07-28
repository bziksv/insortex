<?

namespace Ranx\Landing\Captcha;

use \Bitrix\Main\Localization\Loc;


class CaptchaBase
{
    public static function getCode()
    {
        return strtolower((new \ReflectionClass(static::class))->getShortName());
    }

    public static function getTitle()
    {
        $code = strtoupper(static::getCode());
        return Loc::getMessage('RX_LANDING_LIB_CAPTCHA_' . $code . '_TITLE');
    }

    public static function getOptions()
    {
        return [];
    }

    protected static function showNode($data = [])
    {?>
        <? $data = array_merge($data, ['type' => static::getCode()]) ?>
        <div
            class="rx-captcha"
            <? foreach ($data as $key => $value): ?>
                data-captcha-<?= $key ?>="<?= $value ?>"
            <? endforeach; ?>
        >
        </div>
    <?}

    public static function showFormField()
    {
        static::showNode();
    }

    public static function verifyFormPost($formData)
    {
        return false;
    }

}
