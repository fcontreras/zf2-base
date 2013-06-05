<?php

namespace Giniem\Model;

class Users {
    public $id;
    public $username;
    public $password;
    public $email;
    public $first_name;
    public $last_name;
    public $office_phone;
    public $mobile_phone;
    public $company_id;
    public $is_main_contact;
    public $avatar;
    public $gender;
    public $birth_date;
    public $about;
    public $website;
    public $facebook;
    public $twitter;
    public $skypeid;
    public $yahooid;
    
    public function exchangeArray($data) {
        $this->id =  $data['id'];
        $this->username = $data['username'];
        $this->password = $data['password'];
        $this->email = $data['email'];
        $this->first_name = $data['first_name'];
        $this->last_name = $data['last_name'];
        $this->office_phone = $data['office_phone'];
        $this->mobile_phone = $data['mobile_phone'];
        $this->company_id = $data['company_id'];
        $this->is_main_contact = $data['is_main_contact'];
        $this->avatar = $data['avatar'];
        $this->gender = $data['gender'];
        //Parse to date time to be used later
        $dateInfo = \DateTime::createFromFormat('Y-m-d H:i:s', $data['birth_date']);
        $this->birth_date = $dateInfo;
        $this->about = $data['about'];
        $this->website = $data['website'];
        $this->facebook = $data['facebook'];
        $this->twitter = $data['twitter'];
        $this->skypeid = $data['skypeid'];
        $this->yahooid = $data['yahooid'];
        
    }
}