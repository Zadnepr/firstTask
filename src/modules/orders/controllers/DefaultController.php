<?php

namespace app\modules\orders\controllers;

use yii;
use yii\web\Controller;
use app\modules\orders\models\Modes;
use app\modules\orders\models\Statuses;
use app\modules\orders\models\OrdersSearch;
use app\modules\orders\helpers\CsvHelper;

/**
 * Default controller for the `orders` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        //Yii::$app->language = 'ru-RU';
        $request = Yii::$app->request;
        $status_id = $request->get('status_id', null);
        $service_id = $request->get('service_id', null);
        $mode_id = $request->get('mode_id', null);
        $search = $request->get('search', '');
        $searchType = $request->get('searchType', 1);

        $OrdersSearch = new OrdersSearch();

        /*
         * Validation request and search
         */
        if($request->get()){
            $OrdersSearch->load($request->get(), '');
            if (!$OrdersSearch->validate()) {
                $errors = $OrdersSearch->errors;
            }
        }
        $orders = $OrdersSearch->search();
        $services = $OrdersSearch->getServices();
        /* End validations and search */

        if($request->get('download', 0) == 1) {
            /*
             * Use module helper for download csv file
             */
            CsvHelper::sendCsvFromBuffer($OrdersSearch->search());
        }
        else {
            return $this->render('orders', [
                'search' => $search,
                'searchType' => $searchType,
                'service_id' => is_null($service_id) ? null : (int)$service_id,
                'status_id' => is_null($status_id) ? null : (int)$status_id,
                'mode_id' => is_null($mode_id) ? null : (int)$mode_id,
                'statuses' => Statuses::getStatuses(),
                'modes' => Modes::getModes(),
                'search_types' => OrdersSearch::getTypes(),

                'orders' => $orders,
                'services' => $services,
                'errors' => $errors,
            ]);
        }
    }

}
