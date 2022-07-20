<?php
namespace GDO\Contact\tpl;
use GDO\Contact\GDO_ContactMessage;
use GDO\UI\GDT_Back;
use GDO\UI\GDT_Card;
/** @var $message GDO_ContactMessage **/

$user = $message->getUser();
$username = $user->renderUserName();
$username = html("$username <{$message->getEmail()}>");

$card = GDT_Card::make()->gdo($message);
$title = $message->gdoColumn('cmsg_title')->getVar();
$card->title('cntctmsg_title', [html($title)]);
$card->creatorHeader();

$card->content($message->gdoColumn('cmsg_message'));

$card->actions()->addField(GDT_Back::make());

echo $card->render();
