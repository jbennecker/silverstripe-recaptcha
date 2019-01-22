<?php

namespace jbennecker\recaptcha\Forms;

use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\FormField;
use SilverStripe\View\Requirements;

class RecaptchaField extends FormField
{

    /**
     * @config
     */
    private static $public_api_key;

    /**
     * @config
     */
    private static $private_api_key;

    public function Field($properties = [])
    {
        Requirements::javascript('https://www.google.com/recaptcha/api.js');
        return "<div class='g-recaptcha' data-sitekey='" . $this->getConfig('public_api_key') . "'></div>";
    }

    private function getConfig(string $name)
    {
        return Config::inst()->get('jbennecker\recaptcha\Forms\RecaptchaField', $name);
    }

    public function validate($validator)
    {
        if (empty($_POST['g-recaptcha-response']) || !is_string($_POST['g-recaptcha-response'])) {
            $validator->validationError(
                $this->name,
                _t('RecaptchaField.EMPTY', "This field is required"),
                "validation",
                false
            );

            return false;
        }

        $isValid = $this->validateWithGoogle();
        if (!$isValid) {
            $validator->validationError(
                $this->name,
                _t('RecaptchaField.VALIDSOLUTION', "Looks like you are a Robot."),
                "validation",
                false
            );

            return false;
        }

        return true;
    }

    private function validateWithGoogle()
    {
        $captcha = $_POST['g-recaptcha-response'];
        $response = json_decode(
            file_get_contents(
                "https://www.google.com/recaptcha/api/siteverify?secret=" . $this->getConfig(
                    'private_api_key'
                ) . "&response=" . $captcha
            ),
            true
        );

        return $response['success'];
    }
}
