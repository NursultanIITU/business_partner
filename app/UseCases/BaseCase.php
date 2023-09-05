<?php

namespace App\UseCases;

use Illuminate\Http\Request;

/**
 * Class BaseCase
 */
class BaseCase
{
    /**
     * @var $repository
     */
    protected $repository;

    /**
     * @param array $data
     * @return mixed
     * @author Serikbay Nursultan
     */
    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    /**
     * @return mixed
     * @author Serikbay Nursultan
     */
    public function getList()
    {
        return $this->repository->order('created_at', 'desc')->filter();
    }

    /**
     * @return mixed
     * @author Serikbay Nursultan
     */
    public function getListWithOnlyTrashed()
    {
        return $this->repository->order('created_at', 'desc')->trashedFilter();
    }

    /**
     * @return mixed
     * @author Serikbay Nursultan
     */
    public function getCollection()
    {
        return $this->repository->list();
    }

    /**
     * @return mixed
     * @author Serikbay Nursultan
     */
    public function getTreeCollection()
    {
        return $this->repository->treeDefaultOrder()->list();
    }

    /**
     * @param array $relations
     * @return $this
     * @author Serikbay Nursultan
     */
    public function with(array $relations)
    {
        $this->repository->with($relations);

        return $this;
    }

    /**
     * @param array $relations
     * @return $this
     * @author Serikbay Nursultan
     */
    public function withCount(array $relations)
    {
        $this->repository->withCount($relations);

        return $this;
    }

    /**
     * @param int $id
     * @param array $relations
     * @return mixed
     * @author Serikbay Nursultan
     */
    public function item(int $id, array $relations = [])
    {
        return $this->repository->with($relations)->withCount($relations)->getById($id);
    }

    /**
     * @param int $id
     * @param array $relations
     * @return mixed
     * @author Serikbay Nursultan
     */
    public function trashedItem(int $id, array $relations = [])
    {
        return $this->repository->with($relations)->withCount($relations)->getByIdOnlyTrashed($id);
    }

    /**
     * @return mixed
     * @author Serikbay Nursultan
     */
    public function first()
    {
       return $this->repository->first();
    }

    /**
     * @param string $field
     * @param string $condition
     * @return $this
     * @author Serikbay Nursultan
     */
    public function order(string $field, string $condition)
    {
        $this->repository->order($field, $condition);

        return $this;
    }

    /**
     * @param int $id
     * @param array $data
     * @return mixed
     * @author Serikbay Nursultan
     */
    public function update(int $id, array $data)
    {
        return $this->repository->updateById($id, $data);
    }

    /**
     * @param string $column
     * @param string $value
     * @return $this
     * @author Serikbay Nursultan
     */
    public function where(string $column, string $value)
    {
        $this->repository->whereEqual($column, $value);

        return $this;
    }

    /**
     * @param string $column
     * @param string $value
     * @return $this
     * @author Anastas Mironov
     */
    public function orWhere(string $column, string $value)
    {
        $this->repository->orWhereEqual($column, $value);

        return $this;
    }

    /**
     * @return mixed
     * @author Serikbay Nursultan
     */
    public function getListWithDataFilter(Request $request)
    {
        return $this->repository->withDataFilter($request);
    }

    /**
     * @param string $column
     * @param string $value
     * @return $this
     * @author Serikbay Nursultan
     */
    public function whereHas(string $column, $value)
    {
        $this->repository->whereHas($column, $value);

        return $this;
    }

    /**
     * @param string $column
     * @return $this
     * @author Serikbay Nursultan
     */
    public function whereNull(string $column)
    {
        $this->repository->whereNull($column);

        return $this;
    }

    /**
     * @param string $column
     * @param string $value
     * @return $this
     * @author Serikbay Nursultan
     */
    public function whereMore(string $column, string $value)
    {
        $this->repository->whereMore($column, $value);

        return $this;
    }

    /**
     * @param string $column
     * @param string $value
     * @return $this
     * @author Serikbay Nursultan
     */
    public function whereLess(string $column, string $value)
    {
        $this->repository->whereLess($column, $value);

        return $this;
    }

    /**
     * @param string $column
     * @param string $value
     * @return $this
     * @author Serikbay Nursultan
     */
    public function whereMoreOrEqual(string $column, string $value)
    {
        $this->repository->whereMoreOrEqual($column, $value);

        return $this;
    }

    /**
     * @param string $column
     * @param string $value
     * @return $this
     * @author Serikbay Nursultan
     */
    public function whereLessOrEqual(string $column, string $value)
    {
        $this->repository->whereLessOrEqual($column, $value);

        return $this;
    }

    /**
     * @param string $column
     * @param string $value1
     * @param string $value2
     * @return $this
     * @author Serikbay Nursultan
     */
    public function whereBetween(string $column, string $value1, string $value2)
    {
        $this->repository->whereBetween($column, $value1, $value2);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return $this
     * @author Serikbay Nursultan
     */
    public function whereIn(string $column, array $values)
    {
        $this->repository->whereIn($column, $values);

        return $this;
    }

    /**
     * @param string $column
     * @param string $value
     * @return $this
     * @author Serikbay Nursultan
     */
    public function whereLike(string $column, string $value)
    {
        $this->repository->whereLike($column, $value);

        return $this;
    }

    /**
     * @param array $selections
     * @return $this
     * @author Serikbay Nursultan
     */
    public function select(array $selections)
    {
        $this->repository->select($selections);

        return $this;
    }

    /**
     * @param int $id
     * @author Serikbay Nursultan
     */
    public function treeUp(int $id)
    {
        $item = $this->item($id);

        if ($item) {
            $item->up();
        }
    }

    /**
     * @param int $id
     * @author Serikbay Nursultan
     */
    public function treeDown(int $id)
    {
        $item = $this->item($id);

        if ($item) {
            $item->down();
        }
    }
}
