<?php

namespace orders\controllers;

use orders\helpers\CsvHelper;
use orders\models\search\ModesSearch;
use orders\models\search\OrdersSearch;
use orders\models\search\StatusesSearch;
use yii;
use yii\base\BaseObject;
use yii\base\Exception;
use yii\web\Controller;

/**
 * Order controller for the `orders` module
 */
class OrderController extends Controller
{

    /**
     * errorHandler for module orders
     * @return string
     */
    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            $request = Yii::$app->request;
            $ordersSearch = new OrdersSearch();
            $ordersSearch->load($request->get(), '');
            return $this->render(
                'orders',
                [
                    'services' => $ordersSearch->getServices(),
                    'search' => $ordersSearch->search,
                    'searchType' => $ordersSearch->searchType,
                    'serviceId' => $ordersSearch->serviceId,
                    'statusId' => $ordersSearch->statusId,
                    'modeId' => $ordersSearch->modeId,
                    'statuses' => StatusesSearch::getStatuses(),
                    'modes' => ModesSearch::getModes(),
                    'searchTypes' => OrdersSearch::getTypes(),
                    'errors' => $ordersSearch->getErrors(),
                    'exception' => $exception,
                ]
            );
        }
    }

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
                'search' => $ordersSearch->search,
                'searchType' => $ordersSearch->searchType,
                'serviceId' => $ordersSearch->serviceId,
                'statusId' => $ordersSearch->statusId,
                'modeId' => $ordersSearch->modeId,
                'statuses' => StatusesSearch::getStatuses(),
                'modes' => ModesSearch::getModes(),
                'searchTypes' => OrdersSearch::getTypes(),
            ]
        );
    }

    /**
     * Download CSV
     */
    public function actionDownload()
    {
        $request = Yii::$app->request;
        $ordersSearch = new OrdersSearch();
        $ordersSearch->load($request->get(), '');
        /*
         * Use module helper for download csv file
         */
        CsvHelper::sendCsvFromBuffer($ordersSearch->search());

    }

}
