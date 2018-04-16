shopping_list_app = angular.module("shopping_list_application", []);

// shopping list controller containing all shopping list variables and functoions
shopping_list_app.controller("shopping_list_controller", function($scope, $http){

    // contoller level variables which are bound to html elements
    $scope.items = [];
    $scope.add_quantity = 1;
    $scope.add_description = "";

    // name: get_total
    // desc: loops through all items to calculate and return the sum total 
    $scope.get_total = function() {
        var sum_total = 0;
        //
        angular.forEach($scope.items, function(value, key){
            sum_total += value.total;
        });
        //
        return sum_total;
    };
    
    // name: adjust_quantity 
    // desc: increments or decrements the add_quantity based on the passed parameter
    $scope.adjust_quantity = function(increment_not_decrement)
    {
        if(increment_not_decrement)
        {
            // cap increments at 20
            if($scope.add_quantity < 20)
            {
                $scope.add_quantity++;
            }
        }
        else
        {
            // cap decrements at 1
            if($scope.add_quantity > 1)
            {
                $scope.add_quantity--;
            }
        }
    };
    
    // name: add_item
    // desc: generated a http post request to add a new item to the database
    //     : keeps the user updated on the status via the status bar element  
    $scope.add_item = function()
    {
        // get link to status bar element
        var status_bar = document.getElementById("status_bar");
        //
        // generate new item from bound variables
        var new_item = {checked:false, 
                            description:$scope.add_description, 
                            quantity:$scope.add_quantity, 
                            price:$scope.add_price, 
                            total:($scope.add_quantity * $scope.add_price)};
        //
        // generate http post data string
        var post_data = "action=add"+
                            "&checked="+new_item.checked+
                            "&description="+new_item.description+
                            "&quantity="+new_item.quantity+
                            "&price="+new_item.price;
        //
        // generate post request info
        var post_request = {
            method: "POST",
            url: "database_requests.php",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            data: post_data
        };
        //
        // update the status bar
        status_bar.innerText = "Please wait... adding " + new_item.description + " to the list";
        //
        // send the post request 
        $http(post_request).then(function (response){
            // log to console for debug purposes 
            console.log(response);
            //
            // post was successful so add new_item to the array of items
            $scope.items.push(new_item);
            // update the status bar
            status_bar.innerText = "Added " + new_item.description + " to the list";
            //
        }, function(response){
            // log to console for debug purposes 
            console.log(response);
            //
            // update teh status bar
            status_bar.innerText = "Failed to add " + new_item.description + " to the list";
        });
    };
    
    // name: delete_checked_items
    // desc: generated http requests to delete all the 
    //     : checked items from the database
    $scope.delete_checked_items = function()
    {
        // get a link to the status bar element 
        var status_bar = document.getElementById("status_bar");
        //
        // variables to inform the user of progress
        var items_to_delete = 0;
        var items_deleted = 0;
        var items_failed = 0;
        //
        var post_data;
        //
        var post_request;
        //
        // loop through the items array and check if each item is checked
        angular.forEach($scope.items, function(value, key){
            //
            // if this item is checked
            if(value.checked)
            {
                // item is checked for deletion so increment items to delete
                items_to_delete++;
                //
                // update the status bar
                status_bar.innerText = items_deleted + " successful " + items_failed + " failed out of " + items_to_delete + " items"; 
                //
                // generate post data to delete this item
                post_data = "action=delete"+
                            "&description="+value.description;
                //
                // generate post request info 
                post_request = 
                {
                    method: "POST",
                    url: "database_requests.php",
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    data: post_data
                };
                //
                // send the post request 
                $http(post_request).then(function (response){
                    //
                    // success so increment the items deleted
                    items_deleted++;
                    //
                    // update the status bar
                    status_bar.innerText = items_deleted + " successful " + items_failed + " failed out of " + items_to_delete + " items";
                    //
                    }, function(response){
                        //
                        // log to the console for debug purposes
                        console.log(response);
                        //
                        // increment the items failed
                        items_failed++;
                        //
                        // update the status bar
                        status_bar.innerText = items_deleted + " successful " + items_failed + " failed out of " + items_to_delete + " items";
                });
            }
        });
        //
        // send one last post request to update the items array 
        post_data = "action=get";
        //
        post_request = 
        {
            method: "POST",
            url: "database_requests.php",
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            data: post_data
        };
        //
        $http(post_request).then(function(response){
            console.log(response);
            // update the items array with the returned data
            $scope.items = response.data;
        }, function(response){
            console.log(response);
            // update the status bar
            status_bar.innerText = "Failed to refresh list";
        });
    };
});