<?php
/**
 * Created by PhpStorm.
 * User: DavidBagaryan
 * Date: 17.04.2018
 * Time: 17:13
 *
 * cron can be started by the console command
 * /path/to/php /path/to/project/cron/moneySender.php > /path/to/project/log/log.html
 */

use app\models\DataBase;

require_once('..' . DIRECTORY_SEPARATOR . 'config.php');
require_once('..' . DIRECTORY_SEPARATOR . 'models/DataBase.php');


// select gifts
$moneyGifts = DataBase::getInstance()->prepare(
    'SELECT *
              FROM user_gift AS u_g
              WHERE `gift_type` = 1 AND `is_sent` = "N" 
                AND (`date_send` = 0 OR `date_send` IS NULL) 
                AND `user_id` IN (
                  SELECT u.id 
                  FROM `raffle_users` AS u
                  WHERE (`bank_account` != "нет реквизитов для отправки" or `bank_account` != NULL) 
                    AND `is_active` = "Y"
                ) LIMIT ?');
$moneyGifts->execute([QUERY_LIMIT]);

$giftIds = [];
$userIds = [];
foreach ($moneyGifts->fetchAll() as $gift) {
    if (!in_array($gift['id'], $giftIds)) $giftIds[] = $gift['id'];
    if (!in_array($gift['user_id'], $userIds)) $userIds[] = $gift['user_id'];
}

if (count($giftIds) > 0) {

    // users
    $userIds = implode(', ', $userIds);
    $users = DataBase::getInstance()->prepare(
        "SELECT * 
                  FROM `raffle_users` AS u
                  WHERE `id` IN ({$userIds})");
    $users->execute();

    // xml generator
    $total = function ($id) {
        $moneyGift = DataBase::getInstance()->prepare(
            'SELECT SUM(`gift`) AS `gift`
                      FROM `user_gift` 
                      WHERE `gift_type` = 1 AND `is_sent` = "N" 
                        AND `date_send` = 0 AND `user_id` = ?'
        );
        $moneyGift->execute([$id]);
        return $moneyGift->fetch();
    };

    $date = (new DateTime())->format('Y-m-d_h-i');
    $xml = new SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\"?><users/>");
    foreach ($users->fetchAll() as $user) {
        $info = $xml->addChild('user');
        $info->addChild('name', $user['user_login']);
        $info->addChild('total', $total($user['id'])['gift']);
        $info->addChild('bank_account', $user['bank_account']);
    }

    $xml->asXML();

    // Update all gifts' statuses that was sent
    $giftIds = implode(', ', $giftIds);
    $updatedGifts = DataBase::getInstance()->prepare(
        "UPDATE user_gift 
                  SET `is_sent` = 'Y', `date_send` = NOW() 
                  WHERE `id` IN ({$giftIds}) AND `user_id` IN ({$userIds})");
    if ($updatedGifts->execute()) {
        $cronDir = '..' . DIRECTORY_SEPARATOR . 'cron-xml';
        if (!is_dir($cronDir)) mkdir($cronDir);
        $xml->saveXML($cronDir . DIRECTORY_SEPARATOR . $date . '_xml_bank.xml');

        // TODO send XML by curl or other thing

        echo "XML was sent at {$date}";
    } else echo 'update failure!';
} else echo "No gifts to send";
