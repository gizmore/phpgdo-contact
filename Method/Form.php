<?php
namespace GDO\Contact\Method;

use GDO\Captcha\GDT_Captcha;
use GDO\Contact\GDO_ContactMessage;
use GDO\Contact\Module_Contact;
use GDO\Form\GDT_AntiCSRF;
use GDO\Form\GDT_Form;
use GDO\Form\GDT_Submit;
use GDO\Form\MethodForm;
use GDO\Mail\Mail;
use GDO\User\GDO_User;
use GDO\User\GDT_ProfileLink;
use GDO\UI\GDT_Link;
use GDO\Util\Arrays;

/**
 * Contact form. Sends mail to staff or single recipient in module config.
 * 
 * @author gizmore
 * @version 7.0.1
 * @since 3.2.0
 */
final class Form extends MethodForm
{
	public function isUserRequired() : bool { return false; }
	
	public function contactFields()
	{
		return ['cmsg_email', 'cmsg_title', 'cmsg_message'];
	}
	
	private function getInfoText(): string
	{
		$c = Module_Contact::instance();
		$names = [];
		foreach (GDO_User::admins() as $admin)
		{
			$names[] = GDT_ProfileLink::make()->level()->user($admin)->nickname()->avatar()->avatarUser($admin)->avatarSize(28)->renderHTML();
		}
		$names = Arrays::implodeHuman($names);
		$email = $c->cfgEmail();
		$subject = t('mail_subj_contact', [sitename()]);
		$email = GDT_Link::make()->href('mailto:'.$email.'?subject='.urlencode($subject))->textRaw($email)->renderHTML();
		
		$text = t('contact_info', [$names, $email]);
		
		if ($c->cfgWhatsAppContact())
		{
			$text .= "<br/>\n";
			$text .= $c->getConfigColumn('whatsapp_contact')->render();
		}
		
		return $text;
	}
	
	public function createForm(GDT_Form $form) : void
	{
	    $form->textRaw($this->getInfoText());
		$form->addFields(...GDO_ContactMessage::table()->getGDOColumns($this->contactFields()));
		$form->getField('cmsg_email')->initial(GDO_User::current()->getMail());
		$form->getField('cmsg_title')->label('title');
		if (Module_Contact::instance()->cfgCaptchaEnabled())
		{
			$form->addField(GDT_Captcha::make());
		}
		$form->addField(GDT_AntiCSRF::make());
		$form->actions()->addField(GDT_Submit::make()->label('btn_send'));
	}
	
	public function formValidated(GDT_Form $form)
	{
		$message = GDO_ContactMessage::blank($form->getFormVars())->insert();
		$this->sendMails($message);
		$this->resetForm(true);
		return $this->message('msg_contact_mail_sent', [sitename()])->addField($this->renderPage());
	}
	
	############
	### Mail ###
	############
	public function sendMails(GDO_ContactMessage $message)
	{
		if ($to = Module_Contact::instance()->cfgEmailReceiver())
		{
			$this->sendSingleMail($to, $message);
		}
		else
		{
			foreach (GDO_User::withPermission('staff') as $user)
			{
				$this->sendMail($user, $message);
			}
		}
	}
	
	private function sendSingleMail($to, GDO_ContactMessage $message)
	{
		$user = GDO_User::blank([
			'user_name' => tiso(GDO_LANGUAGE, 'contact_mail_receiver_name', [sitename()]),
			'user_language' => GDO_LANGUAGE,
			'user_email' => $to,
		]);
		return $this->sendMail($user, $message);
	}
	
	private function sendMail(GDO_User $user, GDO_ContactMessage $message)
	{
		$module = Module_Contact::instance();

		$sitename = sitename();
		$staffname = $user->renderUserName();
		$email = html($message->getEmail());
		$username = $message->getUser()->renderUserName();
		$title = html($message->getTitle());
		$text = html($message->getMessage());

		$mail = new Mail();
		$mail->setSender($module->cfgEmailSender());
		$mail->setSenderName(tusr($user, 'contact_mail_sender_name', [$sitename]));
		$mail->setSubject(tusr($user, 'mail_subj_contact', [$sitename]));
		$mail->setReply($message->getEmail());
		$args = [$staffname, $sitename, $username, $email, $title, $text];
		$mail->setBody(tusr($user, 'mail_body_contact', $args));
		$mail->sendToUser($user);
	}

}
