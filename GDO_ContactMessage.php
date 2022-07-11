<?php
namespace GDO\Contact;

use GDO\Core\GDO;
use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_CreatedBy;
use GDO\Mail\GDT_Email;
use GDO\UI\GDT_Message;
use GDO\User\GDO_User;
use GDO\UI\GDT_Title;

final class GDO_ContactMessage extends GDO
{
	public function gdoCached() : bool { return false; }
	
	public function gdoColumns() : array
	{
		return [
			GDT_AutoInc::make('cmsg_id'),
			GDT_Email::make('cmsg_email')->label('email'),
			GDT_Title::make('cmsg_title')->notNull(),
			GDT_Message::make('cmsg_message')->min(2)->max(8192)->notNull(),
			GDT_CreatedAt::make('cmsg_created_at'),
			GDT_CreatedBy::make('cmsg_user_id')->cascadeNull(),
		];
	}
	
	/**
	 * @return GDO_User
	 */
	public function getUser() { return $this->getValue('cmsg_user_id'); }
	
	public function getEmail() { return $this->gdoVar('cmsg_email'); }
	public function getTitle() { return $this->gdoVar('cmsg_title'); }
	public function getMessage() { return $this->gdoColumn('cmsg_message')->renderCell(); }
	public function getCreatedAt() { return $this->gdoVar('cmsg_created_at'); }
	
	public function href_link_message() { return href('Contact', 'Message', '&id='.$this->getID()); }
}
