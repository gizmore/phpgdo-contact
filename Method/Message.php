<?php
namespace GDO\Contact\Method;

use GDO\Admin\MethodAdmin;
use GDO\Contact\GDO_ContactMessage;
use GDO\Core\Method;
use GDO\Util\Common;

final class Message extends Method
{
	use MethodAdmin;
	
	public function getPermission() : ?string { return 'staff'; }
	
	private $message;
	
	public function onInit() : void
	{
		$this->message = GDO_ContactMessage::table()->find(Common::getRequestString('id'));
	}
	
	public function execute()
	{
		return $this->templateMessage($this->message);
	}
	
	public function templateMessage(GDO_ContactMessage $message)
	{
		return $this->templatePHP('message.php', ['message' => $message]);
	}
	
}
