<?php


namespace orders\helpers;

use yii\data\ActiveDataProvider;
use yii;

/**
 * Helper for download csv file in module orders
 */
class CsvHelper
{
    /**
     * @param $dataProvider ActiveDataProvider
     */
    public static function sendCsvFromBuffer(ActiveDataProvider $dataProvider)
    {
        $fileName = "Orders " . date('Y-m-d_His');
        $stream = fopen('php://output', 'a');
        header('Content-Disposition: attachment;filename="' . $fileName . '.csv"');
        ob_start();
        fputcsv(
            $stream,
            [
                Yii::t('orders/main', 'table.id'),
                Yii::t('orders/main', 'table.user'),
                Yii::t('orders/main', 'table.link'),
                Yii::t('orders/main', 'table.quantity'),
                Yii::t('orders/main', 'table.service'),
                Yii::t('orders/main', 'table.status'),
                Yii::t('orders/main', 'table.mode'),
                Yii::t('orders/main', 'table.created'),
            ]
        );
        ob_flush();
        flush();
        foreach ($dataProvider->query->orderBy("id DESC")->batch(100) as $orders) {
            foreach ($orders as $key => $order) {
                fputcsv(
                    $stream,
                    [
                        $order->id,
                        $order->username,
                        $order->link,
                        $order->quantity,
                        $order->service_id_title,
                        $order->status_title,
                        $order->mode_title,
                        $order->datetime,
                    ]
                );
                ob_flush();
                flush();
            }
        }
        ob_end_clean();
        exit;
    }
}