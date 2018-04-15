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
            add_item_to_database($_POST['checked'],$_POST['description'],$_POST['quantity'],$_POST['price']);
            break;
        case 'delete':
            delete_item_from_database_using_description($_POST['description']);
            break;
        default:
            
            break;
    }
}

?>