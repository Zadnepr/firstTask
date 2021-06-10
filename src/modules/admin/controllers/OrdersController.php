<?php

namespace app\modules\admin\controllers;

use yii;
use yii\base\BaseObject;
use yii\web\Controller;
use app\modules\admin\models\Orders;
use app\modules\admin\models\Services;
use app\modules\admin\models\SearchForm;

/**
 * Default controller for the `admin` module
 */
class OrdersController extends Controller
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
        $search = $request->get('search', '');
        $searchType = $request->get('search-type', 1);

        if( isset($status) and in_array($status, [0,1,2,3,4]) ){
            $filters['status'] = $status;
        }

        if( isset($service_id) and in_array($service_id, Services::getServicesIds()) ){
            $filters['service'] = $service_id;
        }

        $searchForm = new SearchForm();
        if($search and $searchType){
            $searchForm->search = $search;
            $searchForm->searchType = $searchType;
            if ($searchForm->validate()) {
                $filters['search'] = $search;
                $filters['searchType'] = $searchType;
            }
            else {
                $errors = $searchForm->errors;
            }
        }

        $orders = Orders::getOrdersList(['filters' => $filters]);
        $services = Services::getServicesList(['filters' => $filters]);

        return $this->render('orders', [
            'status' => $status,
            'search' => $search,
            'searchType' => $searchType,
            'service_id' => $service_id,
            'services' => $services,
            'orders' => $orders['data'],
            'pages' => $orders['pagination'],
            'totalCount' => $orders['totalCount'],
            'offset' => $orders['offset'],
            'total' => $orders['total'],
            'errors' => $errors,
        ]);
    }

}
