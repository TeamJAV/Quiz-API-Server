<?php


namespace App\Repositories;


use Illuminate\Contracts\Container\BindingResolutionException;

abstract class BaseRepository implements IRepositoryInterface
{

    protected $model;
    protected $perPage = 20;

    public function __construct()
    {
        $this->setModel();
    }

    public function setModel()
    {
        try {
            $this->model = app()->make($this->getModel());
        } catch (BindingResolutionException $e) {
            die($e->getMessage());
        }
    }

    abstract public function getModel();


    public function getAll()
    {
        // TODO: Implement getAll() method.
        return $this->model->all();

    }

    public function find($id)
    {
        // TODO: Implement find() method.
        return $this->model->find($id);
    }

    public function create($attributes = [])
    {
        // TODO: Implement create() method.
        return $this->model->create($attributes);
    }

    public function update($id, $attributes = [])
    {
        // TODO: Implement update() method.
        $record = $this->find($id);
        if ($record) {
            $record->update($attributes);
            return $record;
        }
        return false;
    }

    public function delete($id): bool
    {
        // TODO: Implement delete() method.
        $record = $this->find($id);
        if ($record) {
            $record->delete($id);
            return true;
        }
        return false;
    }

    public function findTrash($id){
        return $this->model->withTrashed()->find($id);
    }
}
