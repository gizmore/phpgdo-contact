<?php
namespace GDO\Contact\Method;

use GDO\Admin\MethodAdmin;
use GDO\Contact\GDO_ContactMessage;
use GDO\Core\GDT;
use GDO\Core\GDT_Object;
use GDO\Core\Method;

/**
 * Display a contact message for admins.
 *
 * @author gizmore
 */
final class Message extends Method
{

	use MethodAdmin;

	private GDO_ContactMessage $message;

	public function getPermission(): ?string { return 'staff'; }

	public function gdoParameters(): array
	{
		return [
			GDT_Object::make('id')->table(GDO_ContactMessage::table())->notNull(),
		];
	}

	public function execute(): GDT
	{
		return $this->templateMessage($this->getMessage());
	}

	public function templateMessage(GDO_ContactMessage $message)
	{
		return $this->templatePHP('message_card.php', ['message' => $message]);
	}

	public function getMessage(): GDO_ContactMessage
	{
		if (!isset($this->message))
		{
			$this->message = $this->gdoParameterValue('id');
		}
		return $this->message;
	}

	public function getMethodTitle(): string
	{
		return t('card_title_contact_message');
	}

}
