<?php


namespace App\Repositories\ResultTest;


use App\Repositories\IRepositoryInterface;

interface IResultTestRepositoryInterface extends IRepositoryInterface
{
    public function getResultTestOnline($id);
}
