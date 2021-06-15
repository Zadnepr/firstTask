<?php

namespace orders\controllers;

use orders\helpers\CsvHelper;
use orders\helpers\VariablesHelper;
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

        /*
         * Validation request and search
         */
        if ($request->get()) {
            $ordersSearch->load($request->get(), '');
            if (!$ordersSearch->validate()) {
                $errors = $ordersSearch->errors;
            }
            //var_dump($ordersSearch->errors);
        }
        $orders = $ordersSearch->search();
        $services = $ordersSearch->getServices();
        /* End validations and search */

        if ($request->get('download', 0) == 1) {
            /*
             * Use module helper for download csv file
             */
            CsvHelper::sendCsvFromBuffer($orders);
        } else {
            return $this->render(
                'orders',
                [
                    'search' => $request->get('search', ''),
                    'searchType' => $request->get('searchType', 1),
                    'service_id' => VariablesHelper::intNull($request->get('service_id', null)),
                    'status_id' => VariablesHelper::intNull($request->get('status_id', null)),
                    'mode_id' => VariablesHelper::intNull($request->get('mode_id', null)),
                    'statuses' => StatusesSearch::getStatuses(),
                    'modes' => ModesSearch::getModes(),
                    'search_types' => OrdersSearch::getTypes(),
                    'orders' => $orders,
                    'services' => $services,
                    'errors' => $errors,
                ]
            );
        }
    }

    public function actionDownload(){

    }

}
