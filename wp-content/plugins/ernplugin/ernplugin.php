<?php
/*
 * Plugin Name: Plugin ERN
 * Description: Plugin de l'ern
 * Author: Riad
 * Version: 1.0.0
 * */

require_once plugin_dir_path(__FILE__) . "/service/Ern_Database_Service.php";

class Ern
{
    public function __construct()
    {
        register_activation_hook(__FILE__, array('Ern_Database_Service', 'create_db'));

    }
}

new Ern();