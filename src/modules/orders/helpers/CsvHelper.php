<?php


namespace orders\helpers;

use yii;
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

        $dataProvider->getModels();
        $totalPages = $dataProvider->getPagination()->getPageCount();

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
        for ($page = 1; $page <= $totalPages; $page++) {
            $dataProvider->getPagination()->setPage($page);
            $dataProvider->refresh();
            foreach ($dataProvider->getModels() as $order) {
                fputcsv(
                    $stream,
                    [
                        $order->id,
                        $order->username,
                        $order->link,
                        $order->quantity,
                        $order->serviceId . ' ' . $order->serviceTitle,
                        $order->statusTitle,
                        $order->modeTitle,
                        $order->datetime,
                    ]
                );
            }
            ob_flush();
            flush();
        }
        ob_end_clean();
        exit;
    }
}