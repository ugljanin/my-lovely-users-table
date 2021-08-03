<?php

namespace MLUT\PublicArea;

class User
{
    private int $id;
    private string $name, $email, $username, $city, $suite, $street, $company, $phone;

    function __construct($id, $name, $email, $username, $city, $suite, $street, $company, $phone)
    {
        add_filter('my_lovely_users_table_transform_all', [&$this, 'transform_text'], 10, 1);
        if($this->validate_users_id($id))
            $this->id = apply_filters('my_lovely_users_table_transform_all', $id);
        $this->name = apply_filters('my_lovely_users_table_transform_all', $name);
        if($this->validate_users_email($email))
            $this->email = apply_filters('my_lovely_users_table_transform_all', $email);
        $this->username = apply_filters('my_lovely_users_table_transform_all', $username);
        $this->city = apply_filters('my_lovely_users_table_transform_all', $city);
        $this->suite = apply_filters('my_lovely_users_table_transform_all', $suite);
        $this->street = apply_filters('my_lovely_users_table_transform_all', $street);
        $this->company = apply_filters('my_lovely_users_table_transform_all', $company);
        $this->phone = apply_filters('my_lovely_users_table_transform_all', $phone);
    }
    public function transform_text($value)
    {
        return strtolower($value);
    }
    public function validate_users_email($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else
            return true;
    }
    public function validate_users_id($id)
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            return false;
        } else
            return true;
    }
    public function export()
    {
        return array_filter(get_object_vars($this));
    }
}
