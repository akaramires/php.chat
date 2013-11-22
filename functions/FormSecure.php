<?php
/**
 * User: Abdurayimov Elmar
 * Date: 11/22/13
 * Time: 9:12 PM
 */

class FormSecure
{
	private $formKey;

	private $old_formKey;

	function __construct()
	{
		if (isset($_SESSION['form_key'])) {
			$this->old_formKey = $_SESSION['form_key'];
		}
	}

	private function generateKey()
	{
		$ip = $_SERVER['REMOTE_ADDR'];

		$uniqid = uniqid(mt_rand(), true);

		return md5($ip . $uniqid);
	}

	public function outputKey()
	{
		$this->formKey = $this->generateKey();
		$_SESSION['form_key'] = $this->formKey;

		return $this->formKey;
	}


	public function validate()
	{
		if ($_POST['form_key'] == $this->old_formKey) {
			return true;
		} else {
			return false;
		}
	}
}