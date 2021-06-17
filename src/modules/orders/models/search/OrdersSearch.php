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
 *
 * @const SEARCH_TYPE_ID
 * @const SEARCH_TYPE_LINK
 * @const SEARCH_TYPE_USERNAME
 * @property $search
 * @property $searchType
 * @property $statusId
 * @property $serviceId
 * @property $modeId
 * @property $id
 * @property $title
 * @property $field
 * @property array $searchTypes
 * @property array $filters
 */
class OrdersSearch extends Model
{
    public const SEARCH_TYPE_ID = 1;
    public const SEARCH_TYPE_LINK = 2;
    public const SEARCH_TYPE_USERNAME = 3;
    public $search;
    public $searchType;
    public $statusId;
    public $serviceId;
    public $modeId;
    public $id;
    public $title;
    public $field;
    private static array $searchTypes = [
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
    private array $filters = [];

    /**
     * Returns types objects
     * @return array|string[]
     */
    public static function getTypes(): array
    {
        return array_map(
            function ($type) {
                $type['title'] = Yii::t('orders/main', $type['title']);
                return new static($type);
            },
            self::$searchTypes
        );
    }

    /**
     * Returns status ids list of orders
     * @return array|string[]
     */
    public static function getTypesIds(): array
    {
        return array_map(
            function ($statuse) {
                return $statuse['id'];
            },
            self::$searchTypes
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function findTypeIdentityById(int $id): ?OrdersSearch
    {
        foreach (self::$searchTypes as $type) {
            if ($type['id'] === $id) {
                return new static($type);
            }
        }
        return null;
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'search' => 'Search query',
        ];
    }

    /**
     * Returns array of loaded filters
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * Method for setting filters from request after validation
     * @return $this
     */
    public function setFilters(): OrdersSearch
    {
        if ($this->search && $this->searchType) {
            $this->filters['search'] = [
                'query' => $this->search,
                'type' => $this->searchType,
            ];
        }
        if (isset($this->statusId)) {
            $this->filters['statusId'] = $this->statusId;
        }
        if (isset($this->modeId)) {
            $this->filters['modeId'] = $this->modeId;
        }
        if (isset($this->serviceId)) {
            $this->filters['serviceId'] = $this->serviceId;
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
            if (!$this->getFirstError('statusId') && !in_array('statusId', $ignores)) {
                $object->andFilterWhere(['status' => $this->filters['statusId']]);
            }
            if (!$this->getFirstError('modeId') && !in_array('modeId', $ignores)) {
                $object->andFilterWhere(['mode' => $this->filters['modeId']]);
            }
            if (!$this->getFirstError('serviceId') && !in_array('serviceId', $ignores)) {
                $object->andFilterWhere(['service_id' => $this->filters['serviceId']]);
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
        if(!$this->getFirstError('statusId') && isset($this->statusId)){
            $this->statusId = (int)$this->statusId;
        }
        if(!$this->getFirstError('modeId') && isset($this->modeId)){
            $this->modeId = (int)$this->modeId;
        }
        if(!$this->getFirstError('serviceId') && isset($this->serviceId)){
            $this->serviceId = (int)$this->serviceId;
        }
        if(!$this->getFirstError('searchType') && isset($this->searchType)){
            $this->searchType = (int)$this->searchType;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['search', 'string'],
            ['searchType', 'integer'],
            ['statusId', 'integer'],
            ['serviceId', 'integer'],
            ['modeId', 'integer'],
            ['statusId', 'in', 'range' => StatusesSearch::getStatusesIds()],
            ['serviceId', 'in', 'range' => ServicesSearch::search()],
            ['modeId', 'in', 'range' => ModesSearch::getModesIds()],
            ['searchType', 'in', 'range' => self::getTypesIds()],
        ];
    }
    
    

    /**
     * @throws ForbiddenHttpException
     */
    public function validationException(){
         throw new ForbiddenHttpException(Yii::t('orders/main', 'error.validationException'));
    }

    /**
     * Returns object ActiveDataProvider with filtered Orders
     * @param array $settings
     * @return ActiveDataProvider
     * @throws ForbiddenHttpException
     */
    public function search(array $settings = []): ActiveDataProvider
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
     * @throws ForbiddenHttpException
     */
    public function getServices(array $settings = []): ActiveDataProvider
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

        self::applyFilters($services, ['serviceId']);

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