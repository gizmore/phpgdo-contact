<?php
declare(strict_types=1);
namespace GDO\Contact;

use GDO\Core\GDT;
use GDO\Core\GDT_Method;
use GDO\Core\GDT_String;
use GDO\UI\GDT_Link;

/**
 * A public Telegram username, stored without the leading at-sign.
 */
final class GDT_TelegramUser extends GDT_String
{

	protected function __construct()
	{
		parent::__construct();
		$this->icon('telegram');
		$this->name = 'telegram';
		$this->labelKey = 'telegram';
		$this->ascii()->caseI();
		$this->min(5)->max(32);
		$this->pattern('/^[A-Za-z][A-Za-z0-9_]{4,31}$/D');
		$this->placeholder('@username');
	}

	/** Store the canonical username without its presentation prefix. */
	public function var(?string $var): static
	{
		return parent::var($this->normalizeTelegram($var));
	}

	public function inputToVar(array|int|string|null|GDT_Method $input): ?string
	{
		return $this->normalizeTelegram(parent::inputToVar($input));
	}

	private function normalizeTelegram(?string $username): ?string
	{
		return ($username !== null && str_starts_with($username, '@')) ? substr($username, 1) : $username;
	}

	public function renderHTML(): string
	{
		if (!$username = $this->getVar())
		{
			return GDT::none();
		}
		return GDT_Link::make()
			->href('https://t.me/' . rawurlencode($username))
			->textRaw('@' . $username)
			->targetBlank()
			->relation('noopener noreferrer external')
			->renderHTML();
	}

	public function renderCell(): string
	{
		return $this->renderHTML();
	}

}
