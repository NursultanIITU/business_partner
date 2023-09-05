<?php

namespace App\Repositories;

use App\Models\Cart;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class CartRepository
 */
class CartRepository extends BaseRepository implements ICartRepository
{
    /**
     * @var string
     */
    protected $model = Cart::class;

    /**
     * @return LengthAwarePaginator
     * @throws Exception
     * @author Serikbay Nursultan
     */
    public function filterForApi(): LengthAwarePaginator
    {
        $filteredModel = $this->filterModel();

        return $filteredModel->paginate(20);
    }

    /**
     * @return object
     * @throws Exception
     * @author Serikbay Nursultan
     */
    private function filterModel()
    {
        $model = $this->getModel();

        if (count($this->orderParams)) {
            foreach ($this->orderParams as $order) {
                $model = $model->orderBy($order['field'], $order['condition']);
            }
        }

        if (count($this->whereParams)) {

            foreach ($this->whereParams as $where) {
                $model = $model->where($where['field'], $where['operator'], $where['value']);
            }
        }

        if (count($this->whereBetweenParams)) {
            foreach ($this->whereBetweenParams as $params) {
                $model = $model->whereBetween($params['field'], [$params['value1'], $params['value2']]);
            }
        }

        if (count($this->relations)) {
            $model = $model->with($this->relations);
        }

        if (count($this->relationsCount)) {
            $model = $model->withCount($this->relationsCount);
        }

        return $model;
    }

    /**
     * Получение модели
     * @return object
     * @throws Exception
     */
    public function getModel(): object
    {
        if (!$this->model) {
            throw new Exception('Model not specified');
        }

        return new $this->model;
    }
}