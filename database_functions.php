<?php 

    // name: get_shopping_list_items
    // desc: retrieves items from database and echo's 
    //     : them in a format which can be used to 
    //     : initialise and angular array. 
    function get_shopping_list_items()
    {
        $dbHost = 'fdb19.atspace.me';
        $dbUsername = '2590993_testdb';
        $dbPassword = '2590993_TestDb';
        $dbDatabase = '2590993_testdb';
        //
        // array to hold the database contents 
        $database_contents_array = array();
        //
        // get database connection
        $mysqlidb = mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbDatabase);
        //
        // continue if there was no error connecting to the database
        if(!mysqli_connect_errno($mysqlidb))
        {
            // get all items in the shopping list
            $result = mysqli_query($mysqlidb, "SELECT * FROM shopping_list");
            //
            if($result)
            {
                // for all rows populate the string with the row data
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                    // calculate the line total to be used as part of the output string
                    $line_total = $row['quantity'] * $row['last_price'];
                    //
                    // add info from this row to the database contents array
                    $database_contents_array[] = array("checked" => boolval($row['checked']),
                                                        "description" => $row['description'],
                                                        "quantity" => $row['quantity'],
                                                        "price" => $row['last_price'],
                                                        "total" => $line_total);
                }
                // 
            }
        }
        //
        // close the database 
        mysqli_close($mysqlidb);
        //
        // echo the database contents array 
        echo json_encode($database_contents_array);
    }

    // name: add_item_to_database
    // desc: adds a new item to the database. 
    function add_item_to_database($checked, $description, $quantity, $price)
    {
        $dbHost = 'fdb19.atspace.me';
        $dbUsername = '2590993_testdb';
        $dbPassword = '2590993_TestDb';
        $dbDatabase = '2590993_testdb';
        //
        $output_string = "failure";
        //
        // check that checked is a boolean
        $checked_bool = filter_var($checked, FILTER_VALIDATE_BOOLEAN);
        //
        // get connetcion to the database            
        $mysqlidb = mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbDatabase);
        //
        if(mysqli_connect_errno($mysqlidb))
        {
            // failed to connect
            $output_string = "Failed to connect to db: " .mysqli_connect_error();
        }
        else
        {
            // check type of parameters
            if(is_bool($checked_bool) && is_string($description) && is_numeric($quantity) && is_numeric($price))
            {
                // escape special characters from the description string
                mysqli_real_escape_string($mysqlidb, $description);
                //
                // create a prepared statement 
                if($prep_statement = $mysqlidb->prepare("INSERT INTO shopping_list (checked, description, quantity, last_price) VALUES(?,?,?,?)"))
                {
                    // convert checked bool to an integer value to store it in the database                
                    $checked_bool_int_val = intval($checked_bool);
                    //
                    // bind the parameters to the prepared statement
                    $prep_statement->bind_param('isii', $checked_bool_int_val, $description, $quantity, $price);
                    //
                    // execute the query
                    if($prep_statement->execute())
                    {
                        $output_string = "success";
                    }
                }
            }
        }
        //
        // close the database 
        mysqli_close($mysqlidb);
        //
        // echo the output string
        echo $output_string;
    }
    
    // name: delete_item_from_database_using_description
    // desc: deletes an item to the database using the description. 
    function delete_item_from_database_using_description($description)
    {
        $dbHost = 'fdb19.atspace.me';
        $dbUsername = '2590993_testdb';
        $dbPassword = '2590993_TestDb';
        $dbDatabase = '2590993_testdb';
        //
        $output_string = "";
        //
        // get a connection to the database 
        $mysqlidb = mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbDatabase);
            
        if(mysqli_connect_errno($mysqlidb))
        {
            $output_string = "Failed to connect to db: " .mysqli_connect_error();
        }
        else{
            //
            // delete the items from the database
            $result = mysqli_query($mysqlidb,"DELETE FROM shopping_list WHERE description = '$description'");
            //
            // set the output string as the result of the query 
            if($result)
            {
                $output_string = "success";
            }
            else
            {
                $output_string = "failure";
            }
            //
        }
        //
        // close the database
        mysqli_close($mysqlidb);
        //
        // echo back the ouput string 
        echo $output_string;
    }
?>