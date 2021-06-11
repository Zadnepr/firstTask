<?php

namespace app\modules\orders\controllers;

use yii;
use yii\helpers\Json;
use yii\base\BaseObject;
use yii\web\Controller;
use app\modules\orders\models\Orders;
use app\modules\orders\models\Services;
use app\modules\orders\models\Modes;
use app\modules\orders\models\Statuses;
use app\modules\orders\models\SearchForm;

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
        $request = Yii::$app->request;
        $filters = [];
        $status = $request->get('status', null);
        $service_id = $request->get('service', null);
        $mode = $request->get('mode', null);
        $search = $request->get('search', '');
        $searchType = $request->get('search-type', 1);

        /*
         * Validation requests
         */
        $errors = [];
        if($search and $searchType){
            $searchForm = new SearchForm();
            $searchForm->search = $search;
            $searchForm->searchType = $searchType;
            if ($searchForm->validate()) {
                $filters['search'] = [
                    'query' => $search,
                    'type' => $searchType,
                ];
            }
            else {
                $errors[] = $searchForm->errors;
            }
        }
        if(!is_numeric($status) and !is_null($status)){
            $Statuses = new Statuses();
            $Statuses->id = $status;
            if (!$Statuses->validate()) {
                unset($status);
                $errors[] = $Statuses->errors;
            }
        }
        if(!is_numeric($mode) and !is_null($mode)){
            $Modes = new Modes();
            $Modes->id = $mode;
            if (!$Modes->validate()) {
                unset($mode);
                $errors[] = $Modes->errors;
            }
        }
        /* End validations */

        /*
         * Building filters
         */
        if( isset($status) and in_array($status, Statuses::getStatusesIds()) ){
            $filters['status'] = $status;
        }
        if( isset($mode) and in_array($mode, Modes::getModesIds()) ){
            $filters['mode'] = $mode;
        }

        if( isset($service_id) and in_array($service_id, Services::getServicesIds()) ){
            $filters['service'] = $service_id;
        }
        /* End building filters */


        $orders = Orders::getOrdersList(['filters' => $filters]);
        $services = Services::getServicesList(['filters' => $filters]);

        return $this->render('orders', [
            'search' => $search,
            'searchType' => $searchType,
            'service_id' => $service_id,
            'status' => is_null($status) ? null : (int)$status,
            'mode' => is_null($mode) ? null : (int)$mode,
            'services' => $services,
            'statuses' => Statuses::getStatuses(),
            'modes' => Modes::getModes(),
            'orders' => $orders['data'],
            'pages' => $orders['pagination'],
            'totalCount' => $orders['totalCount'],
            'offset' => $orders['offset'],
            'total' => $orders['total'],
            'errors' => $errors,
        ]);
    }

    /**
     * Compile the csv file and return it
     * @return string
     */

    public function actionDownload(){

        if(\Yii::$app->request->isAjax){
            return Json::encode(['success' => 1, 'data' => "", 'post' => \Yii::$app->request->post()]);
        }

    }
}
