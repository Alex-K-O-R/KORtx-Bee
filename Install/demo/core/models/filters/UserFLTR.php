<?php
namespace app\filters\models;

use app\filters\models\GeneralFilterableModel;
use app\filters\FilterModes;
use app\filters\ModelFilterDescription;
use app\dba\UserDBA;

class simple_UserFLTR extends GeneralFilterableModel{
    protected function LoadFilterFields()
    {
        return
            array(
                // AND
                array(// OR
                    'd_name'=> new ModelFilterDescription( // Name of database table's field to be searched within
                        'user_name', // Key is name of GET-parameter to be used during search
                        true // Flag of dialect-depending field
                    )// OR
                    ,'d_surname'=> new ModelFilterDescription( // Name of database table's field to be searched within
                        'user_name', // Key is name of GET-parameter to be used during search
                        true // Flag of dialect-depending field
                    )
                )// AND
                ,array(
                    'd_general_info'=> new ModelFilterDescription(
                        'about_info',
                        true
                    )// OR
                    ,'d_additional_info'=> new ModelFilterDescription(
                            'about_info',
                            true
                    )
                )// AND
                ,array(
                    'd_job'=> new ModelFilterDescription(
                        'profession',
                        true
                    )
                )// AND
                ,array(
                    'activated'=> new ModelFilterDescription(
                        'activated',
                        false,
                        FilterModes::EQUALS
                    )
                )
            );
    }
}


class user_control_FLTR extends GeneralFilterableModel{
    protected function LoadFilterFields()
    {
        return
            array(
                // AND
                array(// OR
                    'd_name'=> new ModelFilterDescription(
                        'name',
                        true
                    )// OR
                ,'d_surname'=> new ModelFilterDescription(
                    'name',
                    true
                )
                )// AND
            ,array(
                'login'=> new ModelFilterDescription(
                    'login',
                    false
                )
            ) // AND
            ,array( // This block illustrates another usage possiblility and is needed to remove Administrators from the result
                'is_admin'=> new ModelFilterDescription(
                    null,
                    false,
                    FilterModes::NOT_EQUALS,
                    true
                )
            )
            );
    }
}

?>
