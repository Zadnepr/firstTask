<?php

namespace orders\controllers;

use orders\helpers\CsvHelper;
use orders\models\search\ModesSearch;
use orders\models\search\OrdersSearch;
use orders\models\search\StatusesSearch;
use yii;
use yii\web\Controller;

/**
 * Order controller for the `orders` module
 */
class OrderController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $ordersSearch = new OrdersSearch();
        $ordersSearch->load($request->get(), '');

        return $this->render(
            'orders',
            [
                'orders' => $ordersSearch->search(),
                'services' => $ordersSearch->getServices(),
                'search' => $request->get('search', ''),
                'searchType' => $ordersSearch->searchType,
                'serviceId' => $ordersSearch->service_id,
                'statusId' => $ordersSearch->status_id,
                'modeId' => $ordersSearch->mode_id,
                'statuses' => StatusesSearch::getStatuses(),
                'modes' => ModesSearch::getModes(),
                'searchTypes' => OrdersSearch::getTypes(),
                'errors' => $ordersSearch->getErrors(),
            ]
        );
    }

    public function actionDownload(){
        $request = Yii::$app->request;
        $ordersSearch = new OrdersSearch();
        $ordersSearch->load($request->get(), '');
        /*
         * Use module helper for download csv file
         */
        CsvHelper::sendCsvFromBuffer($ordersSearch->search());
    }

}
