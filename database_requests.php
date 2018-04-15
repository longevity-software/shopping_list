<?php
//
// include the database_functions file as this is where 
// the functions which perform the actions are defined
require_once("database_functions.php");
//
// check if an action is set
if(isset($_POST['action']))
{
    // call the appropriate database function for the posted action
    switch($_POST['action'])
    {
        case 'add':
            // return an accepted response code
            http_response_code(202);
            add_item_to_database($_POST['checked'],$_POST['description'],$_POST['quantity'],$_POST['price']);
            break;
        case 'delete':
            // return an accepted response code
            http_response_code(202);
            delete_item_from_database_using_description($_POST['description']);
            break;
        default:
            // action not recognised so return a bad request response code
            http_response_code(400);
            break;
    }
}
else
{
    // return a method not allowed response code
    http_response_code(405);
}

?>