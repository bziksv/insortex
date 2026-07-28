<?

namespace Ranx\Landing\Captcha;


use Bitrix\Main\SystemException;
use Bitrix\Main\Web\HttpClient;
use Bitrix\Main\Web\Json;


class GrecaptchaBase extends CaptchaBase
{
    protected const API_VERIFICATION_URL = 'https://www.google.com/recaptcha/api/siteverify';


    protected static function getSecretKey()
    {
        return '';
    }


    protected static function getChallengeResult($verificationKey)
    {
        try
        {
            $client     = new HttpClient();
            $parameters = [
                'secret'   => static::getSecretKey(),
                'response' => $verificationKey,
            ];

            $response = $client->post(static::API_VERIFICATION_URL, $parameters);
            $response = Json::decode($response);

            if(!is_array($response))
                $response = [];

            if(!isset($response['success']))
                $response['success'] = false;

            return $response;
        }catch (SystemException $exception)
        {

        }

        return ['success' => false];
    }


    protected static function verifyChallengeResult($result)
    {
        return !empty($result['success']);
    }


    public static function verifyFormPost($formData)
    {
        $verificationKey = $formData['grecaptcha_verification_key'] ?? NULL;
        if(!$verificationKey)
            return false;

        return static::verifyChallengeResult(
            static::getChallengeResult($verificationKey)
        );
    }

}
