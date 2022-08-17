<?php
namespace GDO\Contact\Method;

use GDO\Admin\MethodAdmin;
use GDO\Contact\GDO_ContactMessage;
use GDO\Core\Method;
use GDO\Core\GDT_Object;

/**
 * Display a contact message for admins.
 * 
 * @author gizmore
 */
final class Message extends Method
{
	use MethodAdmin;
	
	public function getPermission() : ?string { return 'staff'; }
	
	private GDO_ContactMessage $message;
	
	public function gdoParameters() : array
	{
		return [
			GDT_Object::make('id')->table(GDO_ContactMessage::table())->notNull(),
		];
	}
	
	public function getMessage() : GDO_ContactMessage
	{
		if (!isset($this->message))
		{
			$this->message = $this->gdoParameterValue('id');
		}
		return $this->message;
	}
	
	public function execute()
	{
		return $this->templateMessage($this->getMessage());
	}
	
	public function templateMessage(GDO_ContactMessage $message)
	{
		return $this->templatePHP('message.php', ['message' => $message]);
	}
	
	public function getMethodTitle() : string
	{
		return t('card_title_contact_message');
	}
	
}
