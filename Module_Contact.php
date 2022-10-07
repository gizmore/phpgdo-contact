<?php
namespace GDO\Contact;

use GDO\Core\GDO_Module;
use GDO\Mail\GDT_Email;
use GDO\Core\GDT_Checkbox;
use GDO\UI\GDT_Link;
use GDO\User\GDO_User;
use GDO\UI\GDT_PageBar;
use GDO\UI\GDT_Divider;
use GDO\Core\GDT;

/**
 * Contact Module.
 * Provides contact to admins, and
 * Write users a mail without spoiling their email.
 * 
 * @author gizmore
 * @version 7.0.1
 * @since 4.0.1
 */
final class Module_Contact extends GDO_Module
{
	##############
	### Module ###
	##############
	public function onLoadLanguage() : void { $this->loadLanguage('lang/contact'); }
	public function getClasses() : array { return ['GDO\Contact\GDO_ContactMessage']; }
	public function href_administrate_module() : ?string { return href('Contact', 'Messages'); }
	public function getDependencies() : array { return ['Mail']; }
	public function getFriendencies() : array { return ['Captcha']; }
	public function getConfig() : array
	{
		return [
			GDT_Checkbox::make('contact_captcha')->initial('1'),
			GDT_Checkbox::make('member_captcha')->initial('1'),
			GDT_Email::make('contact_mail')->initial(GDO_ADMIN_EMAIL)->required(),
			GDT_Email::make('contact_mail_sender')->initial(GDO_BOT_EMAIL)->notNull(),
			GDT_Email::make('contact_mail_receiver'),
		    GDT_PageBar::make('hook_bar')->initial('bottom'),
			GDT_Divider::make('div_whatsapp_settings'),
			GDT_WhatsApp::make('whatsapp_contact'),
			GDT_Checkbox::make('whatsapp_settings')->initial('1'),
		];
	}
	
	public function getUserSettings(): array
	{
		if ($this->cfgWhatsAppSettings())
		{
			return [
				GDT_WhatsApp::make('whatsapp_number'),
			];
		}
		return GDT::EMPTY_ARRAY;
	}

	##############
	### Config ###
	##############
	public function cfgCaptchaGuest(): bool { return $this->getConfigValue('contact_captcha', '1') && module_enabled('Captcha'); }
	public function cfgCaptchaMember(): bool { return $this->getConfigValue('member_captcha', '0') && module_enabled('Captcha'); }
	public function cfgCaptchaEnabled(): bool { return GDO_User::current()->isMember() ? $this->cfgCaptchaMember() : $this->cfgCaptchaGuest(); }
	public function cfgEmail(): string { return $this->getConfigVar('contact_mail'); }
	public function cfgEmailSender(): string { return $this->getConfigVar('contact_mail_sender'); }
	public function cfgEmailReceiver(): string { return $this->getConfigVar('contact_mail_receiver'); }
	public function cfgWhatsAppContact(): string { return $this->getConfigVar('whatsapp_contact'); }
	public function cfgWhatsAppSettings(): bool { return $this->getConfigValue('whatsapp_settings'); }
	
	##############
	### Navbar ###
	##############
	public function onInitSidebar() : void
	{
		$bar = $this->getConfigValue('hook_bar');
	    $bar->addField(GDT_Link::make('link_contact')
	    	->href($this->href('Form'))
	    	->icon('message'));
	}
	
}
