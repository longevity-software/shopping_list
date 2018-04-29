<!DOCTYPE html>
<html>
    <head>
        <title>Shopping List</title>
        
        <!-- add angular and my script -->
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.js"></script>
        <script type="text/javascript" src="shopping_list_script.js"></script>

        <!-- add custom style sheet -->
        <link rel="stylesheet" href="shopping_list_styles.css">

        <!-- set to improve mobile ui -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    
    <body ng-app="shopping_list_application" id="shopping_list_body">
    
        <!-- include database functions to initialise the items array  -->
        <?php require_once("database_functions.php") ?>

        <!-- main div -->
        <div ng-controller="shopping_list_controller" id="shopping_list_main_div" ng-init='items=<?php get_shopping_list_items()?>' >
        
            <!-- Add new items to the list div -->
            <div>
            
                <fieldset>
                
                    <!-- add some instructions to user -->
                    <legend align="center">Add new Item</legend>
                
                    <!-- labels buttons and text inputs used for item info entry -->
                    <p align="center">
                        <!-- description label -->
                        Description
                    </p>
                    <p align="center">
                        <!-- description text input -->
                        <input type="text" id="description_input_id" list="auto_complete_list_id" name="description_input" ng-change="check_for_duplicates()" ng-model="add_description" ng-model-instant></input>
                        
                        <datalist id="auto_complete_list_id">
                            <option ng-repeat="item in items" value="{{item.description}}">
                        </datalist>
                    </p>
                    <p align="center">
                        <!-- quantity label -->
                        Quantity
                    </p>
                    <p align="center">
                        <!-- quantity adjustment using buttons -->
                        <button type="button" ng-click="adjust_quantity(false)"> - </button>
                        {{add_quantity}}
                        <button type="button" ng-click="adjust_quantity(true)"> + </button>
                    </p>
                    <p align="center">
                        <!-- price label -->
                        Price
                    </p>
                    <p align="center">
                        <!-- price text input -->
                        <input type="text" name="price_input" ng-model="add_price" ng-model-instant></input>
                    </p>
                    <p align="center">
                        <!-- add item button -->
                        <button type="button" ng-click="add_item()">{{add_or_update_string}}</button>
                    </p>
                    
                </fieldset>
            
            </div>

            <!-- Status bar to show errors and successes -->
            <p align="center" id="status_bar"></p>
            
            <!-- Table to display all the shopping list data -->
            <table style="width: 100%" id="shopping_list_items_table">

                <!-- headings row -->
                <tr>

                    <th align="center">Check</th>
                    <th align="center">Description</th>
                    <th align="center">Quantity</th>
                    <th align="center">Price</th>
                    <th align="center">total</th>

                </tr>

                <!-- data rows populated with items 
                     each row's class is dependent on the items checked status -->
                <tr ng-repeat="item in items" class="list_item_{{item.checked}}">

                    <!-- bind the checkbox checked status to the items checked status -->
                    <td align="center"><input type="checkbox" ng-model="item.checked"></td>
                    <td align="center">{{item.description}}</td>
                    <td align="center">{{item.quantity}}</td>
                    <td align="center">{{item.price}}</td>
                    <td align="center">{{item.quantity * item.price}}</td>

                </tr>

                <!-- total row -->
                <tr>

                    <th align="center"></th>
                    <th align="center"></th>
                    <th align="center"></th>
                    <th align="center">Total</th>
                    <th align="center">{{get_total()}}</th>

                </tr>
            </table>

            <p align="center">
                <!-- button to delete the checked items  -->
                <button type="button" ng-click="delete_checked_items()">Delete checked items</button>
            </p>
        </div>
        
    </body>
</html>