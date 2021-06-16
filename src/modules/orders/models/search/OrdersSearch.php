<?php


namespace orders\models\search;

use orders\models\Orders;
use orders\models\Services;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\web\ForbiddenHttpException;
use yii;


/**
 * This is the model class for object "OrdersSearch".
 */
class OrdersSearch extends Model
{
    const SEARCH_TYPE_ID = 1;
    const SEARCH_TYPE_LINK = 2;
    const SEARCH_TYPE_USERNAME = 3;


    private static $serchTypes = [
        [
            'id' => self::SEARCH_TYPE_ID,
            'title' => 'search.type.order-id',
            'field' => '`orders`.`id`',
        ],
        [
            'id' => self::SEARCH_TYPE_LINK,
            'title' => 'search.type.link',
            'field' => '`link`',
        ],
        [
            'id' => self::SEARCH_TYPE_USERNAME,
            'title' => 'search.type.username',
            'field' => 'CONCAT_WS(\' \', `users`.`first_name`, `users`.`last_name`)',
        ],
    ];
    public $search;
    public $searchType;
    public $status_id;
    public $service_id;
    public $mode_id;
    public $id;
    public $title;
    public $field;
    private $filters = [];

    function init()
    {
        parent::init();
    }

    /**
     * Returns types objects
     * @return array|string[]
     */
    public static function getTypes()
    {
        return array_map(
            function ($type) {
                $type['title'] = Yii::t(Yii::getAlias('@translateOrders'), $type['title']);
                return new static($type);
            },
            self::$serchTypes
        );
    }

    /**
     * Returns status ids list of orders
     * @return array|string[]
     */
    public static function getTypesIds()
    {
        return array_map(
            function ($statuse) {
                return $statuse['id'];
            },
            self::$serchTypes
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function findTypeIdentityById(int $id)
    {
        foreach (self::$serchTypes as $type) {
            if ($type['id'] === $id) {
                return new static($type);
            }
        }
        return null;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'search' => 'Search query',
        ];
    }

    /**
     * Returns array of loaded filters
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Method for setting filters from request after validation
     * @return $this
     */
    public function setFilters()
    {
        if ($this->search && $this->searchType) {
            $this->filters['search'] = [
                'query' => $this->search,
                'type' => $this->searchType,
            ];
        }
        if (isset($this->status_id)) {
            $this->filters['status_id'] = $this->status_id;
        }
        if (isset($this->mode_id)) {
            $this->filters['mode_id'] = $this->mode_id;
        }
        if (isset($this->service_id)) {
            $this->filters['service_id'] = $this->service_id;
        }
        return $this;
    }

    /**
     * @param $object
     * @param array $ignores
     * @return mixed
     */
    public function applyFilters(&$object, array $ignores = [])
    {
        if ($this->filters) {
            if (!$this->getFirstError('status_id') && !in_array('status_id', $ignores)) {
                $object->andFilterWhere(['status' => $this->filters['status_id']]);
            }
            if (!$this->getFirstError('mode_id') && !in_array('mode_id', $ignores)) {
                $object->andFilterWhere(['mode' => $this->filters['mode_id']]);
            }
            if (!$this->getFirstError('service_id') && !in_array('service_id', $ignores)) {
                $object->andFilterWhere(['service_id' => $this->filters['service_id']]);
            }
            if (!$this->getFirstError('search') && !$this->getFirstError('searchType') && isset($this->filters['search']) && !in_array('search', $ignores)) {
                $searchType = OrdersSearch::findTypeIdentityById($this->filters['search']['type']);
                if ($searchType) {
                    $object->andWhere(['like', $searchType->field, $this->filters['search']['query']]);
                }
            }
        }
        return $object;
    }

    /**
     * {@inheritdoc}
     */
    public function afterValidate()
    {
        parent::afterValidate();
        if(!$this->getFirstError('status_id') && isset($this->status_id)){
            $this->status_id = (int)$this->status_id;
        }
        if(!$this->getFirstError('mode_id') && isset($this->mode_id)){
            $this->mode_id = (int)$this->mode_id;
        }
        if(!$this->getFirstError('service_id') && isset($this->service_id)){
            $this->service_id = (int)$this->service_id;
        }
        if(!$this->getFirstError('searchType') && isset($this->searchType)){
            $this->searchType = (int)$this->searchType;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['search', 'string', 'length' => [4, 24]],
            ['searchType', 'integer'],
            ['status_id', 'integer'],
            ['service_id', 'integer'],
            ['mode_id', 'integer'],
            ['status_id', 'in', 'range' => StatusesSearch::getStatusesIds()],
            ['service_id', 'in', 'range' => ServicesSearch::search()],
            ['mode_id', 'in', 'range' => ModesSearch::getModesIds()],
            ['searchType', 'in', 'range' => self::getTypesIds()],
        ];
    }
    
    

    /**
     * @throws ForbiddenHttpException
     */
    public function validationException(){
         throw new ForbiddenHttpException(Yii::t(Yii::getAlias('@translateOrders'), 'error.validationException'));
    }

    /**
     * Returns object ActiveDataProvider with filtered Orders
     * @param array $settings
     * @return ActiveDataProvider
     */
    public function search(array $settings = [])
    {
        $defaultSettings = [
            'limit' => 100,
        ];

        $settings = array_merge($defaultSettings, $settings);

        self::setFilters();

        $Orders = Orders::find()
            ->leftJoin('users', '`orders`.`user_id` = `users`.`id`')
            ->with('users', 'services');

        if (!$this->validate()) {
            self::validationException();
        }

        self::applyFilters($Orders);

        $provider = new ActiveDataProvider(
            [
                'query' => $Orders,
                'pagination' => [
                    'pageSize' => $settings['limit'], // export batch size
                ],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                    ]
                ],
            ]
        );
        return $provider;
    }

    /**
     * Returns object ActiveDataProvider with filtered Services (ignoring filter service_id)
     * @param array $settings
     * @return ActiveDataProvider
     */
    public function getServices(array $settings = [])
    {
        $defaultSettings = [
            'limit' => 20,
            'order' => 'counts desc',
        ];

        $settings = array_merge($defaultSettings, $settings);

        self::setFilters();

        $services = Services::find()
            ->select('`services`.*, COUNT(`services`.`id`) as `counts`')
            ->leftJoin('orders', '`orders`.`service_id` = `services`.`id`')
            ->leftJoin('users', '`orders`.`user_id` = `users`.`id`')
            ->groupBy('`services`.`id`');

        if (!$this->validate()) {
            self::validationException();
        }

        self::applyFilters($services, ['service_id']);

        if (is_numeric($settings['limit']) && $settings['limit'] > 0) {
            $services->limit($settings['limit']);
        }
        if ($settings['order']) {
            $services->orderBy($settings['order']);
        }

        $provider = new ActiveDataProvider(
            [
                'query' => $services,
            ]
        );

        return $provider;
    }

}