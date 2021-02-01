<?php
    /*foreach (getallheaders() as $name => $value) {
        echo "$name: $value\n";
    }*/

    //var_dump($_REQUEST);
    //print_r($_REQUEST."<br/>");
    foreach ($_POST as $key => $val) {
        echo($key . "=>" . $val . "<br/>");
    }

    use IMSGlobal\LTI\ToolProvider;

    require_once('vendor/autoload.php');

    class ImsToolProvider extends ToolProvider\ToolProvider {
        function onLaunch() {        
            print_r("SpringISD Intergration Testing<br/>");
            // Insert code here to handle incoming connections - use the user
            // and resourceLink properties of the class
            // to access the current user and resource link.
        }

        function onError() {
            print_r("There was an error with the request !!<br/>");
        }
    }

    // Cancel any existing session
    session_start();
    $_SESSION = array();
    session_destroy();
    session_start();

    $dsn = "mysql:host=localhost;dbname=lonestaar";
    $user = "mhl397";
    $passwd = "Developer2!";

    $db = new PDO($dsn, $user, $passwd);  // Database constants not defined here
    $data_connector = ToolProvider\DataConnector\DataConnector::getDataConnector("lti", $db);
    $tool = new ImsToolProvider($data_connector);
    //$tool->setParameterConstraint('user_id', true, 50);
    //$tool->setParameterConstraint('roles');
    $tool->setParameterConstraint('user_id');
    $tool->setParameterConstraint('user_name');
    ini_set('memory_limit', '-1');
    $tool->handleRequest();
    
?>