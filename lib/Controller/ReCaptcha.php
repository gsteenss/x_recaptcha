<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 8/12/13
 * Time: 4:43 PM
 * To change this template use File | Settings | File Templates.
 */
namespace rvadym\x_recaptcha;
require_once __DIR__.'/../../vendor/recaptchalib.php';
class Controller_ReCaptcha extends \Controller {

    // Get a key from https://www.google.com/recaptcha/admin/create
    public $publickey  = "6LdsAOYSAAAAAEryYty5wIf_4-A6Pe9KHDcdc64q";
    public $privatekey = "6LdsAOYSAAAAAOnZgA1mTaus7cUxfu5sWhUFYtdc";


    // the response from reCAPTCHA
    public $resp = null;
    // the error code from reCAPTCHA, if any
    public $error = null;

    public $isCaptchaOk = false;

    // settings
    public $view_name = 'recaptcha';

    function init() {
        parent::init();

        if (
            !(get_class($this->owner)=='Form' || is_subclass_of($this->owner,'Form'))
        ) {
            throw $this->exception('ReCaptcha can be connected to Form only. You tried to connect to '.get_class($this->owner));
        }

        if ($this->api->getConfig('reCAPTCHA/publickey',false)) {
            $this->publickey = $this->api->getConfig('reCAPTCHA/publickey');
        }
        if ($this->api->getConfig('reCAPTCHA/privatekey',false)) {
            $this->privatekey = $this->api->getConfig('reCAPTCHA/privatekey');
        }


		// add add-on locations to pathfinder
//		$l = $this->api->locate('addons',__NAMESPACE__,'location');
//		$addon_location = $this->api->locate('addons',__NAMESPACE__);
//		$this->api->pathfinder->addLocation($addon_location,array(
//			//'js'=>'templates/js',
//			//'css'=>'templates/css',
//            'template'=>'templates',
//		))->setParent($l);


        $this->owner->recaptcha = $this;
        $this->checkCaptcha();
        $this->addCaptcha();
    }
    private function checkCaptcha() {
        # was there a reCAPTCHA response?
        if (isset($_POST["recaptcha_response_field"])) {
            $resp = recaptcha_check_answer ($this->privatekey,
                                            $_SERVER["REMOTE_ADDR"],
                                            $_POST["recaptcha_challenge_field"],
                                            $_POST["recaptcha_response_field"]);
            if ($resp->is_valid) {
                $this->isCaptchaOk = true;
            } else {
                # set the error code so that we can display it
                $this->error = $resp->error;
                $this->isCaptchaOk = false;
            }
        }
    }
    private function addCaptcha() {
        if (!$this->isCaptchaOk) {
            $wrapper = $this->owner->add('View')->addClass('atk-form-row atk-cells atk-form-row-line ');
            $wrapper->add('View')->addClass('atk-cell atk-form-label atk-text-nowrap')->setHTML('&nbsp;');
            $wrapper->add('View',$this->view_name/*,null,array('view/recaptcha_field')*/)->setHTML(
                recaptcha_get_html($this->publickey, $this->error)
            )->addClass('atk-cell atk-form-field atk-jackscrew ');
        }
    }
}