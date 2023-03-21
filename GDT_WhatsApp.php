<?php
namespace GDO\Contact;

use GDO\Core\GDT_String;

/**
 * WhatsApp-Contact, freetext.
 *
 * @author gizmore
 */
final class GDT_WhatsApp extends GDT_String
{

	protected function __construct()
	{
		parent::__construct();
		$this->icon('whatsapp');
		$this->name = 'whatsapp';
		$this->labelKey = 'whatsapp';
		$this->ascii()->caseI();
		$this->min(3)->max(32);
	}

}
