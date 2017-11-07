<?php
/**
*  LoginController class 
*/
class LoginController extends Zend_Controller_Action
{
    public $_loginLinkAndData = array();
	
	/**
	/* Init all controller for login
	*/
    public function init()
    {
        $module = strtolower($this->getRequest()->getParam('lang'));
        $controller = strtolower($this->getRequest()->getControllerName());
        $action = strtolower($this->getRequest()->getActionName());
        if (
            file_exists(
                APPLICATION_PATH . '/modules/' . $module . '/views/scripts/' . $controller . '/' . $action . ".phtml"
            )
        ) {
            $this->view->setScriptPath(APPLICATION_PATH . '/modules/' . $module . '/views/scripts');
        } else {
            $this->view->setScriptPath(APPLICATION_PATH . '/views/scripts');
        }
		# Get and set flas message
        $flashMessage = $this->_helper->getHelper('FlashMessenger');
        $message = $flashMessage->getMessages();
        $this->view->successMessage = isset($message[0]['success']) ?
        $message[0]['success'] : '';
        $this->view->errorMessage = isset($message[0]['error']) ?
        $message[0]['error'] : '';
        $this->viewHelperObject = new \FrontEnd_Helper_viewHelper();
    }

	/**
	/* Check user authentication when rerect on this websiste
	*/
    public function preDispatch()
    {
        $action = $this->getRequest()->getActionName();
        if (\Auth_VisitorAdapter::hasIdentity()
            && ($action == 'forgotpassword'
            || $action == 'resetpassword'
            || $action == 'index')
        ) {
            $this->_redirect(
                HTTP_PATH_LOCALE. \FrontEnd_Helper_viewHelper::__link('link_inschrijven'). '/' .
                \FrontEnd_Helper_viewHelper::__link('link_profiel')
            );
        }
    }

	/**
	/* Default action of this controller
	*/
    public function indexAction()
    {
        $emailAddressFromMemory = '';
        $emailAddressSpace = new \Zend_Session_Namespace('emailAddressSpace');
        if (isset($emailAddressSpace->emailAddressSpace)) {
            $emailAddressFromMemory = $emailAddressSpace->emailAddressSpace;
            $emailAddressSpace->emailAddressSpace = '';
        }
        $loginForm = new \Application_Form_Login();
        $this->view->form = $loginForm;
        $loginForm->getElement('emailAddress')->setValue($emailAddressFromMemory);
        $this->viewHelperObject->getMetaTags($this);
        if ($this->getRequest()->isPost()) {
            if ($loginForm->isValid($this->getRequest()->getPost())) {
                $visitorDetails = $loginForm->getValues();
                $this->_helper->Login->setVisitorSession($visitorDetails);
                self::redirectByVisitorStatus($visitorDetails);
            } else {
                $loginForm->highlightErrorElements();
            }
        }
        $this->view->headTitle(\FrontEnd_Helper_viewHelper::__form('form_Members Only'));
        $this->view->pageCssClass = 'login-page';
        # set reponse header X-Nocache used for varnish
        $this->getResponse()->setHeader('X-Nocache', 'no-cache');
    }

	/**
	 * Add flash message for application
	 * @param string message
	 * @param string redirectLink
	 * @param string errorType
	 */
    public function addFlashMessage($message, $redirectLink, $errorType)
    {
        $flashMessage = $this->_helper->getHelper('FlashMessenger');
        $flashMessage->addMessage(array($errorType => $message));
        $this->_redirect($redirectLink);
    }

	/**
	 * Remove cookies and logout from application
	 */
    public function logoutAction()
    {
        \Auth_VisitorAdapter::clearIdentity();
        setcookie('kc_unique_user_id', "", time() - (\Application_Service_Session_Timeout::getSessionTimeout()), '/');
        # set reponse header X-Nocache used for varnish
        $this->getResponse()->setHeader('X-Nocache', 'no-cache');
        \Zend_Session::namespaceUnset('favouriteShopId');
        $this->_redirect(HTTP_PATH_LOCALE);
    }

	/**
	 * Send Confirm Email verification
	 */
    public function confirmemailAction()
    {
        $this->getResponse()->setHeader('X-Nocache', 'no-cache');
        $visitorEmail = \FrontEnd_Helper_viewHelper::sanitize((base64_decode($this->_request->getParam("email"))));
        $visitor = \KC\Repository\Visitor::getVisitorDetailsByEmail($visitorEmail);
        if (!empty($visitor)) {
            if (\Visitor::updateVisitorStatus($visitor[0]['id'])) {
                $this->addFlashMessage(
                    \FrontEnd_Helper_viewHelper::__translate('Your email address has been confirmed please login'),
                    HTTP_PATH_LOCALE . \FrontEnd_Helper_viewHelper::__link('link_login'),
                    'success'
                );
            } else {
                $this->addFlashMessage(
                    \FrontEnd_Helper_viewHelper::__translate('Your email address is already confirmed'),
                    HTTP_PATH_LOCALE . \FrontEnd_Helper_viewHelper::__link('link_login'),
                    'error'
                );
            }
        } else {
            $this->addFlashMessage(
                \FrontEnd_Helper_viewHelper::__translate('Invalid confirmation link'),
                HTTP_PATH_LOCALE . \FrontEnd_Helper_viewHelper::__link('link_login'),
                'error'
            );
        }
    }
}
