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

/**
 * A message that got sent by the contact form.
 * 
 * @author gizmore
 * @version 7.0.1
 * @since 3.5.0
 */
final class GDO_ContactMessage extends GDO
{
	public function gdoCached() : bool { return false; }
	
	public function gdoColumns() : array
	{
		return [
			GDT_AutoInc::make('cmsg_id'),
			GDT_Email::make('cmsg_email')->label('email'),
			GDT_Title::make('cmsg_title')->notNull()->label('msg_title'),
			GDT_Message::make('cmsg_message')->min(2)->max(8192)->notNull(),
			GDT_CreatedAt::make('cmsg_created_at'),
			GDT_CreatedBy::make('cmsg_user_id')->cascadeNull(),
		];
	}
	
	public function getUser() : GDO_User { return $this->gdoValue('cmsg_user_id'); }
	
	public function getEmail() : ?string { return $this->gdoVar('cmsg_email'); }
	public function getTitle() : string { return $this->gdoVar('cmsg_title'); }
	public function getMessage() : string { return $this->gdoColumn('cmsg_message')->renderHTML(); }
	public function getCreatedAt() : string { return $this->gdoVar('cmsg_created_at'); }
	
	public function href_link_message() : string { return href('Contact', 'Message', '&id='.$this->getID()); }

}
