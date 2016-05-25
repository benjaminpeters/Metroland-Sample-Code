<!DOCTYPE html>
<html lang= "en-US">
<head>
    <meta charset="UTF-8">
    <title>search.php</title>
</head>
<body>
    <h1>Clothing</h1>
    <?php
        try {
            $fieldName = array("clothingID", "name", "type", "price");
            //get values from form
            
            $srchField = filter_input(INPUT_POST, "srchField");
            $srchValue = filter_input(INPUT_POST, "srchValue");
            
            // don't proceed unless it's a valid field name
            if (in_array($srchField, $fieldName)){
                $field = $srchField;
                //put value inside %% structure
                $value = "%$srchValue%";
                
                $con = new PDO('mysql:host=localhost;dbname=benpetersca_sampledb', "shopit_user", "User123");
                $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
                //$stmt = $con->prepare("SELECT * FROM CasualShirts, DressShirts, TShirtsandPolos, Sweaters, SweatshirtsandSweatpants, CoatsandJackets, BlazersandVests, Shorts, Swim, UnderwearandPajamas WHERE $field LIKE ?");

                $stmt = $con->prepare("SELECT * FROM jcrewTable WHERE $field LIKE ?");
                $stmt->execute(array($value));
                
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                if (empty($result)){
                    print "No matches found";
                } else {
                    foreach($result as $row){
                        foreach ($row as $field=>$value){
                            print "<strong>$field:</strong> $value <br />";
                        } // end field loop
                        print "<br />";
                        
                    } // end row loop
                } // end 'empty results' if 
            } else {
            print "That is not a valid field name";
        } //end if
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        } //end try
    ?>
</body>
</html>