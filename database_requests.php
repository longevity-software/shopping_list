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
        case 'update':
            http_response_code(202);
            update_item_in_database($_POST['database_id'], $_POST['checked'],$_POST['description'],$_POST['quantity'],$_POST['price']);
            break;
        case 'delete':
            // return an accepted response code
            http_response_code(202);
            //delete_item_from_database_using_description($_POST['description']);
            delete_item_from_database_using_id($_POST['database_id']);
            break;
        case 'get':
            // return an accepted response code
            http_response_code(202);
            get_shopping_list_items();
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