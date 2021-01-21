<?php

return array(

    'Global' => array(
        array(
            'permission' => 'superuser',
            'label'      => 'Super User',
            'note'       => 'Determines whether the user has full access to all aspects of the admin. This setting overrides any more specific permissions throughout the system. ',
            'display'    => true,
        ),
    ),

    'Admin' => array(
        array(
            'permission' => 'admin',
            'label'      => '',
            'note'       => 'Determines whether the user has access to most aspects of the admin. ',
            'display'    => true,
        ),
        array(
            'permission' => 'admin.api_key',
            'label'      => 'Create API Key',
            'note'       => 'Determines whether the user can access the API via API key.',
            'display'    => false,
        ),
    ),

    'Users' => array(
        array(
            'permission' => 'users.view',
            'label'      => 'View ',
            'note'       => '',
            'display'    => true,
        ),
        array(
            'permission' => 'users.create',
            'label'      => 'Create Users',
            'note'       => '',
            'display'    => true,
        ),
        array(
            'permission' => 'users.edit',
            'label'      => 'Edit Users',
            'note'       => '',
            'display'    => true,
        ),
        array(
            'permission' => 'users.delete',
            'label'      => 'Delete Users',
            'note'       => '',
            'display'    => true,
        ),

    ),
    'Location' => array(
        array(
            'permission' => 'location.view',
            'label'      => 'View ',
            'note'       => '',
            'display'    => true,
        ),
        array(
            'permission' => 'location.create',
            'label'      => 'Create ',
            'note'       => '',
            'display'    => true,  
        ),
        array(
            'permission' => 'location.edit',
            'label'      => 'Edit ',
            'note'       => '',
            'display'    => true,  
        ),
    ),
    'Self' => array(
        array(
            'permission' => 'self.two_factor',
            'label'      => 'Two-Factor Authentication',
            'note'       => 'The user may disable/enable two-factor authentication themselves if two-factor is enabled and set to selective.',
            'display'    => true,
        ),

    ),

);
