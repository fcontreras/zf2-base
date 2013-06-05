<?php
namespace Giniem\Model;

use Zend\Db\TableGateway\TableGateway;

class UsersService {

    private $gateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->gateway = $tableGateway;
    }
    
    public function getByUsername($username) {
        $rowset = $this->gateway->select(array('username' => $username));
        $row = $rowset->current();
        return $row;
    }
    
    public function getById($id) {
        $rowset = $this->gateway->select(array('id' => $id));
        $row = $rowset->current();
        return $row;
    }
    
}