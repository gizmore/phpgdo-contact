<?php
namespace GDO\Contact\Method;

use GDO\Admin\MethodAdmin;
use GDO\Contact\GDO_ContactMessage;
use GDO\Core\GDO;
use GDO\DB\Query;
use GDO\Table\MethodQueryTable;
use GDO\UI\GDT_Button;

/**
 * List contact messages for staff members.
 * 
 * @author gizmore
 */
final class Messages extends MethodQueryTable
{
	use MethodAdmin;
	
	public function getPermission() : ?string { return 'staff'; }
	
	public function getMethodTitle() : string
	{
		return t('card_title_contact_message');
	}
	
	public function gdoTable() : GDO
	{
	    return GDO_ContactMessage::table();
	}
	
	public function getQuery() : Query
	{
	    return $this->gdoTable()->select();
	}
	
	public function gdoHeaders() : array
	{
		$gdo = $this->gdoTable();
		return [
			$gdo->gdoColumn('cmsg_id'),
			$gdo->gdoColumn('cmsg_created_at'),
			$gdo->gdoColumn('cmsg_user_id'),
			$gdo->gdoColumn('cmsg_email'),
			$gdo->gdoColumn('cmsg_title'),
			GDT_Button::make('link_message'),
		];
	}
	
}
