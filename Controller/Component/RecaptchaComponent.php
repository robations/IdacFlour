<?php

App::import('Vendor', 'recaptchalib');

/**
 * Allows check for recaptcha. Requires setting 'private_key'.
 *
 * @author Rob-C
 */
class RecaptchaComponent extends Component
{
    /**
     * Checks POST data and private key with remote reCAPTCHA server for 
     * validity.
     * 
     * @return boolean
     */
    public function isRecaptchaValid()
    {
        if (!isset($_POST['recaptcha_challenge_field']) 
            || !isset($_POST['recaptcha_response_field']))
        {
            return false;
        }
        $resp = recaptcha_check_answer(
                $this->settings['private_key'], 
                $_SERVER["REMOTE_ADDR"], 
                $_POST["recaptcha_challenge_field"], 
                $_POST["recaptcha_response_field"]);
        
        return $resp->is_valid;
    }
}
