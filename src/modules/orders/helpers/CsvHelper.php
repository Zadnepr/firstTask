<?php


namespace app\modules\orders\helpers;
use yii\data\ActiveDataProvider;
use app\modules\orders\Module;

class CsvHelper
{
    /**
     * @param $dataProvider ActiveDataProvider
     */
    public static function sendCsvFromBuffer(ActiveDataProvider $dataProvider)
    {

        $fileName = "Orders ".date('Y-m-d_His');
        $stream = fopen('php://output', 'a');
        header('Content-Disposition: attachment;filename="' . $fileName . '.csv"');
        ob_start();
        fputcsv($stream, [
            Module::t('main', 'table.id'),
            Module::t('main', 'table.user'),
            Module::t('main', 'table.link'),
            Module::t('main', 'table.quantity'),
            Module::t('main', 'table.service'),
            Module::t('main', 'table.status'),
            Module::t('main', 'table.mode'),
            Module::t('main', 'table.created'),
        ]);
        ob_flush();
        flush();
        foreach ($dataProvider->query->batch(100) as  $orders) {
            foreach ($orders as $key => $order){
                fputcsv($stream, [
                    $order->id,
                    $order->username,
                    $order->link,
                    $order->quantity,
                    $order->service_id_title,
                    $order->status_title,
                    $order->mode_title,
                    $order->datetime,
                ]);
                ob_flush();
                flush();
            }
        }
        ob_end_clean();
        exit;
    }
}