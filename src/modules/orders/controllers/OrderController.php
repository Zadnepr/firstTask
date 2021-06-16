<?php

namespace orders\controllers;

use orders\helpers\CsvHelper;
use orders\helpers\VariablesHelper;
use orders\models\search\ModesSearch;
use orders\models\search\OrdersSearch;
use orders\models\search\StatusesSearch;
use yii;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

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

        try {
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
                    'orders' => $ordersSearch->search(),
                    'services' => $ordersSearch->getServices(),
                    'errors' => $ordersSearch->getErrors(),
                ]
            );
        }
        catch(ForbiddenHttpException $e){
            return self::errorPage($e);
        }
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

    public function errorPage(ForbiddenHttpException $e)
    {
        $request = Yii::$app->request;
        return $this->render('error', [
            'search' => $request->get('search', ''),
            'searchType' => $request->get('searchType', 1),
            'service_id' => VariablesHelper::intNull($request->get('service_id', null)),
            'status_id' => VariablesHelper::intNull($request->get('status_id', null)),
            'mode_id' => VariablesHelper::intNull($request->get('mode_id', null)),
            'statuses' => StatusesSearch::getStatuses(),
            'modes' => ModesSearch::getModes(),
            'search_types' => OrdersSearch::getTypes(),
            'errors' => [$e->getMessage()]
        ]);
    }

}
