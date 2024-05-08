<?php
    include 'configs.php';  // Ensure this path is correct relative to the caller script

    function getDatabaseConnection() {
        global $cfgServer;  // Declare it global to access the configuration

        $link = mysqli_connect($cfgServer['host'], $cfgServer['user'], $cfgServer['password'], $cfgServer['dbname']);
        if (!$link) {
            die('Connection failed: ' . mysqli_connect_error());
        }
        return $link;
    }
?>