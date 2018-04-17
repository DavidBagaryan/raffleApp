<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 17.04.2018
 * Time: 17:13
 */

use app\models\DataBase;

require_once 'config.php';
require_once 'models/DataBase.php';

// select gifts
$moneyGifts = DataBase::getInstance()->prepare(
    'SELECT * FROM user_gift 
              WHERE `gift_type` = 1 AND `is_sent` = "N" AND `date_send` IS NULL 
              LIMIT ?');
$moneyGifts->execute([QUERY_LIMIT]);

// select user ids
$userIds = [];
$giftIds = [];

foreach ($moneyGifts->fetchAll() as $gift) {
    if (!in_array($gift['user_id'], $userIds)) $userIds[] = $gift['user_id'];
    if (!in_array($gift['user_id'], $giftIds)) $giftIds[] = $gift['id'];
}

// select users
$userIds = implode(',', $userIds);
$users = DataBase::getInstance()->prepare("SELECT * FROM `raffle_users` WHERE `id` IN ({$userIds})");
$users->execute();
$total = function ($id) {
    $moneyGift = DataBase::getInstance()->prepare(
        'SELECT SUM(`gift`) AS `gift`
                  FROM `user_gift` 
                  WHERE `gift_type` = 1 AND `is_sent` = "N" AND `date_send` IS NULL AND `user_id` = ?'
    );
    $moneyGift->execute([$id]);
    return $moneyGift->fetch();
};

// Update all gifts' statuses that was sent
$giftIds = implode(', ', $giftIds);
var_dump($giftIds);
$updateGifts = DataBase::getInstance()->prepare("UPDATE user_gift SET `is_sent` = 'Y', `date_send` = NOW() 
                                                    WHERE `id` IN ({$giftIds})");
$updateGifts->execute();

// xml generator
$date = (new DateTime());
$xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><users/>");
foreach ($users->fetchAll() as $user)
    if ($user['is_active'] === 'Y' and $user['address'] !== 'нет адреса') {
        $info = $xml->addChild('user');
        $info->addChild('name', $user['user_login']);
        $info->addChild('total', $total($user['id'])['gift']);
        $info->addChild('bank_account', $user['bank_account']);
    }


$xml->saveXML('cron-xmls/xml_bank1.xml');
echo "XML was sent at {$date->format('Y-m-d h:m')}";


