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
        // string which will hold the contents as an array decleration
        $database_contents_as_array_decleration = "";
        //
        // get database connection
        $mysqlidb = mysqli_connect($dbHost,$dbUsername,$dbPassword,$dbDatabase);
        //
        if(mysqli_connect_errno($mysqlidb))
        {
            // error getting connection so set output string as empty array
            $database_contents_as_array_decleration = "items = []";
        }
        else
        {
            // get all items in the shopping list
            $result = mysqli_query($mysqlidb, "SELECT * FROM shopping_list");
            //
            if($result)
            {
                // there are results so start the string
                $database_contents_as_array_decleration = "items = [";
                //
                // for all rows populate the string with the row data
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
                {
                    // calculate the line total to be used as part of the output string
                    $line_total = $row['quantity'] * $row['last_price'];
                    //
                    // add info from this row to the output string
                    $database_contents_as_array_decleration = $database_contents_as_array_decleration . 
                                            "{ " .
                                            "checked:" . (boolval($row['checked']) ? 'true' : 'false') . ", " . 
                                            "description:'" . $row['description'] . "', " .
                                            "quantity:" . strval($row['quantity']) . ", " .
                                            "price:" . strval($row['last_price']) . ", " .
                                            "total:" . strval($line_total) .
                                            " },";
                }
                //
                // remove the last comma from the output string 
                $database_contents_as_array_decleration = rtrim($database_contents_as_array_decleration, ",");
                //
                // add the closing square bracket to the output string
                $database_contents_as_array_decleration = $database_contents_as_array_decleration . "]";   
            }
            else
            {
                // no results so set output string as an empty array
                $database_contents_as_array_decleration = "items = []";
            }
        }
        //
        // close the database 
        mysqli_close($mysqlidb);
        //
        // echo the output string 
        echo json_encode($database_contents_as_array_decleration);
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
        $output_string = "";
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
            // insert the item into the database
            $result = mysqli_query($mysqlidb,"INSERT INTO shopping_list (checked, description, quantity, last_price) VALUES ('$checked', '$description', '$quantity', '$price')");
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