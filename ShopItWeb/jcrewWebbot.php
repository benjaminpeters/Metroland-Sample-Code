<?php

    /*
     * PHP Webbot used for collecting data from www.jcrew.com - by Ben Peters
     *
     */
    
    # Include http library
    include("LIB_parse.php");
    include("LIB_http.php");

    $product_array = array();

    $categories_array = array("Casual Shirts" => "https://www.jcrew.com/ca/mens_category/shirts.jsp?iNextCategory=-1",
                              "Dress Shirts" => "https://www.jcrew.com/ca/mens_category/dressshirts.jsp",
                              "TShirts and Polos" => "https://www.jcrew.com/ca/mens_category/polostees.jsp",
                              "Sweaters" => "https://www.jcrew.com/ca/mens_category/sweaters.jsp",
                              "Sweatshirts and Sweatpants" => "https://www.jcrew.com/ca/mens_category/teesfleece.jsp",
                              "Coats and Jackets" => "https://www.jcrew.com/ca/mens_category/outerwear.jsp?iNextCategory=-1",
                              "Blazers and Vests" => "https://www.jcrew.com/ca/mens_category/sportcoatsandvests.jsp",
                              "Shorts" => "https://www.jcrew.com/ca/mens_category/shortsswim.jsp",
                              "Swim" => "https://www.jcrew.com/ca/mens_category/swim.jsp",
                              "Underwear and Pajamas" => "https://www.jcrew.com/ca/mens_category/underwearpajamas.jsp"
                              );
    
    // MySQL Connection
    
    $con = new PDO('mysql:host=localhost;dbname=dbname', "root", "pwd");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION, PDO::MYSQL_ATTR_USE_BUFFERED_QUERY );
    
    # create the table
        $outPut = $outPut.sqlCreateTable($category_name);
        
        if ( $fetched = $con->query($outPut)){
            $fetched->execute(PDO::FETCH_BOTH);
            //echo "Added table $category_name!";
        }
        else {
            //echo "Failed on table $category_name";
        }
        
    foreach($categories_array as $category_name => $category){
        
        $curr_category = http_get($category, "");
        
        # remove CSS
        $style_removed = remove($curr_category['FILE'], "<style", "</style>");
        
        # remove JS
        $javascript_removed = remove($style_removed, "<script", "</script>");
        
        # find the div containing all the product
        $product_array = parse_array($curr_category['FILE'], "<table", "</table>");

        foreach($product_array as $product){
        //for($xx=1; $xx<count($product_array); $xx++){
            $name = return_between($product, "alt=\"", "\"", EXCL);
            $name =  htmlspecialchars_decode($name);
            
            // convert all html entities
            //$name = html_entity_decode($name);
            
            $price = return_between($product, "CAD ", "</span>", EXCL);
            
            $url = return_between($product, "data-producturl=", " ", EXCL);
            
            //$imageurl = return_between($div_array[$xx], "img src=\"", "?$", EXCL);
            
            $nameNoSpace = str_replace(" ", "", $name);
          
            $noSpaceImageURL = $nameNoSpace.".jpg";
            
            // save the images to a seperate folder
            //grab_image($imageurl, "/Users/jeanpeters/Desktop/Shop App/Images/".$noSpaceImageURL);
            
            //$outPut = $outPut.csvOutPut($name, $category_name,$price,$noSpaceImageURL);
            $outPut = $outPut.sqlInsert($name, $category_name, $price, $noSpaceImageURL, $category_name);
            
            if ( $fetched = $con->query($outPut)){
                $fetched->execute(PDO::FETCH_BOTH);
                //echo "Added product $name";
            }
            else {
                //echo "Failed on product $name";
            }

            $outPut = "";
        }
    }
    
    function sqlCreateTable($table_name){
          /* DROP TABLE IF EXISTS jcrewCasualShirts
           *
           * CREATE TABLE jcrewCasualShirts (
               clothingID int PRIMARY KEY AUTO_INCREMENT,
               name VARCHAR(50),
               type VARCHAR(50),
               price DECIMAL(6,2),
               image VARCHAR(50)
               );
           )
          */
          
        $table_name = str_replace(" ", "", $table_name);

        echo $table_name;
        
          return ("DROP TABLE IF EXISTS jcrewTable;
                  
                  CREATE TABLE jcrewTable (
                    clothingID int PRIMARY KEY AUTO_INCREMENT,
                    name VARCHAR(50),
                    type VARCHAR(50),
                    price DECIMAL(6,2),
                    image VARCHAR(50)
                  );
                  
                  ");
     }
     
     function sqlInsert($name, $type, $price, $image, $table_name){
          /* INSERT INTO jcrewCasualShirts VALUES
           *   (null, 'name', 'type', 'price', 'image');
           */
            
        $table_name = str_replace(" ", "", $table_name);

          
          return ("INSERT INTO jcrewTable VALUES
                         (null, '$name', '$type', '$price', '$image');
                    ");
     }