<?php

App::import('Component', 'Email');
App::import('Vendor', 'Emogrifier', array('file' => 'com.pelagodesign' . DS . 'emogrifier.php'));

/**
 * EmogrifiedEmailComponent
 *
 * This component extends the default CakePHP EmailComponent to incorporate the
 * Emogrifier class which applies inline styles to html based on a supplied CSS
 * stylesheet
 *
 * @link http://www.pelagodesign.com/sidecar/emogrifier/
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller.components
 *
 */
class EmogrifiedEmailComponent extends EmailComponent
{
    public $cssStylesheet = null;
    
    /**
     * Send an email using the specified content, template and layout
     *
     * @param mixed $content Either an array of text lines, or a string with contents
     * @param string $template Template to use when sending email
     * @param string $layout Layout to use to enclose email body
     * @return boolean Success
     * @access public
     */
	function send($content = null, $template = null, $layout = null) {

        $deliverAllTo = Configure::read('Email.deliverAllTo');
        if ($deliverAllTo) {
            $this->to = $deliverAllTo;
            $this->cc = array();
            $this->bcc = array();
        }

		$this->__createHeader();

		if ($template) {
			$this->template = $template;
		}

		if ($layout) {
			$this->layout = $layout;
		}

		if (is_array($content)) {
			$content = implode("\n", $content) . "\n";
		}

		$message = $this->__wrap($content);
		if ($this->template === null) {
			$message = $this->__formatMessage($message);
		} else {
			$message = $this->__renderTemplate($message);
		}
		$message[] = '';
		$this->__message = $message;

		if (!empty($this->attachments)) {
			$this->__attachFiles();
		}

		if (!is_null($this->__boundary)) {
			$this->__message[] = '';
			$this->__message[] = '--' . $this->__boundary . '--';
			$this->__message[] = '';
		}

		if ($this->_debug) {
			return $this->__debug();
		}
		$__method = '__' . $this->delivery;
		$sent = $this->$__method();

		$this->__header = array();
		$this->__message = array();

		return $sent;
	}
	
    /**
     * Render the contents using the current layout and template.
     *
     * @param string $content Content to render
     * @return array Email ready to be sent
     * @access private
     */
	public function __renderTemplate($content) {
		$viewClass = $this->Controller->view;

		if ($viewClass != 'View') {
			if (strpos($viewClass, '.') !== false) {
				list($plugin, $viewClass) = explode('.', $viewClass);
			}
			$viewClass = $viewClass . 'View';
			App::import('View', $this->Controller->view);
		}
		$View = new $viewClass($this->Controller, false);
		$View->layout = $this->layout;
		$msg = array();

		$content = implode("\n", $content);

		if ($this->sendAs === 'both')
		{
			$htmlContent = $content;
			if (!empty($this->attachments))
			{
				$msg[] = '--' . $this->__boundary;
				$msg[] = 'Content-Type: multipart/alternative; boundary="alt-' . $this->__boundary . '"';
				$msg[] = '';
			}
			$msg[] = '--alt-' . $this->__boundary;
			$msg[] = 'Content-Type: text/plain; charset=' . $this->charset;
			$msg[] = 'Content-Transfer-Encoding: 7bit';
			$msg[] = '';

			$content = $View->element('email' . DS . 'text' . DS . $this->template, array('content' => $content), true);
			$View->layoutPath = 'email' . DS . 'text';
			$content = explode("\n", str_replace(array("\r\n", "\r"), "\n", $View->renderLayout($content)));
			$msg = array_merge($msg, $content);

			$msg[] = '';
			$msg[] = '--alt-' . $this->__boundary;
			$msg[] = 'Content-Type: text/html; charset=' . $this->charset;
			$msg[] = 'Content-Transfer-Encoding: 7bit';
			$msg[] = '';

			$htmlContent = $View->element('email' . DS . 'html' . DS . $this->template, array('content' => $htmlContent), true);
			$View->layoutPath = 'email' . DS . 'html';
			$htmlContent = explode("\n", str_replace(array("\r\n", "\r"), "\n", $View->renderLayout($htmlContent)));
			if ($this->cssStylesheet !== null)
			{
			    $css = $View->element('email' . DS . 'css' . DS . $this->cssStylesheet);
			    $emo = new Emogrifier($htmlContent, $css);
			    $htmlContent = $emo->emogrify();
			}
			$msg = array_merge($msg, $htmlContent);
			$msg[] = '';
			$msg[] = '--alt-' . $this->__boundary . '--';
			$msg[] = '';

			return $msg;
		}

		if (!empty($this->attachments)) {
			if ($this->sendAs === 'html') {
				$msg[] = '';
				$msg[] = '--' . $this->__boundary;
				$msg[] = 'Content-Type: text/html; charset=' . $this->charset;
				$msg[] = 'Content-Transfer-Encoding: 7bit';
				$msg[] = '';
			} else {
				$msg[] = '--' . $this->__boundary;
				$msg[] = 'Content-Type: text/plain; charset=' . $this->charset;
				$msg[] = 'Content-Transfer-Encoding: 7bit';
				$msg[] = '';
			}
		}

		$content = $View->element('email' . DS . $this->sendAs . DS . $this->template, array('content' => $content), true);
		$View->layoutPath = 'email' . DS . $this->sendAs;
		
        if ($this->sendAs === 'html' && $this->cssStylesheet !== null)
        {
            $css = $View->element('email' . DS . 'css' . DS . $this->cssStylesheet);
            $emo = new Emogrifier($content, $css);
            $content = $emo->emogrify();
        }
        else
        {
            $content = $View->renderLayout($content);
        }
		$content = explode("\n", str_replace(array("\r\n", "\r"), "\n", $content));
		$msg = array_merge($msg, $content);

		return $msg;
	}
	
}
?>