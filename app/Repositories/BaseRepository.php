<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class BaseRepository
 */
class BaseRepository implements IBaseRepository
{
    /**
     * @var null
     */
    protected $model = null;

    /**
     * @var int
     */
    protected $perPage = 25;

    /**
     * @var array
     */
    protected $whereHasParams = [];

    /**
     * @var array
     */
    protected $selections = [];

    /**
     * @var array
     */
    protected $whereParams = [];

    /**
     * @var array
     */
    protected $orWhereParams = [];

    /**
     * @var array
     */
    protected $orderParams = [];

    /**
     * @var array
     */
    protected $whereInParams = [];

    /**
     * @var array
     */
    protected $whereBetweenParams = [];

    /**
     * @var array
     */
    protected $relations = [];

    /**
     * @var array
     */
    protected $relationsCount = [];

    /**
     * @var bool
     */
    protected $treeDefaultOrder = false;

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     * @author Serikbay Nursultan
     */
    public function store(array $data)
    {
        return $this->getModel()->create($data);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @author Serikbay Nursultan
     */
    public function getById(int $id)
    {
        $model = $this->getModel();

        if (count($this->relations)) {
            $model = $model->with($this->relations);
        }

        if (count($this->relationsCount)) {
            $model = $model->withCount($this->relationsCount);
        }

        return $model->find($id);
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Exception
     * @author Serikbay Nursultan
     */
    public function getByIdOnlyTrashed(int $id)
    {
        $model = $this->getModel();

        if (count($this->relations)) {
            $model = $model->with($this->relations);
        }

        if (count($this->relationsCount)) {
            $model = $model->withCount($this->relationsCount);
        }

        return $model->onlyTrashed()->find($id);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Exception
     * @author Serikbay Nursultan
     */
    public function firstOrCreate(array $data)
    {
        $model = $this->getModel();

        return $model->firstOrCreate($data);
    }

    /**
     * @return LengthAwarePaginator
     * @author Serikbay Nursultan
     */
    public function filter(): LengthAwarePaginator
    {
        $filteredModel = $this->filterModel();

        return $filteredModel->paginate($this->perPage);
    }

    /**
     * @return Collection
     * @author Serikbay Nursultan
     */
    public function list(): Collection
    {
        $filteredMOdel = $this->filterModel();

        return ($this->treeDefaultOrder) ? $filteredMOdel->defaultOrder()->get()->toTree() : $filteredMOdel->get();
    }

    /**
     * @return Collection
     * @author Serikbay Nursultan
     */
    public function trashedFilter(): LengthAwarePaginator
    {
        $filteredModel = $this->filterModel();

        return $filteredModel->onlyTrashed()->paginate($this->perPage);
    }

    /**
     * @return mixed
     * @author Serikbay Nursultan
     */
    public function first()
    {
        $filteredMOdel = $this->filterModel();

        return $filteredMOdel->first();
    }

    /**
     * @param int $id
     * @return mixed|void
     * @throws \Exception
     * @author Serikbay Nursultan
     */
    public function deleteById(int $id)
    {
        $model = $this->getById($id);
        $model->delete();
    }

    /**
     * @param string $field
     * @param string $value
     * @throws \Exception
     * @author Serikbay Nursultan
     */
    public function deleteByField(string $field, string $value)
    {
        $model = $this->getModel();
        $model->where($field, $value);
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     * @throws \Exception
     * @author Serikbay Nursultan
     */
    public function updateById(int $id, array $data)
    {
        $model = $this->getById($id);
        $model->update($data);

        return $model;
    }

    /**
     * @param string $fieldName
     * @param string $fieldValue
     * @param array $data
     * @return mixed|null
     * @throws \Exception
     * @author Serikbay Nursultan
     */
    public function updateByField(string $fieldName, string $fieldValue, array $data)
    {
        $item = $this->whereEqual($fieldName, $fieldValue)->first();

        if ($item) {
            return $this->updateById($item->id, $data);
        }

        return null;
    }

    /**
     * @param array $relations
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function with(array $relations)
    {
        $this->relations = $relations;

        return $this;
    }

    /**
     * @param array $relations
     * @return $this
     * @author Serikbay Nursultan
     */
    public function withCount(array $relations)
    {
        $this->relationsCount = $relations;

        return $this;
    }

    /**
     * @param string $field
     * @param string $condition
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function order(string $field, string $condition)
    {
        $this->orderParams[] = ['field' => $field, 'condition' => $condition];

        return $this;
    }

    /**
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function treeDefaultOrder()
    {
        $this->treeDefaultOrder = true;
        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this
     * @author Serikbay Nursultan
     */
    public function where(string $field, string $value)
    {
        $this->whereParams[] = ['field' => $field, 'operator' => '=', 'value' => $value];

        return $this;
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function withDataFilter(Request $request)
    {
        $filteredModel = $this->filterModel()->filter($request->all());

        return $filteredModel->paginate($this->perPage);
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this
     * @author Serikbay Nursultan
     */
    public function whereHas(string $field, $value)
    {
        $this->whereHasParams[] = ['field' => $field, 'value' => $value];

        return $this;
    }


    /**
     * @param string $field
     * @param string $value
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function whereEqual(string $field, string $value)
    {

        $this->whereParams[] = ['field' => $field, 'operator' => '=', 'value' => $value];

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this|mixed
     * @author Anastas Mironov
     */
    public function orWhereEqual(string $field, string $value)
    {
        $this->orWhereParams[] = ['field' => $field, 'operator' => '=', 'value' => $value];

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function whereNotEqual(string $field, string $value)
    {
        $this->whereParams[] = ['field' => $field, 'operator' => '!=', 'value' => $value];

        return $this;
    }

    /**
     * @param string $field
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function whereNotNull(string $field)
    {
        $this->whereParams[] = ['field' => $field, 'operator' => '!=', 'value' => null];

        return $this;
    }

    /**
     * @param string $field
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function whereNull(string $field)
    {
        $this->whereParams[] = ['field' => $field, 'operator' => '=', 'value' => null];

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function whereMore(string $field, string $value)
    {
        $this->whereParams[] = ['field' => $field, 'operator' => '>', 'value' => $value];

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function whereLess(string $field, string $value)
    {
        $this->whereParams[] = ['field' => $field, 'operator' => '<', 'value' => $value];

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function whereMoreOrEqual(string $field, string $value)
    {
        $this->whereParams[] = ['field' => $field, 'operator' => '>=', 'value' => $value];

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function whereLessOrEqual(string $field, string $value)
    {
        $this->whereParams[] = ['field' => $field, 'operator' => '<=', 'value' => $value];

        return $this;
    }

    /**
     * @param string $field
     * @param array $values
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function whereIn(string $field, array $values)
    {
        $this->whereInParams = [$field, $values];

        return $this;
    }

    /**
     * @param string $field
     * @param string $value1
     * @param string $value2
     * @return $this
     * @author Serikbay Nursultan
     */
    public function whereBetween(string $field, string $value1, string $value2)
    {
        $this->whereBetweenParams[] = ['field' => $field, 'value1' => $value1, 'value2' => $value2];

        return $this;
    }

    /**
     * @param string $field
     * @param string $value
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function whereLike(string $field, string $value)
    {

        $this->whereParams[] = ['field' => $field, 'operator' => 'like', 'value' => '%' . $value . '%'];

        return $this;
    }

    /**
     * @param array $selections
     * @return $this|mixed
     * @author Serikbay Nursultan
     */
    public function select(array $selections)
    {
        $this->selections = $selections;

        return $this;
    }

    /**
     * @return object
     * @throws \Exception
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

        if (count($this->orWhereParams)) {
            foreach ($this->orWhereParams as $where) {
                $model = $model->orWhere($where['field'], $where['operator'], $where['value']);
            }
        }

        if (count($this->whereHasParams))
        {
            foreach ($this->whereHasParams as $whereHas)
            {
                $model = $model->whereHas($whereHas['field'], $whereHas['value']);
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

        if (count($this->selections)) {
            $model = $model->select($this->selections);
        }

        return $model;
    }


    /**
     * Получение модели
     * @return object
     * @throws \Exception
     */
    public function getModel()
    {
        if (!$this->model) {
            throw new \Exception('Model not specified');
        }

        return new $this->model;
    }

    /**
     * @param string $model
     */
    public function setModel(string $model)
    {
        $this->model = $model;
        $this->relationsCount = [];
        $this->relations = [];
        $this->whereParams = [];
        $this->orWhereParams = [];
        $this->orderParams = [];

        return $this;
    }
}
