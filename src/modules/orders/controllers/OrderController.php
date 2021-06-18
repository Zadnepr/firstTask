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
     * @throws yii\web\ForbiddenHttpException
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $ordersSearch = new OrdersSearch();
        $ordersSearch->load($request->get(), '');

        try {
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
        } catch (yii\base\Exception $e) {
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
                    'exception' => $e,
                ]
            );
        }
    }

    /**
     * @throws yii\web\ForbiddenHttpException
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
