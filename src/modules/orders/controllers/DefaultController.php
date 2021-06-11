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
use yii2tech\csvgrid\CsvGrid;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

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

        if($request->get('download', 0) == 1 and $orders['model']) {
            $file_uniq_name = uniqid('csv_') . '.csv';
            $exporter = new CsvGrid([
                'dataProvider' => new ActiveDataProvider([
                    'query' => $orders['model'],
                    'pagination' => [
                        'pageSize' => 100, // export batch size
                    ],
                ]),
                'columns' => [
                    [
                        'attribute' => 'id',
                        'label' => 'ID',
                    ],
                    [
                        'attribute' => 'username',
                        'label' => 'User',
                    ],
                    [
                        'attribute' => 'link',
                        'label' => 'Link',
                    ],
                    [
                        'attribute' => 'quantity',
                        'label' => 'Quantity',
                    ],
                    [
                        'attribute' => 'service_id_title',
                        'label' => 'Service',
                    ],
                    [
                        'attribute' => 'status_title',
                        'label' => 'Status',
                    ],
                    [
                        'attribute' => 'mode_title',
                        'label' => 'Mode',
                    ],
                    [
                        'attribute' => 'datetime',
                        'label' => 'Created',
                    ],
                ],
            ]);
            $exporter->export()->saveAs(__DIR__ . DIRECTORY_SEPARATOR . $file_uniq_name);
            if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . $file_uniq_name)){
                $csvData =  file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . $file_uniq_name);
                unlink(__DIR__ . DIRECTORY_SEPARATOR . $file_uniq_name);
                return $csvData;
            }
        }
        else {
            return $this->render('orders', [
                'search' => $search,
                'searchType' => $searchType,
                'service_id' => $service_id,
                'status' => is_null($status) ? null : (int)$status,
                'mode' => is_null($mode) ? null : (int)$mode,
                'services' => $services['data'],
                'services_sum' => $services['sum'],
                'statuses' => Statuses::getStatuses(),
                'modes' => Modes::getModes(),
                'search_types' => SearchForm::getTypes(),
                'orders' => $orders['data'],
                'pages' => $orders['pagination'],
                'totalCount' => $orders['totalCount'],
                'offset' => $orders['offset'],
                'total' => $orders['total'],
                'errors' => $errors,
            ]);
        }
    }

}
