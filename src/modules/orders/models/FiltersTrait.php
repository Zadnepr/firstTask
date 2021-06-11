<?php
namespace app\modules\orders\models;

trait FiltersTrait {

    private function applyFilters($object, $filters){
        if(isset($filters['status'])) {
            $object->andWhere(['status' => $filters['status']]);
        }
        if(isset($filters['mode'])) {
            $object->andWhere(['mode' => $filters['mode']]);
        }
        if(isset($filters['service'])) {
            $object->andWhere(['service_id' => $filters['service']]);
        }

        if(isset($filters['search'])) {
            $searchType = SearchForm::findIdentityById($filters['search']['type']);
            if($searchType)
                $object->andWhere(['like', $searchType->field, $filters['search']['query'] ]);
        }
        return $object;
    }
}
