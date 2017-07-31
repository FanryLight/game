<?php
/**
 * Created by PhpStorm.
 * User: Sarah
 * Date: 29-Jul-17
 * Time: 12:34
 */

interface iEntity
{

    /**
     * save entity in db
     */
    public function save();

    /**
     * delete entity from db
     */
    public function delete();

    /**
     * find entity by it's id
     *
     * @param int $id
     * @return mixed
     */
    public static function findById($id);

    /**
     * find entity by value of parameter
     *
     * @param string $parameter
     * @param string $value
     * @return mixed
     */
    public static function findBy($parameter, $value);

    /**
     * find all entities
     *
     * @return mixed
     */
    public static function findAll();
}