<?php


namespace orders\helpers;

use yii\data\ActiveDataProvider;

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
                Yii::t(Yii::getAlias('@translateOrders'), 'table.id'),
                Yii::t(Yii::getAlias('@translateOrders'), 'table.user'),
                Yii::t(Yii::getAlias('@translateOrders'), 'table.link'),
                Yii::t(Yii::getAlias('@translateOrders'), 'table.quantity'),
                Yii::t(Yii::getAlias('@translateOrders'), 'table.service'),
                Yii::t(Yii::getAlias('@translateOrders'), 'table.status'),
                Yii::t(Yii::getAlias('@translateOrders'), 'table.mode'),
                Yii::t(Yii::getAlias('@translateOrders'), 'table.created'),
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