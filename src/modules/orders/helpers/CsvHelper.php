<?php


namespace app\modules\orders\helpers;
use yii\data\ActiveDataProvider;


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
        fputcsv($stream, [ 'ID','User', 'Link', 'Quantity', 'Service', 'Status', 'Mode', 'Created' ]);
        ob_flush(); flush();
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
                ob_flush(); flush();
            }
        }
        ob_end_clean();
        exit;
    }
}