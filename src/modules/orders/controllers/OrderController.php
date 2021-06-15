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
        $status_id = $request->get('status_id', null);
        $service_id = $request->get('service_id', null);
        $mode_id = $request->get('mode_id', null);
        $search = $request->get('search', '');
        $searchType = $request->get('searchType', 1);

        $ordersSearch = new OrdersSearch();

        /*
         * Validation request and search
         */
        if ($request->get()) {
            $ordersSearch->load($request->get(), '');
            if (!$ordersSearch->validate()) {
                $errors = $ordersSearch->errors;
            }
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
                    'search' => $search,
                    'searchType' => $searchType,
                    'service_id' => is_null($service_id) ? null : (int)$service_id,
                    'status_id' => is_null($status_id) ? null : (int)$status_id,
                    'mode_id' => is_null($mode_id) ? null : (int)$mode_id,
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

}
