
\nfunction psc_admin_menu() {
\n    add_menu_page(
\n        'Property Syncronisation Checker', //page title
\n        'Property Sync Checker', //menu title
\n        'manage_options', //capability
\n        'property-sync-checker', //page slug 
\n        'psc_admin_page_content', //Callback to print html
\n        'dashicons-update-alt', //icon
\n        3
\n    );
\n    add_submenu_page('property-sync-checker', 'Property Syncronisation Checker', 'Syncronisation Checker', 'manage_options', 'property-sync-checker' );
\n}
\nadd_action( 'admin_menu', 'psc_admin_menu' );
\n
\n
\n//Admin page html callback
\nfunction psc_admin_page_content() {
\n    //FEEDSYNC XML
\n    $urlXmlResidential_current = 'https://feedsyncppg.housedigital.com.au/?action=do_output&type=residential&status=current';
\n    $urlXmlResidential_sold = 'https://feedsyncppg.housedigital.com.au/?action=do_output&type=residential&status=sold&days_back=60';
\n    $urlXmlLand_current = 'https://feedsyncppg.housedigital.com.au/?action=do_output&type=land&status=current';
\n    $urlXmlLand_sold = 'https://feedsyncppg.housedigital.com.au/?action=do_output&type=land&status=sold&days_back=60';
\n    $urlXmlRental_current = 'https://feedsyncppg.housedigital.com.au/?action=do_output&type=rental&status=current';
\n    $urlXmlRental_leased = 'https://feedsyncppg.housedigital.com.au/?action=do_output&type=rental&status=leased&days_back=30';
\n
\n    //xml residential current
\n    $getfileXmlResidential_current = wp_remote_get( $urlXmlResidential_current );
\n    $getbodyXmlResidential_current = wp_remote_retrieve_body( $getfileXmlResidential_current );
\n    $getresultXmlResidential_current = simplexml_load_string($getbodyXmlResidential_current);
\n    $getarrayXmlResidential_current = (array)$getresultXmlResidential_current;
\n    $getcountXmlResidential_current = 0;
\n    $getIDXmlResidential = [];
\n    $getItemsXmlResidential = [];
\n    $getAllXmlResidential = [];
\n    $getCountAllXmlResidential = 0;
\n    $getmodDateXmlResidentialALL = [];
\n    $getCountAllXmlCommercial = 0;
\n    $getChckStatusitemXmlResidentialALL = [];
\n    $getChckStatusXmlResidentialALL = [];
\n    //COMPOSITE
\n    $AllCompositeResidentialitems = [];
\n    $AllCompositeResidential = [];
\n    $AllCompositeLanditems = [];
\n    $AllCompositeLand = [];
\n    $AllCompositeRentalitems = [];
\n    $AllCompositeRental = [];
\n    //Attachment
\n    $getAllXmlResidentialAttachmentItem_current = [];
\n    $getAllXmlResidentialAttachment_current = [];
\n    
\n    foreach($getarrayXmlResidential_current['residential'] as $items){
\n        $array = (array)$items;
\n        $photos = (array)$array['objects'];
\n
\n        $imageCount = 0;
\n        $imagesDataItem = [];
\n        $imagesDataItemALL = [];
\n
\n        foreach($photos['img'] as $images){
\n            $imagesData = (array)$images;
\n
\n            if($imagesData['@attributes']['url']){
\n                $imagesDataItem = $imagesData['@attributes']['url'];
\n
\n                array_push($imagesDataItemALL, $imagesDataItem);
\n
\n                $imageCount++;
\n            }
\n        }
\n
\n        $getAllXmlResidentialAttachmentItem_current['ID'] = $array['uniqueID'];
\n        $getAllXmlResidentialAttachmentItem_current['attach'] = $imagesDataItemALL;
\n        array_push($getAllXmlResidentialAttachment_current, $getAllXmlResidentialAttachmentItem_current);
\n
\n        array_push($getIDXmlResidential, $array['uniqueID']);
\n        array_push($getmodDateXmlResidentialALL, $array['@attributes']['modTime']);
\n        
\n        $addressDetails = (array)$array['address'];
\n        $addressStreetNum = $addressDetails['streetNumber'];
\n        $addressStreet = $addressDetails['street'];
\n        $addressSuburb = $addressDetails['suburb'];
\n        $addressState = $addressDetails['state'];
\n        $addressPostcode = $addressDetails['postcode'];
\n        
\n        $getItemsXmlResidential['propID'] = $array['uniqueID'];
\n        $getItemsXmlResidential['status'] = 'current';
\n        $getItemsXmlResidential['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        array_push($getAllXmlResidential, $getItemsXmlResidential);
\n
\n
\n        $AllCompositeResidentialitems['uniq_id'] = $array['uniqueID'];
\n        $AllCompositeResidentialitems['status'] = 'current';
\n        $AllCompositeResidentialitems['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        array_push($AllCompositeResidential, $AllCompositeResidentialitems);
\n
\n        $getChckStatusitemXmlResidentialALL['id'] = $array['uniqueID'];
\n        $getChckStatusitemXmlResidentialALL['status'] = 'current';
\n        $getChckStatusitemXmlResidentialALL['image_count'] = $imageCount;
\n
\n        array_push($getChckStatusXmlResidentialALL, $getChckStatusitemXmlResidentialALL);
\n        
\n        $getcountXmlResidential_current++;
\n    }
\n    //xml residential sold
\n    $getfileXmlResidential_sold = wp_remote_get( $urlXmlResidential_sold );
\n    $getbodyXmlResidential_sold = wp_remote_retrieve_body( $getfileXmlResidential_sold );
\n    $getresultXmlResidential_sold = simplexml_load_string($getbodyXmlResidential_sold);
\n    $getarrayXmlResidential_sold = (array)$getresultXmlResidential_sold;
\n    $getcountXmlResidential_sold = 0;
\n    $getmodDateXmlResidential_sold = [];
\n    
\n    foreach($getarrayXmlResidential_sold['residential'] as $items){
\n        $array = (array)$items;
\n        $photos = (array)$array['objects'];
\n
\n        $imageCount = 0;
\n
\n        foreach($photos['img'] as $images){
\n            $imagesData = (array)$images;
\n
\n            if($imagesData['@attributes']['url']){
\n                $imageCount++;
\n            }
\n        }
\n
\n        array_push($getIDXmlResidential, $array['uniqueID']);
\n        array_push($getmodDateXmlResidential_sold, $array['@attributes']['modTime']);
\n        array_push($getmodDateXmlResidentialALL, $array['@attributes']['modTime']);
\n        
\n        $addressDetails = (array)$array['address'];
\n        $addressStreetNum = $addressDetails['streetNumber'];
\n        $addressStreet = $addressDetails['street'];
\n        $addressSuburb = $addressDetails['suburb'];
\n        $addressState = $addressDetails['state'];
\n        $addressPostcode = $addressDetails['postcode'];
\n        
\n        $getItemsXmlResidential['propID'] = $array['uniqueID'];
\n        $getItemsXmlResidential['status'] = 'sold';
\n        $getItemsXmlResidential['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        array_push($getAllXmlResidential, $getItemsXmlResidential);
\n
\n        $AllCompositeResidentialitems['uniq_id'] = $array['uniqueID'];
\n        $AllCompositeResidentialitems['status'] = 'sold';
\n        $AllCompositeResidentialitems['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        array_push($AllCompositeResidential, $AllCompositeResidentialitems);
\n
\n        $getChckStatusitemXmlResidentialALL['id'] = $array['uniqueID'];
\n        $getChckStatusitemXmlResidentialALL['status'] = 'sold';
\n        $getChckStatusitemXmlResidentialALL['image_count'] = $imageCount;
\n
\n        array_push($getChckStatusXmlResidentialALL, $getChckStatusitemXmlResidentialALL);
\n        
\n        $getcountXmlResidential_sold++;
\n    }
\n    //get residential count, all result XML
\n    foreach($getAllXmlResidential as $getAllXmlResidential_res){
\n        $getCountAllXmlResidential++;
\n    }
\n
\n    //xml land current
\n    $getfileXmlLand_current = wp_remote_get( $urlXmlLand_current );
\n    $getbodyXmlLand_current = wp_remote_retrieve_body( $getfileXmlLand_current );
\n    $getresultXmlLand_current = simplexml_load_string($getbodyXmlLand_current);
\n    $getarrayXmlLand_current = (array)$getresultXmlLand_current;
\n    $getcountXmlLand_current = 0;
\n    $getIDXmlLand = [];
\n    $getItemsXmlLand = [];
\n    $getAllXmlLand = [];
\n    $getCountAllXmlLand = 0;
\n    $getmodDateXmlLandALL = [];
\n    $getChckStatusitemXmlLandALL = [];
\n    $getChckStatusXmlLandALL = [];
\n
\n    foreach($getarrayXmlLand_current['land'] as $items){
\n        $array = (array)$items;
\n        $photos = (array)$array['objects'];
\n
\n        $imageCount = 0;
\n
\n        foreach($photos['img'] as $images){
\n            $imagesData = (array)$images;
\n
\n            if($imagesData['@attributes']['url']){
\n                $imageCount++;
\n            }
\n        }
\n
\n        array_push($getIDXmlLand, $array['uniqueID']);
\n        array_push($getmodDateXmlLandALL, $array['@attributes']['modTime']);
\n
\n        $addressDetails = (array)$array['address'];
\n        $addressStreetNum = $addressDetails['streetNumber'];
\n        $addressStreet = $addressDetails['street'];
\n        $addressSuburb = $addressDetails['suburb'];
\n        $addressState = $addressDetails['state'];
\n        $addressPostcode = $addressDetails['postcode'];
\n        
\n        $getItemsXmlLand['propID'] = $array['uniqueID'];
\n        $getItemsXmlLand['status'] = 'current';
\n        $getItemsXmlLand['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        array_push($getAllXmlLand, $getItemsXmlLand);
\n
\n        $AllCompositeLanditems['uniq_id'] = $array['uniqueID'];
\n        $AllCompositeLanditems['status'] = 'current';
\n        $AllCompositeLanditems['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        array_push($AllCompositeLand, $AllCompositeLanditems);
\n
\n        $getChckStatusitemXmlLandALL['id'] = $array['uniqueID'];
\n        $getChckStatusitemXmlLandALL['status'] = 'current';
\n        $getChckStatusitemXmlLandALL['image_count'] = $imageCount;
\n
\n        array_push($getChckStatusXmlLandALL, $getChckStatusitemXmlLandALL);
\n
\n        $getcountXmlLand_current++;
\n    }
\n    //xml land sold
\n    $getfileXmlLand_sold = wp_remote_get( $urlXmlLand_sold );
\n    $getbodyXmlLand_sold = wp_remote_retrieve_body( $getfileXmlLand_sold );
\n    $getresultXmlLand_sold = simplexml_load_string($getbodyXmlLand_sold);
\n    $getarrayXmlLand_sold = (array)$getresultXmlLand_sold;
\n    $getcountXmlLand_sold = 0;
\n    $getmodDateXmlLand_sold = [];
\n
\n    foreach($getarrayXmlLand_sold['land'] as $items){
\n        $array = (array)$items;
\n        $photos = (array)$array['objects'];
\n
\n        $imageCount = 0;
\n
\n        foreach($photos['img'] as $images){
\n            $imagesData = (array)$images;
\n
\n            if($imagesData['@attributes']['url']){
\n                $imageCount++;
\n            }
\n        }
\n
\n        array_push($getmodDateXmlLand_sold, $array['@attributes']['modTime']);
\n        array_push($getmodDateXmlLandALL, $array['@attributes']['modTime']);
\n        array_push($getIDXmlLand, $array['uniqueID']);
\n
\n        $addressDetails = (array)$array['address'];
\n        $addressStreetNum = $addressDetails['streetNumber'];
\n        $addressStreet = $addressDetails['street'];
\n        $addressSuburb = $addressDetails['suburb'];
\n        $addressState = $addressDetails['state'];
\n        $addressPostcode = $addressDetails['postcode'];
\n        
\n        $getItemsXmlLand['propID'] = $array['uniqueID'];
\n        $getItemsXmlLand['status'] = 'sold';
\n        $getItemsXmlLand['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        array_push($getAllXmlLand, $getItemsXmlLand);
\n
\n        $AllCompositeLanditems['uniq_id'] = $array['uniqueID'];
\n        $AllCompositeLanditems['status'] = 'sold';
\n        $AllCompositeLanditems['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        array_push($AllCompositeLand, $AllCompositeLanditems);
\n
\n        $getChckStatusitemXmlLandALL['id'] = $array['uniqueID'];
\n        $getChckStatusitemXmlLandALL['status'] = 'sold';
\n        $getChckStatusitemXmlLandALL['image_count'] = $imageCount;
\n
\n        array_push($getChckStatusXmlLandALL, $getChckStatusitemXmlLandALL);
\n
\n        $getcountXmlLand_sold++;
\n    }
\n
\n    //get land count, all result XML
\n    foreach($getAllXmlLand as $getAllXmlLand_res){
\n        $getCountAllXmlLand++;
\n    }
\n
\n    //xml rental current
\n    $getfileXmlRental_current = wp_remote_get( $urlXmlRental_current );
\n    $getbodyXmlRental_current = wp_remote_retrieve_body( $getfileXmlRental_current );
\n    $getresultXmlRental_current = simplexml_load_string($getbodyXmlRental_current);
\n    $getarrayXmlRental_current = (array)$getresultXmlRental_current;
\n    $getcountXmlRental_current = 0;
\n    $getIDXmlRental = [];
\n    $getItemsXmlRental = [];
\n    $getAllXmlRental = [];
\n    $getCountAllXmlRental = 0;
\n    $getmodDateXmlRentalALL = [];
\n    $getChckStatusitemXmlRentalALL = [];
\n    $getChckStatusXmlRentalALL = [];
\n
\n    foreach($getarrayXmlRental_current['rental'] as $items){
\n        $array = (array)$items;
\n        $photos = (array)$array['objects'];
\n
\n        $imageCount = 0;
\n
\n        foreach($photos['img'] as $images){
\n            $imagesData = (array)$images;
\n
\n            if($imagesData['@attributes']['url']){
\n                $imageCount++;
\n            }
\n        }
\n
\n        array_push($getIDXmlRental, $array['uniqueID']);
\n        array_push($getmodDateXmlRentalALL, $array['@attributes']['modTime']);
\n
\n        $addressDetails = (array)$array['address'];
\n        $addressStreetNum = $addressDetails['streetNumber'];
\n        $addressStreet = $addressDetails['street'];
\n        $addressSuburb = $addressDetails['suburb'];
\n        $addressState = $addressDetails['state'];
\n        $addressPostcode = $addressDetails['postcode'];
\n        
\n        $getItemsXmlRental['propID'] = $array['uniqueID'];
\n        $getItemsXmlRental['status'] = 'current';
\n        $getItemsXmlRental['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        array_push($getAllXmlRental, $getItemsXmlRental);
\n
\n        $AllCompositeRentalitems['uniq_id'] = $array['uniqueID'];
\n        $AllCompositeRentalitems['status'] = 'current';
\n        $AllCompositeRentalitems['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        array_push($AllCompositeRental, $AllCompositeRentalitems);
\n
\n        $getChckStatusitemXmlRentalALL['id'] = $array['uniqueID'];
\n        $getChckStatusitemXmlRentalALL['status'] = 'current';
\n        $getChckStatusitemXmlRentalALL['image_count'] = $imageCount;
\n
\n        array_push($getChckStatusXmlRentalALL, $getChckStatusitemXmlRentalALL);
\n
\n        $getcountXmlRental_current++;
\n    }
\n    //xml rental leased
\n    $getfileXmlRental_leased = wp_remote_get( $urlXmlRental_leased );
\n    $getbodyXmlRental_leased = wp_remote_retrieve_body( $getfileXmlRental_leased );
\n    $getresultXmlRental_leased = simplexml_load_string($getbodyXmlRental_leased);
\n    $getarrayXmlRental_leased = (array)$getresultXmlRental_leased;
\n    $getcountXmlRental_leased = 0;
\n    $getmodDateXmlRental_leased = [];
\n    $getcountXmlCommercial = 0;
\n
\n    foreach($getarrayXmlRental_leased['rental'] as $items){
\n        $array = (array)$items;
\n        array_push($getmodDateXmlRental_leased, $array['@attributes']['modTime']);
\n        array_push($getmodDateXmlRentalALL, $array['@attributes']['modTime']);
\n
\n        array_push($getIDXmlRental, $array['uniqueID']);
\n
\n        $addressDetails = (array)$array['address'];
\n        $addressStreetNum = $addressDetails['streetNumber'];
\n        $addressStreet = $addressDetails['street'];
\n        $addressSuburb = $addressDetails['suburb'];
\n        $addressState = $addressDetails['state'];
\n        $addressPostcode = $addressDetails['postcode'];
\n        
\n        // $getItemsXmlRental['propID'] = $array['uniqueID'];
\n        // $getItemsXmlRental['status'] = 'leased';
\n        // $getItemsXmlRental['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        // array_push($getAllXmlRental, $getItemsXmlRental);
\n
\n        // $AllCompositeRentalitems['uniq_id'] = $array['uniqueID'];
\n        // $AllCompositeRentalitems['status'] = 'leased';
\n        // $AllCompositeRentalitems['address'] = $addressStreetNum.' '.$addressStreet.', '.$addressSuburb.' '.$addressState.' '.$addressPostcode;
\n        // array_push($AllCompositeRental, $AllCompositeRentalitems);
\n
\n        $getChckStatusitemXmlRentalALL['id'] = $array['uniqueID'];
\n        $getChckStatusitemXmlRentalALL['status'] = 'leased';
\n
\n        array_push($getChckStatusXmlRentalALL, $getChckStatusitemXmlRentalALL);
\n
\n        $getcountXmlRental_leased++;
\n    }
\n    //get rental count, all result XML
\n    foreach($getAllXmlRental as $getAllXmlRental_res){
\n        $getCountAllXmlRental++;
\n    }
\n
\n    //FEEDSYNC DB
\n    $servername = 'bne-cp060.web-host.com.au';
\n    $username = 'feedsyncppg_checker';
\n    $password = 'WQx^bgRMxJJY';
\n
\n    $feedsync_db_error = null;
\n    // Create connection
\n    $conn = @new mysqli($servername, $username, $password, 'feedsyncppg_feedsyncppg');
\n
\n    // Check connection
\n    if ($conn->connect_error) {
\n        $feedsync_db_error = $conn->connect_error;
\n        $conn = null;
\n    }
\n
\n    if ($feedsync_db_error) {
\n        echo '<div class="notice notice-error"><p>FeedSync DB connection failed: ' . esc_html($feedsync_db_error) . '</p></div>';
\n    }
\n
\n    $sql_residential = "SELECT * FROM feedsync WHERE type = 'residential' ";
\n    $sql_rental = "SELECT * FROM feedsync WHERE type = 'rental' ";
\n    $sql_land = "SELECT * FROM feedsync WHERE type = 'land' ";
\n    $sql_commercial = "SELECT * FROM feedsync WHERE type = 'commercial' ";
\n
\n    if ($conn) {
\n        $residential_results = $conn->query($sql_residential);
\n        $rental_results = $conn->query($sql_rental);
\n        $land_results = $conn->query($sql_land);
\n        $commercial_results = $conn->query($sql_commercial);
\n    } else {
\n        $residential_results = [];
\n        $rental_results = [];
\n        $land_results = [];
\n        $commercial_results = [];
\n    }
\n    // $tables = $results->fetch_all();
\n    $residentialCount_current = 0;
\n    $residentialCount_sold = 0;
\n    $rentalCount_current = 0;
\n    $rentalCount_leased = 0;
\n    $landCount_current = 0;
\n    $landCount_sold = 0;
\n    $commercialCount = 0;
\n    $residentialCountAll = 0;
\n    $landCountAll = 0;
\n    $rentalCountAll = 0;
\n    $commercialCountAll = 0;
\n    $feedResidentialID = [];
\n    $feedRentalID = [];
\n    $feedLandID = [];
\n    $feedCommercialID = [];
\n    $getChckStatusitemDBResidentialALL = [];
\n    $getChckStatusitemDBLandALL = [];
\n    $getChckStatusitemDBRentalALL = [];
\n    $getChckStatusDBResidentialALL = [];
\n    $getChckStatusDBLandALL = [];
\n    $getChckStatusDBRentalALL = [];
\n    
\n    //Commercial FeedSync
\n    // foreach($commercial_results as $commercial_result){
\n    //     if($commercial_result){
\n    //         $commercialCount = 'Record found';
\n    //     }else{
\n    //         $commercialCount = 'No record found';
\n    //     }
\n    // }
\n
\n    //Residential FeedSync
\n    foreach($residential_results as $residential_result){
\n
\n        if($residential_result['status'] == 'current'){
\n            $residentialCount_current++;
\n        }
\n
\n        if($residential_result['status'] == 'sold'){
\n
\n            $residentialModDate = str_replace(' ', '-', $residential_result['mod_date']);
\n            if(in_array($residentialModDate, $getmodDateXmlResidential_sold)){
\n                $residentialCount_sold++;
\n            }
\n
\n        }
\n
\n        if($residential_result['status'] == 'current' || $residential_result['status'] == 'sold'){
\n
\n            $residentialModDate = str_replace(' ', '-', $residential_result['mod_date']);
\n            if(in_array($residentialModDate, $getmodDateXmlResidentialALL)){
\n                array_push($feedResidentialID, $residential_result['unique_id']);
\n
\n                $getChckStatusitemDBResidentialALL['id'] = $residential_result['unique_id'];
\n                $getChckStatusitemDBResidentialALL['status'] = $residential_result['status'];
\n
\n                array_push($getChckStatusDBResidentialALL, $getChckStatusitemDBResidentialALL);
\n
\n                $AllCompositeResidentialitems['uniq_id'] = $residential_result['unique_id'];
\n                $AllCompositeResidentialitems['status'] = $residential_result['status'];
\n                $AllCompositeResidentialitems['address'] = $residential_result['address'];
\n                array_push($AllCompositeResidential, $AllCompositeResidentialitems);
\n
\n                $residentialCountAll++;
\n            }
\n        }
\n    }
\n    //Rental FeedSync
\n    foreach($rental_results as $rental_result){
\n
\n        if($rental_result['status'] == 'current'){
\n            $rentalCount_current++;
\n        }
\n
\n        if($rental_result['status'] == 'leased'){
\n
\n            $rentalModDate = str_replace(' ', '-', $rental_result['mod_date']);
\n            if(in_array($rentalModDate, $getmodDateXmlRental_leased)){
\n                $rentalCount_leased++;
\n            }
\n        }
\n
\n        if($rental_result['status'] == 'current'){
\n
\n            $rentalModDate = str_replace(' ', '-', $rental_result['mod_date']);
\n            if(in_array($rentalModDate, $getmodDateXmlRentalALL)){
\n                array_push($feedRentalID, $rental_result['unique_id']);
\n
\n                $getChckStatusitemDBRentalALL['id'] = $rental_result['unique_id'];
\n                $getChckStatusitemDBRentalALL['status'] = $rental_result['status'];
\n
\n                array_push($getChckStatusDBRentalALL, $getChckStatusitemDBRentalALL);
\n
\n                $AllCompositeRentalitems['uniq_id'] = $rental_result['unique_id'];
\n                $AllCompositeRentalitems['status'] = $rental_result['status'];
\n                $AllCompositeRentalitems['address'] = $rental_result['address'];
\n                array_push($AllCompositeRental, $AllCompositeRentalitems);
\n
\n                $rentalCountAll++;
\n            }
\n        }
\n    }
\n    //Land FeedSync
\n    foreach($land_results as $land_result){
\n
\n        if($land_result['status'] == 'current'){
\n            $landCount_current++;
\n        }
\n
\n        if($land_result['status'] == 'sold'){
\n
\n            $landModDate = str_replace(' ', '-', $land_result['mod_date']);
\n            if(in_array($landModDate, $getmodDateXmlLand_sold)){
\n                $landCount_sold++;
\n            }
\n        }
\n
\n        if($land_result['status'] == 'current' || $land_result['status'] == 'sold'){
\n
\n            $landModDate = str_replace(' ', '-', $land_result['mod_date']);
\n            if(in_array($landModDate, $getmodDateXmlLandALL)){
\n                array_push($feedLandID, $land_result['unique_id']);
\n
\n                $getChckStatusitemDBLandALL['id'] = $land_result['unique_id'];
\n                $getChckStatusitemDBLandALL['status'] = $land_result['status'];
\n
\n                array_push($getChckStatusDBLandALL, $getChckStatusitemDBLandALL);
\n
\n                $AllCompositeLanditems['uniq_id'] = $land_result['unique_id'];
\n                $AllCompositeLanditems['status'] = $land_result['status'];
\n                $AllCompositeLanditems['address'] = $land_result['address'];
\n                array_push($AllCompositeLand, $AllCompositeLanditems);
\n
\n                $landCountAll++;
\n            }
\n        }
\n    }
\n
\n    //PROPERTIES POST type
\n    $argsProperties = array(
\n        'post_type'         =>  'property',
\n        'posts_per_page'    =>  -1,
\n        'post_status'       =>  'publish'
\n    );
\n            
\n    $queryProperties = new WP_Query( $argsProperties );
\n    // $totalProperties = $queryProperties->found_posts;
\n    $totalProperties_current = 0;
\n    $totalProperties_sold = 0;
\n    $residentialCountAllpost = 0;
\n    $siteResidentialID = [];
\n    $getChckStatusitemPostResidentialALL = [];
\n    $getChckStatusPostResidentialALL = [];
\n
\n    if ( $queryProperties->have_posts() ) {
\n
\n        while ( $queryProperties->have_posts() ) {
\n            $queryProperties->the_post(); 
\n
\n            $meta = get_post_meta(get_the_ID());
\n
\n            $attachments = get_children(
\n                array(
\n                    'post_parent'    => get_the_ID(),
\n                    'post_type'      => 'attachment',
\n                    'post_mime_type' => 'image',
\n                )
\n            );
\n
\n            $countGallery_m = 0;
\n        
\n            foreach($attachments as $a){
\n                $r = (array)$a;
\n
\n                if($r['post_parent'] == get_the_ID()){
\n                    $countGallery_m++;
\n                }
\n            }
\n
\n
\n            if($meta['property_status'][0] == 'current'){
\n                $totalProperties_current++;
\n            }
\n
\n            if($meta['property_status'][0] == 'sold'){
\n                $totalProperties_sold++;
\n            }
\n            if($meta['property_status'][0] == 'current' || $meta['property_status'][0] == 'sold'){
\n
\n                array_push($siteResidentialID, $meta['property_unique_id'][0]);
\n
\n                $getChckStatusitemPostResidentialALL['id'] = $meta['property_unique_id'][0];
\n                $getChckStatusitemPostResidentialALL['status'] = $meta['property_status'][0];
\n
\n                array_push($getChckStatusPostResidentialALL, $getChckStatusitemPostResidentialALL);
\n
\n                $AllCompositeResidentialitems['uniq_id'] = $meta['property_unique_id'][0];
\n                $AllCompositeResidentialitems['status'] = $meta['property_status'][0];
\n                $AllCompositeResidentialitems['address'] = get_the_title();
\n                array_push($AllCompositeResidential, $AllCompositeResidentialitems);
\n
\n                $residentialCountAllpost++;
\n            }
\n        
\n        }
\n    }
\n    wp_reset_postdata();
\n
\n    //LAND POST type
\n    $argsLand = array(
\n        'post_type'         =>  'land',
\n        'posts_per_page'    =>  -1,
\n        'post_status'       =>  'publish'
\n    );
\n            
\n    $queryLand = new WP_Query( $argsLand );
\n    // $totalLand = $queryLand->found_posts;
\n    $totalLand_current = 0;
\n    $totalLand_sold = 0;
\n    $landCountAllpost = 0;
\n    $siteLandID = [];
\n    $getChckStatusitemPostLandALL = [];
\n    $getChckStatusPostLandALL = [];
\n
\n    if ( $queryLand->have_posts() ) {
\n
\n        while ( $queryLand->have_posts() ) {
\n            $queryLand->the_post(); 
\n
\n            $meta = get_post_meta(get_the_ID());
\n
\n            if($meta['property_status'][0] == 'current'){
\n                $totalLand_current++;
\n            }
\n
\n            if($meta['property_status'][0] == 'sold'){
\n                $totalLand_sold++;
\n            }
\n            if($meta['property_status'][0] == 'current' || $meta['property_status'][0] == 'sold'){
\n                array_push($siteLandID, $meta['property_unique_id'][0]);
\n
\n                $getChckStatusitemPostLandALL['id'] = $meta['property_unique_id'][0];
\n                $getChckStatusitemPostLandALL['status'] = $meta['property_status'][0];
\n
\n                array_push($getChckStatusPostLandALL, $getChckStatusitemPostLandALL);
\n
\n                $AllCompositeLanditems['uniq_id'] = $meta['property_unique_id'][0];
\n                $AllCompositeLanditems['status'] = $meta['property_status'][0];
\n                $AllCompositeLanditems['address'] = get_the_title();
\n                array_push($AllCompositeLand, $AllCompositeLanditems);
\n
\n                $landCountAllpost++;
\n            }
\n        
\n        }
\n    }
\n    wp_reset_postdata();
\n
\n    //RENTAL POST type
\n    $argsRental = array(
\n        'post_type'         =>  'rental',
\n        'posts_per_page'    =>  -1,
\n        'post_status'       =>  'publish'
\n    );
\n            
\n    $queryRental = new WP_Query( $argsRental );
\n    // $totalRental = $queryRental->found_posts;
\n    $totalRental_current = 0;
\n    $totalRental_leased = 0;
\n    $rentalCountAllpost = 0;
\n    $siteRentalID = [];
\n    $getChckStatusitemPostRentalALL = [];
\n    $getChckStatusPostRentalALL = [];
\n
\n    if ( $queryRental->have_posts() ) {
\n
\n        while ( $queryRental->have_posts() ) {
\n            $queryRental->the_post(); 
\n
\n            $meta = get_post_meta(get_the_ID());
\n
\n            if($meta['property_status'][0] == 'current'){
\n                $totalRental_current++;
\n            }
\n
\n            if($meta['property_status'][0] == 'leased'){
\n                $totalRental_leased++;
\n            }
\n            if($meta['property_status'][0] == 'current'){
\n                array_push($siteRentalID, $meta['property_unique_id'][0]);
\n
\n                $getChckStatusitemPostRentalALL['id'] = $meta['property_unique_id'][0];
\n                $getChckStatusitemPostRentalALL['status'] = $meta['property_status'][0];
\n
\n                array_push($getChckStatusPostRentalALL, $getChckStatusitemPostRentalALL);
\n
\n                $rentalCountAllpost++;
\n
\n                $AllCompositeRentalitems['uniq_id'] = $meta['property_unique_id'][0];
\n                $AllCompositeRentalitems['status'] = $meta['property_status'][0];
\n                $AllCompositeRentalitems['address'] = get_the_title();
\n                array_push($AllCompositeRental, $AllCompositeRentalitems);
\n            }
\n        
\n        }
\n    }
\n    wp_reset_postdata();
\n
\n    //COMMERCIAL POST type
\n    $argsCommercial = array(
\n        'post_type'         =>  'commercial',
\n        'posts_per_page'    =>  -1,
\n        'post_status'       =>  'publish'
\n    );
\n            
\n    $queryCommercial = new WP_Query( $argsCommercial );
\n    $totalCommercial = $queryCommercial->found_posts;
\n    $siteCommercialID = [];
\n    $commercialCountAllpost = 0;
\n    $getChckStatusitemPostCommercialALL = [];
\n    $getChckStatusPostCommercialALL = [];
\n
\n    if ( $queryCommercial->have_posts() ) {
\n
\n        while ( $queryCommercial->have_posts() ) {
\n            $queryCommercial->the_post(); 
\n
\n            $meta = get_post_meta(get_the_ID());
\n
\n            if($meta['property_status'][0] == 'current' || $meta['property_status'][0] == 'sold' && $meta['property_status'][0] == 'leased'){
\n                array_push($siteCommercialID, $meta['property_unique_id'][0]);
\n
\n                $getChckStatusitemPostCommercialALL['id'] = $meta['property_unique_id'][0];
\n                $getChckStatusitemPostCommercialALL['status'] = $meta['property_status'][0];
\n
\n                array_push($getChckStatusPostCommercialALL, $getChckStatusitemPostCommercialALL);
\n
\n                $commercialCountAllpost++;
\n            }
\n        
\n        }
\n    }
\n    wp_reset_postdata();
\n
\n    //API REQUEST
\n    $urlResidential_current = 'https://ap-southeast-2.api.vaultre.com.au/api/v1.3/properties/residential/sale?publishedOnPortals=332&pagesize=200';
\n    $urlRental_current = 'https://ap-southeast-2.api.vaultre.com.au/api/v1.3/properties/residential/lease?publishedOnPortals=332&pagesize=200';
\n    $urlLand_current = 'https://ap-southeast-2.api.vaultre.com.au/api/v1.3/properties/land/sale?publishedOnPortals=332&pagesize=200';
\n
\n    $urlCommercial_current = 'https://ap-southeast-2.api.vaultre.com.au/api/v1.3/properties/commercial/sale?publishedOnPortals=332&pagesize=200';
\n    $urlCommercial_leased = 'https://ap-southeast-2.api.vaultre.com.au/api/v1.3/properties/commercial/lease?publishedOnPortals=332&pagesize=200';
\n
\n    $args = array(
\n        'headers' => array(
\n            'Content-Type'  =>  'application/json',
\n            'Authorization' =>  'Bearer uumrxqafuygepfxktcritqfqjxsazvjllsakdipm',
\n            'X-Api-Key'     =>  'uwBk6bvjCV4LJotgmFKiu4uJESyDRD419yAdIl8o'
\n        ),
\n        'timeout' => 10
\n    );
\n
\n    //Residential Current API
\n    $resResidential_current = wp_remote_get( $urlResidential_current, $args );
\n    $resResidential_current_body = wp_remote_retrieve_body( $resResidential_current );
\n    $dataResidential_current = json_decode($resResidential_current_body);
\n    $arrayResidential_current = (array)$dataResidential_current;
\n    $apiResidentialID = [];
\n    $itemsResidentialAPI = [];
\n    $AllResidentialAPI = [];
\n    $CountAllResidentialAPI = 0;
\n    $CountAllResidentialVaultRE = 0;
\n    $getChckStatusitemAPIResidentialALL = [];
\n    $getChckStatusAPIResidentialALL = [];
\n
\n    foreach($arrayResidential_current['items'] as $item){
\n        $array = (array)$item;
\n        $photos = (array)$array['photos'];
\n
\n        if($array['portalStatus'] != 'unconditional'){
\n
\n            $sl_ID = 'L'.$array['saleLifeId'];
\n            array_push($apiResidentialID, $sl_ID);
\n
\n            $imageCount = 0;
\n            foreach($photos as $p){
\n                $imagearray = (array)$p;
\n
\n                if($imagearray['published'] == 1 && $imagearray['type'] == 'Photograph'){
\n                    $imageCount++;
\n
\n                }
\n            }
\n
\n            $CountAllResidentialVaultRE++;
\n            
\n            $address_details = (array)$array['address'];
\n            $suburb_details = (array)$address_details['suburb'];
\n            $state_details = (array)$suburb_details['state'];
\n            
\n            $itemsResidentialAPI['propID'] = $sl_ID;
\n            $itemsResidentialAPI['status'] = 'current';
\n            $itemsResidentialAPI['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n            
\n            $getChckStatusitemAPIResidentialALL['id'] = $sl_ID;
\n            $getChckStatusitemAPIResidentialALL['status'] = 'current';
\n            $getChckStatusitemAPIResidentialALL['image_count'] = $imageCount;
\n            
\n            array_push($getChckStatusAPIResidentialALL, $getChckStatusitemAPIResidentialALL);
\n            
\n            array_push($AllResidentialAPI, $itemsResidentialAPI);
\n            
\n            $AllCompositeResidentialitems['uniq_id'] = $sl_ID;
\n            $AllCompositeResidentialitems['status'] = 'current';
\n            $AllCompositeResidentialitems['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n            array_push($AllCompositeResidential, $AllCompositeResidentialitems);
\n        }
\n    }
\n
\n    //Residential Sold API
\n    // $resResidential_sold = wp_remote_get( $urlResidential_sold, $args );
\n    // $resResidential_sold_body = wp_remote_retrieve_body( $resResidential_sold );
\n    // $dataResidential_sold = json_decode($resResidential_sold_body);
\n    // $arrayResidential_sold = (array)$dataResidential_sold;
\n
\n    // foreach($arrayResidential_sold['items'] as $item){
\n    //     $array = (array)$item;
\n    //     $sl_ID = 'L'.$array['saleLifeId'];
\n    //     array_push($apiResidentialID, $sl_ID);
\n
\n    //     $address_details = (array)$array['address'];
\n    //     $suburb_details = (array)$address_details['suburb'];
\n    //     $state_details = (array)$suburb_details['state'];
\n        
\n    //     $itemsResidentialAPI['propID'] = $sl_ID;
\n    //     $itemsResidentialAPI['status'] = 'sold';
\n    //     $itemsResidentialAPI['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n
\n    //     $getChckStatusitemAPIResidentialALL['id'] = $sl_ID;
\n    //     $getChckStatusitemAPIResidentialALL['status'] = 'sold';
\n
\n    //     array_push($getChckStatusAPIResidentialALL, $getChckStatusitemAPIResidentialALL);
\n
\n    //     array_push($AllResidentialAPI, $itemsResidentialAPI);
\n
\n    //     $AllCompositeResidentialitems['uniq_id'] = $sl_ID;
\n    //     $AllCompositeResidentialitems['status'] = 'sold';
\n    //     $AllCompositeResidentialitems['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n    //     array_push($AllCompositeResidential, $AllCompositeResidentialitems);
\n    // }
\n
\n    //get residential count, all result API
\n    foreach($AllResidentialAPI as $AllResidentialAPI_res){
\n        $CountAllResidentialAPI++;
\n    }
\n
\n    //Rental Current API
\n    $resRental_current = wp_remote_get( $urlRental_current, $args );
\n    $resRental_current_body = wp_remote_retrieve_body( $resRental_current );
\n    $dataRental_current = json_decode($resRental_current_body);
\n    $arrayRental_current = (array)$dataRental_current;
\n    $apiRentalID = [];
\n    $itemsRentalAPI = [];
\n    $AllRentalAPI = [];
\n    $CountAllRentalAPI = 0;
\n    $getChckStatusitemAPIRentalALL = [];
\n    $getChckStatusAPIRentalALL = [];
\n    $apiRentalOverviewCount = 0;
\n
\n    foreach($arrayRental_current['items'] as $item){
\n        $array = (array)$item;
\n        $photos = (array)$array['photos'];
\n
\n        if($array['available'] == 1){
\n
\n            $ll_ID = 'R'.$array['leaseLifeId'];
\n            array_push($apiRentalID, $ll_ID);
\n
\n            $imageCount = 0;
\n            foreach($photos as $p){
\n                $imagearray = (array)$p;
\n
\n                if($imagearray['published'] == 1 && $imagearray['type'] == 'Photograph'){
\n                    $imageCount++;
\n
\n                }
\n            }
\n            
\n            $address_details = (array)$array['address'];
\n            $suburb_details = (array)$address_details['suburb'];
\n            $state_details = (array)$suburb_details['state'];
\n            
\n            $itemsRentalAPI['propID'] = $ll_ID;
\n            $itemsRentalAPI['status'] = 'current';
\n            $itemsRentalAPI['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n            
\n            $apiRentalOverviewCount++;
\n            
\n            $getChckStatusitemAPIRentalALL['id'] = $ll_ID;
\n            $getChckStatusitemAPIRentalALL['status'] = 'current';
\n            $getChckStatusitemAPIRentalALL['image_count'] = $imageCount;
\n            
\n            array_push($getChckStatusAPIRentalALL, $getChckStatusitemAPIRentalALL);
\n            
\n            array_push($AllRentalAPI, $itemsRentalAPI);
\n        }
\n    }
\n
\n    //Rental Leased API
\n    // $resRental_leased = wp_remote_get( $urlRental_leased, $args );
\n    // $resRental_leased_body = wp_remote_retrieve_body( $resRental_leased );
\n    // $dataRental_leased = json_decode($resRental_leased_body);
\n    // $arrayRental_leased = (array)$dataRental_leased;
\n
\n    // foreach($arrayRental_leased['items'] as $item){
\n    //     $array = (array)$item;
\n    //     $ll_ID = 'R'.$array['leaseLifeId'];
\n    //     array_push($apiRentalID, $ll_ID);
\n
\n    //     $address_details = (array)$array['address'];
\n    //     $suburb_details = (array)$address_details['suburb'];
\n    //     $state_details = (array)$suburb_details['state'];
\n        
\n    //     // $itemsRentalAPI['propID'] = $ll_ID;
\n    //     // $itemsRentalAPI['status'] = 'leased';
\n    //     // $itemsRentalAPI['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n
\n    //     $getChckStatusitemAPIRentalALL['id'] = $ll_ID;
\n    //     $getChckStatusitemAPIRentalALL['status'] = 'leased';
\n
\n    //     array_push($getChckStatusAPIRentalALL, $getChckStatusitemAPIRentalALL);
\n
\n    //     // array_push($AllRentalAPI, $itemsRentalAPI);
\n
\n    //     $APIrentalModDate = str_replace('T', '-', $array['modified']);
\n    //     $APIrentalModDate_clean = str_replace('+00:00', '', $APIrentalModDate);
\n    //     if(in_array($APIrentalModDate_clean, $getmodDateXmlRental_leased)){
\n    //         $APIrentalLeasedCount_sold++;
\n    //     }
\n    // }
\n
\n    //get rental count, all result API
\n    $AllRentalAPIduplicateID = [];
\n    foreach($AllRentalAPI as $AllRentalAPI_res){
\n        $AllRentalAPIid_api = $AllRentalAPI_res['propID'];
\n        if(!in_array($AllRentalAPIid_api, $AllRentalAPIduplicateID)){
\n            
\n            $CountAllRentalAPI++;
\n
\n            array_push($AllRentalAPIduplicateID, $AllRentalAPIid_api);
\n        }
\n    }
\n
\n    //Land Current API
\n    $resLand_current = wp_remote_get( $urlLand_current, $args );
\n    $resLand_current_body = wp_remote_retrieve_body( $resLand_current );
\n    $dataLand_current = json_decode($resLand_current_body);
\n    $arrayLand_current = (array)$dataLand_current;
\n    $apiLandID = [];
\n    $itemsLandAPI = [];
\n    $AllLandAPI = [];
\n    $CountAllLandAPI = 0;
\n    $getChckStatusitemAPILandALL = [];
\n    $getChckStatusAPILandALL = [];
\n
\n    foreach($arrayLand_current['items'] as $item){
\n        $array = (array)$item;
\n        $photos = (array)$array['photos'];
\n
\n        $sl_ID = 'L'.$array['saleLifeId'];
\n        array_push($apiLandID, $sl_ID);
\n
\n        $imageCount = 0;
\n        foreach($photos as $p){
\n            $imagearray = (array)$p;
\n
\n            if($imagearray['published'] == 1 && $imagearray['type'] == 'Photograph'){
\n                $imageCount++;
\n
\n            }
\n        }
\n
\n        $address_details = (array)$array['address'];
\n        $suburb_details = (array)$address_details['suburb'];
\n        $state_details = (array)$suburb_details['state'];
\n        
\n        $itemsLandAPI['propID'] = $sl_ID;
\n        $itemsLandAPI['status'] = 'current';
\n        $itemsLandAPI['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n
\n        $getChckStatusitemAPILandALL['id'] = $sl_ID;
\n        $getChckStatusitemAPILandALL['status'] = 'current';
\n        $getChckStatusitemAPILandALL['image_count'] = $imageCount;
\n
\n        array_push($getChckStatusAPILandALL, $getChckStatusitemAPILandALL);
\n
\n        array_push($AllLandAPI, $itemsLandAPI);
\n
\n        $AllCompositeLanditems['uniq_id'] = $sl_ID;
\n        $AllCompositeLanditems['status'] = 'current';
\n        $AllCompositeLanditems['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n        array_push($AllCompositeLand, $AllCompositeLanditems);
\n    }
\n
\n    //Land Sold API
\n    // $resLand_sold = wp_remote_get( $urlLand_sold, $args );
\n    // $resLand_sold_body = wp_remote_retrieve_body( $resLand_sold );
\n    // $dataLand_sold = json_decode($resLand_sold_body);
\n    // $arrayLand_sold = (array)$dataLand_sold;
\n    // $APIlandCount_sold = 0;
\n
\n    // foreach($arrayLand_sold['items'] as $item){
\n    //     $array = (array)$item;
\n    //     $sl_ID = 'L'.$array['saleLifeId'];
\n    //     array_push($apiLandID, $sl_ID);
\n
\n    //     $address_details = (array)$array['address'];
\n    //     $suburb_details = (array)$address_details['suburb'];
\n    //     $state_details = (array)$suburb_details['state'];
\n        
\n    //     $itemsLandAPI['propID'] = $sl_ID;
\n    //     $itemsLandAPI['status'] = 'sold';
\n    //     $itemsLandAPI['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n
\n    //     $getChckStatusitemAPILandALL['id'] = $sl_ID;
\n    //     $getChckStatusitemAPILandALL['status'] = 'sold';
\n
\n    //     array_push($getChckStatusAPILandALL, $getChckStatusitemAPILandALL);
\n
\n    //     array_push($AllLandAPI, $itemsLandAPI);
\n
\n    //     $AllCompositeLanditems['uniq_id'] = $sl_ID;
\n    //     $AllCompositeLanditems['status'] = 'sold';
\n    //     $AllCompositeLanditems['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n    //     array_push($AllCompositeLand, $AllCompositeLanditems);
\n
\n    //     $APIlandModDate = str_replace('T', '-', $array['modified']);
\n    //     $APIlandModDate_clean = str_replace('+00:00', '', $APIlandModDate);
\n    //     if(in_array($APIlandModDate_clean, $getmodDateXmlLand_sold)){
\n    //         $APIlandCount_sold++;
\n    //     }
\n    // }
\n
\n    //get land count, all result API
\n    foreach($AllLandAPI as $AllLandAPI_res){
\n        $CountAllLandAPI++;
\n    }
\n
\n    //Commercial Current API
\n    $resCommercial_current = wp_remote_get( $urlCommercial_current, $args );
\n    $resCommercial_current_body = wp_remote_retrieve_body( $resCommercial_current );
\n    $dataCommercial_current = json_decode($resCommercial_current_body);
\n    $arrayCommercial_current = (array)$dataCommercial_current;
\n    $apiCommercialID = [];
\n    $itemsCommercialAPI = [];
\n    $AllCommercialAPI = [];
\n    $CountAllCommercialAPI = 0;
\n    $getChckStatusitemAPICommercialALL = [];
\n    $getChckStatusAPICommercialALL = [];
\n
\n    foreach($arrayCommercial_current['items'] as $item){
\n        $array = (array)$item;
\n        $sl_ID = 'L'.$array['saleLifeId'];
\n        array_push($apiCommercialID, $sl_ID);
\n
\n        $address_details = (array)$array['address'];
\n        $suburb_details = (array)$address_details['suburb'];
\n        $state_details = (array)$suburb_details['state'];
\n        
\n        $itemsCommercialAPI['propID'] = $sl_ID;
\n        $itemsCommercialAPI['status'] = 'current';
\n        $itemsCommercialAPI['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n
\n        $getChckStatusitemAPICommercialALL['id'] = $sl_ID;
\n        $getChckStatusitemAPICommercialALL['status'] = 'current';
\n
\n        array_push($getChckStatusAPICommercialALL, $getChckStatusitemAPICommercialALL);
\n
\n        array_push($AllCommercialAPI, $itemsCommercialAPI);
\n    }
\n
\n    //Commercial Sold API
\n    // $resCommercial_sold = wp_remote_get( $urlCommercial_sold, $args );
\n    // $resCommercial_sold_body = wp_remote_retrieve_body( $resCommercial_sold );
\n    // $dataCommercial_sold = json_decode($resCommercial_sold_body);
\n    // $arrayCommercial_sold = (array)$dataCommercial_sold;
\n
\n    // foreach($arrayCommercial_sold['items'] as $item){
\n    //     $array = (array)$item;
\n    //     $sl_ID = 'L'.$array['saleLifeId'];
\n    //     array_push($apiCommercialID, $sl_ID);
\n
\n    //     $address_details = (array)$array['address'];
\n    //     $suburb_details = (array)$address_details['suburb'];
\n    //     $state_details = (array)$suburb_details['state'];
\n        
\n    //     $itemsCommercialAPI['propID'] = $sl_ID;
\n    //     $itemsCommercialAPI['status'] = 'sold';
\n    //     $itemsCommercialAPI['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n
\n    //     $getChckStatusitemAPICommercialALL['id'] = $sl_ID;
\n    //     $getChckStatusitemAPICommercialALL['status'] = 'sold';
\n
\n    //     array_push($getChckStatusAPICommercialALL, $getChckStatusitemAPICommercialALL);
\n
\n    //     array_push($AllCommercialAPI, $itemsCommercialAPI);
\n    // }
\n
\n    //Commercial Leased API
\n    $resCommercial_leased = wp_remote_get( $urlCommercial_leased, $args );
\n    $resCommercial_leased_body = wp_remote_retrieve_body( $resCommercial_leased );
\n    $dataCommercial_leased = json_decode($resCommercial_leased_body);
\n    $arrayCommercial_leased = (array)$dataCommercial_leased;
\n
\n    foreach($arrayCommercial_leased['items'] as $item){
\n        $array = (array)$item;
\n        $sl_ID = 'L'.$array['saleLifeId'];
\n        array_push($apiCommercialID, $sl_ID);
\n
\n        $address_details = (array)$array['address'];
\n        $suburb_details = (array)$address_details['suburb'];
\n        $state_details = (array)$suburb_details['state'];
\n        
\n        $itemsCommercialAPI['propID'] = $sl_ID;
\n        $itemsCommercialAPI['status'] = 'leased';
\n        $itemsCommercialAPI['address'] = $address_details['streetNumber'].' '.$address_details['street'].', '.$suburb_details['name'].' '.$state_details['abbreviation'].' '.$suburb_details['postcode'];
\n
\n        $getChckStatusitemAPICommercialALL['id'] = $sl_ID;
\n        $getChckStatusitemAPICommercialALL['status'] = 'leased';
\n
\n        array_push($getChckStatusAPICommercialALL, $getChckStatusitemAPICommercialALL);
\n
\n        array_push($AllCommercialAPI, $itemsCommercialAPI);
\n    }
\n
\n    //get commercial count, all result API
\n    foreach($AllCommercialAPI as $AllCommercialAPI_res){
\n        $CountAllCommercialAPI++;
\n    }
\n    
\n    // check user capabilities
\n    if ( ! current_user_can( 'manage_options' ) ) {
\n        return;
\n    }
\n
\n    //Get the active tab from the $_GET param
\n    $default_tab = null;
\n    $default_property = null;
\n    $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
\n    $property = isset($_GET['property']) ? $_GET['property'] : $default_property;
\n?>
\n    <style>
\n        .green{
\n            color: #02b93b !important;
\n        }
\n        .red{
\n            color: #f59292 !important;
\n        }
\n        .accordion1-not{
\n            background-color: #fff;
\n            color: #444;
\n            padding: 0;
\n            width: 100%;
\n            border: none;
\n            text-align: left;
\n            outline: none;
\n            font-size: 13px;
\n        }
\n        .accordion1,
\n        .accordion {
\n            background-color: #fff;
\n            color: #444;
\n            cursor: pointer;
\n            padding: 0;
\n            width: 100%;
\n            border: none;
\n            text-align: left;
\n            outline: none;
\n            font-size: 13px;
\n            transition: 0.4s;
\n        }
\n
\n        .active, .accordion1:hover
\n        .active, .accordion:hover {
\n            background-color: #ccc; 
\n        }
\n
\n        .panel1,
\n        .panel {
\n            padding: 0 18px;
\n            display: none;
\n            background-color: white;
\n            overflow: hidden;
\n        }
\n        tr.status-red{
\n            background: #ffdbdb !important;
\n        }
\n        .ppg-spinner{
\n            background: url(/wp-admin/images/spinner.gif) no-repeat;
\n            -webkit-background-size: 20px 20px;
\n            background-size: 20px 20px;
\n            display: none;
\n            opacity: 0.7;
\n            filter: alpha(opacity=70);
\n            width: 20px;
\n            height: 20px;
\n            position: absolute;
\n            top: 24px;
\n            left: 415px;
\n        }
\n        #ppg_success{
\n            display: none;
\n            top: 24px;
\n            position: absolute;
\n        }
\n    </style>
\n    <div class="wrap">
\n        <h1>
\n            <?= esc_html( get_admin_page_title() ) ?> 
\n            <button class="page-title-action add-new-h2" id="email_cron">Run Cron</button>
\n            <div class="ppg-spinner"></div>
\n            <span id="ppg_success" class="dashicons dashicons-yes green"></span>
\n        </h1>
\n        <nav class="nav-tab-wrapper">
\n            <a href="?page=property-sync-checker" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Overview</a>
\n            <a href="?page=property-sync-checker&tab=website" class="nav-tab <?php if($tab==='website'):?>nav-tab-active<?php endif; ?>">Website</a>
\n                <a href="?page=property-sync-checker&tab=feedsyncxml" class="nav-tab <?php if($tab==='feedsyncxml'):?>nav-tab-active<?php endif; ?>">FeedSync XML</a>
\n            <a href="?page=property-sync-checker&tab=feedsync" class="nav-tab <?php if($tab==='feedsync'):?>nav-tab-active<?php endif; ?>">FeedSync DB</a>
\n            <a href="?page=property-sync-checker&tab=vaultre" class="nav-tab <?php if($tab==='vaultre'):?>nav-tab-active<?php endif; ?>">VaultRE</a>
\n            <a href="?page=property-sync-checker&tab=composite" class="nav-tab <?php if($tab==='composite'):?>nav-tab-active<?php endif; ?>">Composite</a>
\n            <a href="?page=property-sync-checker&tab=images" class="nav-tab <?php if($tab==='images'):?>nav-tab-active<?php endif; ?>">Images</a>
\n        </nav>
\n        <div class="tab-content">
\n            <?php
\n                switch($tab):
\n                    case 'website': ?>
\n                        <!-- RESIDENTIAL -->
\n                        <button class="accordion <?php if($property==='residential'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Residential (<?= $residentialCountAllpost; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        if ( $queryProperties->have_posts() ) {
\n
\n                                            while ( $queryProperties->have_posts() ) {
\n                                                $queryProperties->the_post(); 
\n                                    
\n                                                $meta = get_post_meta(get_the_ID());
\n                                    
\n                                                if($meta['property_status'][0] == 'current' || $meta['property_status'][0] == 'sold'){
\n                                                    echo '<tr '.((!in_array($meta['property_unique_id'][0], $getIDXmlResidential) || !in_array($meta['property_unique_id'][0], $feedResidentialID)) ? 'class="status-red"' : '').'>';
\n                                                        echo '<td>'.$meta['property_unique_id'][0].'</td>';
\n                                                        echo '<td>'.get_the_title().'</td>';
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$meta['property_status'][0].']</td>';
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $getIDXmlResidential)){
\n                                                            foreach($getChckStatusXmlResidentialALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $feedResidentialID)){
\n                                                            foreach($getChckStatusDBResidentialALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $apiResidentialID)){
\n                                                            foreach($getChckStatusAPIResidentialALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                    echo '</tr>';
\n                                                }
\n                                            }
\n                                        }
\n                                        wp_reset_postdata();
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- LAND -->
\n                        <button class="accordion <?php if($property==='land'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Land (<?= $landCountAllpost; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        if ( $queryLand->have_posts() ) {
\n
\n                                            while ( $queryLand->have_posts() ) {
\n                                                $queryLand->the_post(); 
\n                                    
\n                                                $meta = get_post_meta(get_the_ID());
\n                                    
\n                                                if($meta['property_status'][0] == 'current' || $meta['property_status'][0] == 'sold'){
\n                                                    echo '<tr '.((!in_array($meta['property_unique_id'][0], $getIDXmlLand) || !in_array($meta['property_unique_id'][0], $feedLandID)) ? 'class="status-red"' : '').'>';
\n                                                        echo '<td>'.$meta['property_unique_id'][0].'</td>';
\n                                                        echo '<td>'.get_the_title().'</td>';
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$meta['property_status'][0].']</td>';
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $getIDXmlLand)){
\n                                                            foreach($getChckStatusXmlLandALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $feedLandID)){
\n                                                            foreach($getChckStatusDBLandALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $apiLandID)){
\n                                                            foreach($getChckStatusAPILandALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                    echo '</tr>';
\n                                                }
\n                                            }
\n                                        }
\n                                        wp_reset_postdata();
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- RENTAL -->
\n                        <button class="accordion <?php if($property==='rental'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Rental (<?= $rentalCountAllpost; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        if ( $queryRental->have_posts() ) {
\n
\n                                            while ( $queryRental->have_posts() ) {
\n                                                $queryRental->the_post(); 
\n                                    
\n                                                $meta = get_post_meta(get_the_ID());
\n                                    
\n                                                if($meta['property_status'][0] == 'current'){
\n                                                    echo '<tr '.((!in_array($meta['property_unique_id'][0], $getIDXmlRental) || !in_array($meta['property_unique_id'][0], $feedRentalID)) ? 'class="status-red"' : '').'>';
\n                                                        echo '<td>'.$meta['property_unique_id'][0].'</td>';
\n                                                        echo '<td>'.get_the_title().'</td>';
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$meta['property_status'][0].']</td>';
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $getIDXmlRental)){
\n                                                            foreach($getChckStatusXmlRentalALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $feedRentalID)){
\n                                                            foreach($getChckStatusDBRentalALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $apiRentalID)){
\n                                                            foreach($getChckStatusAPIRentalALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0] && $resData['status'] == 'current'){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                    echo '</tr>';
\n                                                }
\n                                            }
\n                                        }
\n                                        wp_reset_postdata();
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- COMMERCIAL -->
\n                        <button class="accordion <?php if($property==='commercial'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Commercial (<?= $commercialCountAllpost; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        if ( $queryCommercial->have_posts() ) {
\n
\n                                            while ( $queryCommercial->have_posts() ) {
\n                                                $queryCommercial->the_post(); 
\n                                    
\n                                                $meta = get_post_meta(get_the_ID());
\n                                    
\n                                                if($meta['property_status'][0] == 'current' || $meta['property_status'][0] == 'sold' || $meta['property_status'][0] == 'leased'){
\n                                                    echo '<tr '.((!in_array($meta['property_unique_id'][0], $getIDXmlResidential) || !in_array($meta['property_unique_id'][0], $feedCommercialID)) ? 'class="status-red"' : '').'>';
\n                                                        echo '<td>'.$meta['property_unique_id'][0].'</td>';
\n                                                        echo '<td>'.get_the_title().'</td>';
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$meta['property_status'][0].']</td>';
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $getIDXmlResidential)){
\n                                                            foreach($getChckStatusXmlCommercialALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $feedCommercialID)){
\n                                                            foreach($getChckStatusDBCommercialALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                        if(in_array($meta['property_unique_id'][0], $apiCommercialID)){
\n                                                            foreach($getChckStatusAPICommercialALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                    echo '</tr>';
\n                                                }
\n                                            }
\n                                        }else{
\n                                            echo '<tr>';
\n                                                echo '<td>No result found.</td>';
\n                                            echo '</tr>';
\n                                        }
\n                                        wp_reset_postdata();
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                    <?php break; ?>
\n                    <?php case 'feedsyncxml': ?>
\n                        <!-- RESIDENTIAL feedsyncxml-->
\n                        <button class="accordion <?php if($property==='residential'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Residential (<?= $getCountAllXmlResidential; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php
\n                                        foreach($getAllXmlResidential as $getAllXmlResidential_result){
\n                                                
\n                                            echo '<tr '.((!in_array($getAllXmlResidential_result['propID'], $siteResidentialID) || !in_array($getAllXmlResidential_result['propID'], $feedResidentialID)) ? 'class="status-red"' : '').'>';
\n                                                echo '<td>'.$getAllXmlResidential_result['propID'].'</td>';
\n                                                echo '<td>'.$getAllXmlResidential_result['address'].'</td>';
\n                                                
\n                                                if(in_array($getAllXmlResidential_result['propID'], $siteResidentialID)){
\n                                                    foreach($getChckStatusPostResidentialALL as $resData){
\n                                                        if($resData['id'] == $getAllXmlResidential_result['propID']){
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                        }
\n                                                    }
\n                                                }else{
\n                                                    echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                }
\n
\n                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$getAllXmlResidential_result['status'].']</td>';
\n
\n                                                if(in_array($getAllXmlResidential_result['propID'], $feedResidentialID)){
\n                                                    foreach($getChckStatusDBResidentialALL as $resData){
\n                                                        if($resData['id'] == $getAllXmlResidential_result['propID']){
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                        }
\n                                                    }
\n                                                }else{
\n                                                    echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                }
\n
\n                                                if(in_array($getAllXmlResidential_result['propID'], $apiResidentialID)){
\n                                                    foreach($getChckStatusAPIResidentialALL as $resData){
\n                                                        if($resData['id'] == $getAllXmlResidential_result['propID']){
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                        }
\n                                                    }
\n                                                }else{
\n                                                    echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                }
\n                                            echo '</tr>';
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- LAND feedsyncxml -->
\n                        <button class="accordion <?php if($property==='land'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Land (<?= $getCountAllXmlLand; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        foreach($getAllXmlLand as $getAllXmlLand_result){
\n                                                
\n                                            echo '<tr '.((!in_array($getAllXmlLand_result['propID'], $siteLandID) || !in_array($getAllXmlLand_result['propID'], $feedLandID)) ? 'class="status-red"' : '').'>';
\n                                                echo '<td>'.$getAllXmlLand_result['propID'].'</td>';
\n                                                echo '<td>'.$getAllXmlLand_result['address'].'</td>';
\n                                                
\n                                                if(in_array($getAllXmlLand_result['propID'], $siteLandID)){
\n                                                    foreach($getChckStatusPostLandALL as $resData){
\n                                                        if($resData['id'] == $getAllXmlLand_result['propID']){
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                        }
\n                                                    }
\n                                                }else{
\n                                                    echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                }
\n
\n                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$getAllXmlLand_result['status'].']</td>';
\n
\n                                                if(in_array($getAllXmlLand_result['propID'], $feedLandID)){
\n                                                    foreach($getChckStatusDBLandALL as $resData){
\n                                                        if($resData['id'] == $getAllXmlLand_result['propID']){
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                        }
\n                                                    }
\n                                                }else{
\n                                                    echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                }
\n
\n                                                if(in_array($getAllXmlLand_result['propID'], $apiLandID)){
\n                                                    foreach($getChckStatusAPILandALL as $resData){
\n                                                        if($resData['id'] == $getAllXmlLand_result['propID']){
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                        }
\n                                                    }
\n                                                }else{
\n                                                    echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                }
\n                                            echo '</tr>';
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- RENTAL feedsyncxml -->
\n                        <button class="accordion <?php if($property==='rental'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Rental (<?= $getCountAllXmlRental; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        foreach($getAllXmlRental as $getAllXmlRental_result){
\n                                                
\n                                            echo '<tr '.((!in_array($getAllXmlRental_result['propID'], $siteRentalID) || !in_array($getAllXmlRental_result['propID'], $feedRentalID)) ? 'class="status-red"' : '').'>';
\n                                                echo '<td>'.$getAllXmlRental_result['propID'].'</td>';
\n                                                echo '<td>'.$getAllXmlRental_result['address'].'</td>';
\n                                                
\n                                                if(in_array($getAllXmlRental_result['propID'], $siteRentalID)){
\n                                                    foreach($getChckStatusPostRentalALL as $resData){
\n                                                        if($resData['id'] == $getAllXmlRental_result['propID']){
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                        }
\n                                                    }
\n                                                }else{
\n                                                    echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                }
\n
\n                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$getAllXmlRental_result['status'].']</td>';
\n
\n                                                if(in_array($getAllXmlRental_result['propID'], $feedRentalID)){
\n                                                    foreach($getChckStatusDBRentalALL as $resData){
\n                                                        if($resData['id'] == $getAllXmlRental_result['propID']){
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                        }
\n                                                    }
\n                                                }else{
\n                                                    echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                }
\n
\n                                                if(in_array($getAllXmlRental_result['propID'], $apiRentalID)){
\n                                                    foreach($getChckStatusAPIRentalALL as $resData){
\n                                                        if($resData['id'] == $getAllXmlRental_result['propID'] && $resData['status'] == 'current'){
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                        }
\n                                                    }
\n                                                }else{
\n                                                    echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                }
\n                                            echo '</tr>';
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- COMMERCIAL feedsyncxml -->
\n                        <button class="accordion <?php if($property==='commercial'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Commercial (<?= $getCountAllXmlCommercial; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                    <?php break; ?>
\n                    <?php case 'feedsync': ?>
\n                        <!-- RESIDENTIAL -->
\n                        <button class="accordion <?php if($property==='residential'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Residential (<?= $residentialCountAll; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php
\n                                        foreach($residential_results as $residential_result){
\n
\n                                            if($residential_result['status'] == 'current' || $residential_result['status'] == 'sold'){
\n
\n                                                $residentialModDate = str_replace(' ', '-', $residential_result['mod_date']);
\n
\n                                                if(in_array($residentialModDate, $getmodDateXmlResidentialALL)){
\n                                                    echo '<tr '.((!in_array($residential_result['unique_id'], $siteResidentialID) || !in_array($residential_result['unique_id'], $getIDXmlResidential)) ? 'class="status-red"' : '').'>';
\n                                                        echo '<td>'.$residential_result['unique_id'].'</td>';
\n                                                        echo '<td>'.$residential_result['address'].'</td>';
\n                                                        
\n                                                        if(in_array($residential_result['unique_id'], $siteResidentialID)){
\n                                                            foreach($getChckStatusPostResidentialALL as $resData){
\n                                                                if($resData['id'] == $residential_result['unique_id']){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                        if(in_array($residential_result['unique_id'], $getIDXmlResidential)){
\n                                                            foreach($getChckStatusXmlResidentialALL as $resData){
\n                                                                if($resData['id'] == $residential_result['unique_id']){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n    
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$residential_result['status'].']</td>';
\n    
\n                                                        if(in_array($residential_result['unique_id'], $apiResidentialID)){
\n                                                            foreach($getChckStatusAPIResidentialALL as $resData){
\n                                                                if($resData['id'] == $residential_result['unique_id']){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                    echo '</tr>';
\n
\n                                                }
\n                                                
\n                                            }
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- LAND -->
\n                        <button class="accordion <?php if($property==='land'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Land (<?= $landCountAll; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        foreach($land_results as $land_result){
\n
\n                                            if($land_result['status'] == 'current' || $land_result['status'] == 'sold'){
\n
\n                                                $landModDate = str_replace(' ', '-', $land_result['mod_date']);
\n                                                if(in_array($landModDate, $getmodDateXmlLandALL)){
\n                                                    echo '<tr '.((!in_array($land_result['unique_id'], $siteLandID) || !in_array($land_result['unique_id'], $getIDXmlLand)) ? 'class="status-red"' : '').'>';
\n                                                        echo '<td>'.$land_result['unique_id'].'</td>';
\n                                                        echo '<td>'.$land_result['address'].'</td>';
\n                                                        
\n                                                        if(in_array($land_result['unique_id'], $siteLandID)){
\n                                                            foreach($getChckStatusPostLandALL as $resData){
\n                                                                if($resData['id'] == $land_result['unique_id']){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n    
\n                                                        if(in_array($land_result['unique_id'], $getIDXmlLand)){
\n                                                            foreach($getChckStatusXmlLandALL as $resData){
\n                                                                if($resData['id'] == $land_result['unique_id']){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n    
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$land_result['status'].']</td>';
\n    
\n                                                        if(in_array($land_result['unique_id'], $apiLandID)){
\n                                                            foreach($getChckStatusAPILandALL as $resData){
\n                                                                if($resData['id'] == $land_result['unique_id']){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                    echo '</tr>';
\n
\n                                                }
\n                                            }
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- RENTAL -->
\n                        <button class="accordion <?php if($property==='rental'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Rental (<?= $rentalCountAll; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        foreach($rental_results as $rental_result){
\n
\n                                            if($rental_result['status'] == 'current'){
\n
\n                                                $rentalModDate = str_replace(' ', '-', $rental_result['mod_date']);
\n                                                if(in_array($rentalModDate, $getmodDateXmlRentalALL)){
\n                                                    echo '<tr '.((!in_array($rental_result['unique_id'], $siteRentalID) || !in_array($rental_result['unique_id'], $getIDXmlRental)) ? 'class="status-red"' : '').'>';
\n                                                        echo '<td>'.$rental_result['unique_id'].'</td>';
\n                                                        echo '<td>'.$rental_result['address'].'</td>';
\n                                                        
\n                                                        if(in_array($rental_result['unique_id'], $siteRentalID)){
\n                                                            foreach($getChckStatusPostRentalALL as $resData){
\n                                                                if($resData['id'] == $rental_result['unique_id']){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n    
\n                                                        if(in_array($rental_result['unique_id'], $getIDXmlRental)){
\n                                                            foreach($getChckStatusXmlRentalALL as $resData){
\n                                                                if($resData['id'] == $rental_result['unique_id']){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n    
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$rental_result['status'].']</td>';
\n    
\n                                                        if(in_array($rental_result['unique_id'], $apiRentalID)){
\n                                                            foreach($getChckStatusAPIRentalALL as $resData){
\n                                                                if($resData['id'] == $rental_result['unique_id'] && $resData['status'] == 'current'){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                    echo '</tr>';
\n                                                    
\n                                                }
\n                                                
\n                                            }
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- COMMERCIAL -->
\n                        <button class="accordion <?php if($property==='commercial'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Commercial (<?= $commercialCountAll; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        foreach($commercial_results as $commercial_result){
\n                                            if($commercial_result){
\n                                                if($commercial_result['status'] == 'current' || $commercial_result['status'] == 'sold' || $commercial_result['status'] == 'leased'){
\n                                                    
\n                                                    echo '<tr '.((!in_array($commercial_result['unique_id'], $siteCommercialID) || !in_array($commercial_result['unique_id'], $getIDXmlResidential)) ? 'class="status-red"' : '').'>';
\n                                                        echo '<td>'.$commercial_result['unique_id'].'</td>';
\n                                                        echo '<td>'.$commercial_result['address'].'</td>';
\n                                                        
\n                                                        if(in_array($commercial_result['unique_id'], $siteCommercialID)){
\n                                                            foreach($getChckStatusPostCommercialALL as $resData){
\n                                                                if($resData['id'] == $commercial_result['unique_id']){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                        if(in_array($commercial_result['unique_id'], $getIDXmlResidential)){
\n                                                            foreach($getChckStatusXmlCommercialALL as $resData){
\n                                                                if($resData['id'] == $commercial_result['unique_id']){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n    
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$commercial_result['status'].']</td>';
\n    
\n                                                        if(in_array($commercial_result['unique_id'], $apiCommercialID)){
\n                                                            foreach($getChckStatusAPICommercialALL as $resData){
\n                                                                if($resData['id'] == $commercial_result['unique_id']){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                    echo '</tr>';
\n                                                }
\n                                            }else{
\n                                                echo '<tr>';
\n                                                    echo '<td>No result found.</td>';
\n                                                echo '</tr>';
\n                                            }
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                    <?php break; ?>
\n                    <?php case 'vaultre': ?>
\n                        <!-- RESIDENTIAL -->
\n                        <button class="accordion <?php if($property==='residential'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Residential (<?= $CountAllResidentialAPI; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php
\n                                        $AllResidentialAPIduplicate_id = [];
\n                                        foreach($AllResidentialAPI as $ResidentialAPI_result){
\n
\n                                            $residentialID_api = $ResidentialAPI_result['propID'];
\n
\n                                            if(!in_array($residentialID_api, $AllResidentialAPIduplicate_id)){
\n
\n                                                echo '<tr '.((!in_array($ResidentialAPI_result['propID'], $siteResidentialID) || !in_array($ResidentialAPI_result['propID'], $getIDXmlResidential) || !in_array($ResidentialAPI_result['propID'], $feedResidentialID)) ? 'class="status-red"' : '').'>';
\n                                                    echo '<td>'.$ResidentialAPI_result['propID'].'</td>';
\n                                                    echo '<td>'.$ResidentialAPI_result['address'].'</td>';
\n                                                    
\n                                                    if(in_array($ResidentialAPI_result['propID'], $siteResidentialID)){
\n                                                        foreach($getChckStatusPostResidentialALL as $resData){
\n                                                            if($resData['id'] == $ResidentialAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($ResidentialAPI_result['propID'], $getIDXmlResidential)){
\n                                                        foreach($getChckStatusXmlResidentialALL as $resData){
\n                                                            if($resData['id'] == $ResidentialAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($ResidentialAPI_result['propID'], $feedResidentialID)){
\n                                                        foreach($getChckStatusDBResidentialALL as $resData){
\n                                                            if($resData['id'] == $ResidentialAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$ResidentialAPI_result['status'].']</td>';
\n                                                echo '</tr>';
\n
\n                                                array_push($AllResidentialAPIduplicate_id, $residentialID_api);
\n                                            }
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- LAND -->
\n                        <button class="accordion <?php if($property==='land'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Land (<?= $CountAllLandAPI; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        $AllLandAPIduplicate_id = [];
\n                                        foreach($AllLandAPI as $LandAPI_result){
\n
\n                                            $landID_api = $LandAPI_result['propID'];
\n
\n                                            if(!in_array($landID_api, $AllLandAPIduplicate_id)){
\n
\n                                                echo '<tr '.((!in_array($LandAPI_result['propID'], $siteLandID) || !in_array($LandAPI_result['propID'], $getIDXmlLand) || !in_array($LandAPI_result['propID'], $feedLandID)) ? 'class="status-red"' : '').'>';
\n                                                    echo '<td>'.$LandAPI_result['propID'].'</td>';
\n                                                    echo '<td>'.$LandAPI_result['address'].'</td>';
\n                                                    
\n                                                    if(in_array($LandAPI_result['propID'], $siteLandID)){
\n                                                        foreach($getChckStatusPostLandALL as $resData){
\n                                                            if($resData['id'] == $LandAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($LandAPI_result['propID'], $getIDXmlLand)){
\n                                                        foreach($getChckStatusXmlLandALL as $resData){
\n                                                            if($resData['id'] == $LandAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($LandAPI_result['propID'], $feedLandID)){
\n                                                        foreach($getChckStatusDBLandALL as $resData){
\n                                                            if($resData['id'] == $LandAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$LandAPI_result['status'].']</td>';
\n                                                echo '</tr>';
\n
\n                                                array_push($AllLandAPIduplicate_id, $landID_api);
\n                                            }
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- RENTAL -->
\n                        <button class="accordion <?php if($property==='rental'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Rental (<?= $CountAllRentalAPI; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        $AllRentalAPIduplicate_id = [];
\n                                        foreach($AllRentalAPI as $RentalAPI_result){
\n
\n                                            $rentalID_api = $RentalAPI_result['propID'];
\n
\n                                            if(!in_array($rentalID_api, $AllRentalAPIduplicate_id)){
\n
\n                                                echo '<tr '.((!in_array($RentalAPI_result['propID'], $siteRentalID) || !in_array($RentalAPI_result['propID'], $getIDXmlRental) || !in_array($RentalAPI_result['propID'], $feedRentalID)) ? 'class="status-red"' : '').'>';
\n                                                    echo '<td>'.$RentalAPI_result['propID'].'</td>';
\n                                                    echo '<td>'.$RentalAPI_result['address'].'</td>';
\n                                                    
\n                                                    if(in_array($RentalAPI_result['propID'], $siteRentalID)){
\n                                                        foreach($getChckStatusPostRentalALL as $resData){
\n                                                            if($resData['id'] == $RentalAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($RentalAPI_result['propID'], $getIDXmlRental)){
\n                                                        foreach($getChckStatusXmlRentalALL as $resData){
\n                                                            if($resData['id'] == $RentalAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($RentalAPI_result['propID'], $feedRentalID)){
\n                                                        foreach($getChckStatusDBRentalALL as $resData){
\n                                                            if($resData['id'] == $RentalAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$RentalAPI_result['status'].']</td>';
\n                                                echo '</tr>';
\n                                                
\n                                                array_push($AllRentalAPIduplicate_id, $rentalID_api);
\n                                            } 
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- COMMERCIAL -->
\n                        <button class="accordion <?php if($property==='commercial'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Commercial (<?= $CountAllCommercialAPI; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        foreach($AllCommercialAPI as $CommercialAPI_result){
\n                                            if($CommercialAPI_result){
\n                                                echo '<tr '.((!in_array($CommercialAPI_result['propID'], $siteCommercialID) || !in_array($CommercialAPI_result['propID'], $getIDXmlResidential) || !in_array($CommercialAPI_result['propID'], $feedCommercialID)) ? 'class="status-red"' : '').'>';
\n                                                    echo '<td>'.$CommercialAPI_result['propID'].'</td>';
\n                                                    echo '<td>'.$CommercialAPI_result['address'].'</td>';
\n                                                    
\n                                                    if(in_array($CommercialAPI_result['propID'], $siteCommercialID)){
\n                                                        foreach($getChckStatusPostCommercialALL as $resData){
\n                                                            if($resData['id'] == $CommercialAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($CommercialAPI_result['propID'], $getIDXmlResidential)){
\n                                                        foreach($getChckStatusXmlCommercialALL as $resData){
\n                                                            if($resData['id'] == $CommercialAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($CommercialAPI_result['propID'], $feedCommercialID)){
\n                                                        foreach($getChckStatusDBCommercialALL as $resData){
\n                                                            if($resData['id'] == $CommercialAPI_result['propID']){
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['status'].']</td>';
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n    
\n                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$CommercialAPI_result['status'].']</td>';
\n                                                echo '</tr>';
\n
\n                                            }else{
\n                                                echo '<tr>';
\n                                                    echo '<td>No result found.</td>';
\n                                                echo '</tr>';
\n                                            }
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                    <?php break;?>
\n                    <?php case 'composite': ?>
\n                        <!-- RESIDENTIAL -->
\n                        <button class="accordion <?php if($property==='residential'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Residential</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                    <?php
\n                                        usort($AllCompositeResidential, function($a, $b){
\n                                                $a1 = str_replace('L', '', $a['uniq_id']);
\n                                                $b1 = str_replace('L', '', $b['uniq_id']);
\n                                            return $a1 > $b1; 
\n                                        });
\n
\n                                        $Residentialduplicate_id = [];
\n                                        foreach($AllCompositeResidential as $key => $AllCompositeResidential_result){
\n
\n                                            $residentialID_c = $AllCompositeResidential_result['uniq_id'];
\n
\n                                            if(!in_array($residentialID_c, $Residentialduplicate_id)){
\n                                                
\n                                                echo '<tr '.((!in_array($AllCompositeResidential_result['uniq_id'], $siteResidentialID) || !in_array($AllCompositeResidential_result['uniq_id'], $getIDXmlResidential) || !in_array($AllCompositeResidential_result['uniq_id'], $feedResidentialID)) ? 'class="status-red"' : '').'>';
\n                                                    echo '<td>'.$AllCompositeResidential_result['uniq_id'].'</td>';
\n                                                    echo '<td>'.$AllCompositeResidential_result['address'].'</td>';
\n
\n                                                    if(in_array($AllCompositeResidential_result['uniq_id'], $siteResidentialID)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeResidential_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($AllCompositeResidential_result['uniq_id'], $getIDXmlResidential)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeResidential_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($AllCompositeResidential_result['uniq_id'], $feedResidentialID)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeResidential_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($AllCompositeResidential_result['uniq_id'], $apiResidentialID)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeResidential_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                echo '</tr>';
\n                                                
\n                                                array_push($Residentialduplicate_id, $residentialID_c);
\n                                            }
\n
\n                                        }
\n                                    ?>
\n                                <tbody>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- LAND -->
\n                        <button class="accordion <?php if($property==='land'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Land</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php
\n                                        usort($AllCompositeLand, function($a, $b){
\n                                                $a1 = str_replace('L', '', $a['uniq_id']);
\n                                                $b1 = str_replace('L', '', $b['uniq_id']);
\n                                            return $a1 > $b1; 
\n                                        });
\n
\n                                        $Landduplicate_id = [];
\n                                        foreach($AllCompositeLand as $key => $AllCompositeLand_result){
\n
\n                                            $landID_c = $AllCompositeLand_result['uniq_id'];
\n
\n                                            if(!in_array($landID_c, $Landduplicate_id)){
\n                                                
\n                                                echo '<tr '.((!in_array($AllCompositeLand_result['uniq_id'], $siteLandID) || !in_array($AllCompositeLand_result['uniq_id'], $getIDXmlLand) || !in_array($AllCompositeLand_result['uniq_id'], $feedLandID) ) ? 'class="status-red"' : '').'>';
\n                                                    echo '<td>'.$AllCompositeLand_result['uniq_id'].'</td>';
\n                                                    echo '<td>'.$AllCompositeLand_result['address'].'</td>';
\n
\n                                                    if(in_array($AllCompositeLand_result['uniq_id'], $siteLandID)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeLand_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($AllCompositeLand_result['uniq_id'], $getIDXmlLand)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeLand_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($AllCompositeLand_result['uniq_id'], $feedLandID)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeLand_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($AllCompositeLand_result['uniq_id'], $apiLandID)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeLand_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                echo '</tr>';
\n                                                
\n                                                array_push($Landduplicate_id, $landID_c);
\n                                            }
\n
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- RENTAL -->
\n                        <button class="accordion <?php if($property==='rental'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Rental</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php
\n                                        usort($AllCompositeRental, function($a, $b){
\n                                                $a1 = str_replace('R', '', $a['uniq_id']);
\n                                                $b1 = str_replace('R', '', $b['uniq_id']);
\n                                            return $a1 > $b1; 
\n                                        });
\n
\n                                        $Rentalduplicate_id = [];
\n                                        foreach($AllCompositeRental as $key => $AllCompositeRental_result){
\n
\n                                            $rentalID_c = $AllCompositeRental_result['uniq_id'];
\n
\n                                            if(!in_array($rentalID_c, $Rentalduplicate_id)){
\n                                                
\n                                                echo '<tr '.((!in_array($AllCompositeRental_result['uniq_id'], $siteRentalID) || !in_array($AllCompositeRental_result['uniq_id'], $getIDXmlRental) || !in_array($AllCompositeRental_result['uniq_id'], $feedRentalID) || !in_array($AllCompositeRental_result['uniq_id'], $apiRentalID)) ? 'class="status-red"' : '').'>';
\n                                                    echo '<td>'.$AllCompositeRental_result['uniq_id'].'</td>';
\n                                                    echo '<td>'.$AllCompositeRental_result['address'].'</td>';
\n
\n                                                    if(in_array($AllCompositeRental_result['uniq_id'], $siteRentalID)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeRental_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($AllCompositeRental_result['uniq_id'], $getIDXmlRental)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeRental_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($AllCompositeRental_result['uniq_id'], $feedRentalID)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeRental_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                    if(in_array($AllCompositeRental_result['uniq_id'], $apiRentalID)){
\n                                                        echo '<td><span class="dashicons dashicons-yes green"></span> ['.$AllCompositeRental_result['status'].']</td>';
\n                                                    }else{
\n                                                        echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                    }
\n
\n                                                echo '</tr>';
\n                                                
\n                                                array_push($Rentalduplicate_id, $rentalID_c);
\n                                            }
\n
\n                                        }
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- COMMERCIAL -->
\n                        <button class="accordion <?php if($property==='commercial'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Commercial</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Website</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>FeedSync DB</th>
\n                                        <th>VaultRE</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                    <?php break;?>
\n                    <?php case 'images': ?>
\n                        <!-- RESIDENTIAL -->
\n                        <button class="accordion <?php if($property==='residential'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Residential (<?= $residentialCountAllpost; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Status</th>
\n                                        <th>Property Images</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>VaultRE</th>
\n                                        <th>Featured Image</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        if ( $queryProperties->have_posts() ) {
\n
\n                                            while ( $queryProperties->have_posts() ) {
\n                                                $queryProperties->the_post(); 
\n                                    
\n                                                $meta = get_post_meta(get_the_ID());
\n
\n                                                $attachments = get_children(
\n                                                    array(
\n                                                        'post_parent'    => get_the_ID(),
\n                                                        'post_type'      => 'attachment',
\n                                                        'post_mime_type' => 'image',
\n                                                    )
\n                                                );
\n
\n                                                $countGallery = 0;
\n                                                $ErrorcountGallery = 0;
\n                                                $imgvcontent = [];
\n                                            
\n                                                foreach($attachments as $a){
\n                                                    $r = (array)$a;
\n                                
\n                                                    if($r['post_parent'] == get_the_ID()){
\n
\n                                                        $t = get_headers($r['guid'], 1);
\n
\n                                                        if($t[0] == 'HTTP/1.1 404 Not Found'){
\n                                                            $imgv = $r['guid'];
\n                                                            array_push($imgvcontent, $imgv);
\n                                                            $ErrorcountGallery++;
\n                                                        }
\n                                                        
\n                                                        $countGallery++;
\n                                                    }
\n                                                }
\n                                    
\n                                                if($meta['property_status'][0] == 'current' || $meta['property_status'][0] == 'sold'){
\n
\n                                                    if(in_array($meta['property_unique_id'][0], $getIDXmlResidential)){
\n                                                        foreach($getChckStatusXmlResidentialALL as $resData){
\n                                                            if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                
\n                                                                if($resData['image_count'] >= 35 && $countGallery == 35){
\n                                                                    echo '<tr>';
\n                                                                }else{
\n                                                                    if($resData['image_count'] == $countGallery){
\n                                                                        echo '<tr>';
\n                                                                    }else{
\n                                                                        echo '<tr class="status-red">';
\n                                                                    }
\n                                                                }
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        if($meta['property_status'][0] == 'sold'){
\n                                                            echo '<tr>';
\n                                                        }else{
\n
\n                                                            echo '<tr class="status-red">';
\n                                                        }
\n                                                    }
\n
\n                                                    // echo '<tr '.((!in_array($meta['property_unique_id'][0], $apiResidentialID) || empty($attachments)) ? 'class="status-red"' : '').'>';
\n                                                        echo '<td>'.$meta['property_unique_id'][0].'</td>';
\n                                                        echo '<td>'.get_the_title().'</td>';
\n                                                        echo '<td>['.$meta['property_status'][0].']</td>';
\n                                                        // WEBSITE
\n                                                        if ( $attachments ) {
\n
\n                                                            if($ErrorcountGallery > 0){
\n                                                                
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> <small style="color:#ff8383;">['.$ErrorcountGallery.'] Missing</small></td>';
\n                                                            }else{
\n
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> ['.$countGallery.']</td>';
\n                                                            }
\n
\n                                            
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span> [0]</td>';
\n                                                        }
\n                                                        // FEEDSYNC XML
\n                                                        if(in_array($meta['property_unique_id'][0], $getIDXmlResidential)){
\n                                                            foreach($getChckStatusXmlResidentialALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['image_count'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                        // VAULTRE
\n                                                        if(in_array($meta['property_unique_id'][0], $apiResidentialID)){
\n                                                            foreach($getChckStatusAPIResidentialALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['image_count'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                        // FEATURED IMAGE
\n                                                        if (has_post_thumbnail( get_the_ID()) ){
\n                
\n                                                            $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID()), 'thumbnail');
\n                                                            $imgChck = get_headers($image[0], 1);
\n
\n                                                            if($imgChck[0] != 'HTTP/1.1 404 Not Found'){
\n                                                                
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span></td>';
\n                                                            }else{
\n                                                                
\n                                                                echo '<td><span class="dashicons dashicons-yes green"></span> <small style="color:#ff8383;">Missing</small></td>';
\n                                                            }
\n
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                    echo '</tr>';
\n                                                }
\n                                            }
\n                                        }
\n                                        wp_reset_postdata();
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- LAND -->
\n                        <button class="accordion <?php if($property==='land'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Land (<?= $landCountAllpost; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Status</th>
\n                                        <th>Property Images</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>VaultRE</th>
\n                                        <th>Featured Image</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        if ( $queryLand->have_posts() ) {
\n
\n                                            while ( $queryLand->have_posts() ) {
\n                                                $queryLand->the_post(); 
\n                                    
\n                                                $meta = get_post_meta(get_the_ID());
\n
\n                                                $attachments = get_children(
\n                                                    array(
\n                                                        'post_parent'    => get_the_ID(),
\n                                                        'post_type'      => 'attachment',
\n                                                        'post_mime_type' => 'image',
\n                                                    )
\n                                                );
\n
\n                                                $countGallery = 0;
\n                                            
\n                                                foreach($attachments as $a){
\n                                                    $r = (array)$a;
\n                                
\n                                                    if($r['post_parent'] == get_the_ID()){
\n                                                        $countGallery++;
\n                                                    }
\n                                                }
\n                                    
\n                                                if($meta['property_status'][0] == 'current' || $meta['property_status'][0] == 'sold'){
\n                                                    if(in_array($meta['property_unique_id'][0], $getIDXmlLand)){
\n                                                        foreach($getChckStatusXmlLandALL as $resData){
\n                                                            if($resData['id'] == $meta['property_unique_id'][0]){
\n
\n                                                                if($resData['image_count'] >= 35 && $countGallery == 35){
\n                                                                    echo '<tr>';
\n                                                                }else{
\n                                                                    if($resData['image_count'] == $countGallery){
\n                                                                        echo '<tr>';
\n                                                                    }else{
\n                                                                        echo '<tr class="status-red">';
\n                                                                    }
\n                                                                }
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        if($meta['property_status'][0] == 'sold'){
\n                                                            echo '<tr>';
\n                                                        }else{
\n                                                            echo '<tr class="status-red">';
\n                                                        }
\n                                                    }
\n                                                    // echo '<tr '.((!in_array($meta['property_unique_id'][0], $apiLandID) || empty($attachments)) ? 'class="status-red"' : '').'>';
\n                                                        echo '<td>'.$meta['property_unique_id'][0].'</td>';
\n                                                        echo '<td>'.get_the_title().'</td>';
\n                                                        echo '<td>['.$meta['property_status'][0].']</td>';
\n                                                        // WEBSITE
\n                                                        if ( $attachments ) {
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$countGallery.']</td>';
\n                                            
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span> [0]</td>';
\n                                                        }
\n                                                        // FEEDSYNC XML
\n                                                        if(in_array($meta['property_unique_id'][0], $getIDXmlLand)){
\n                                                            foreach($getChckStatusXmlLandALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['image_count'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                        // VAULTRE
\n                                                        if(in_array($meta['property_unique_id'][0], $apiLandID)){
\n                                                            foreach($getChckStatusAPILandALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['image_count'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                        // FEATURED IMAGE
\n                                                        if (has_post_thumbnail( get_the_ID()) ){
\n                
\n                                                            $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID()), 'thumbnail');
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span></td>';
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                    echo '</tr>';
\n                                                }
\n                                            }
\n                                        }
\n                                        wp_reset_postdata();
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- RENTAL -->
\n                        <button class="accordion <?php if($property==='rental'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Rental (<?= $rentalCountAllpost; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Status</th>
\n                                        <th>Property Images</th>
\n                                        <th>FeedSync XML</th>
\n                                        <th>VaultRE</th>
\n                                        <th>Featured Image</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        if ( $queryRental->have_posts() ) {
\n
\n                                            while ( $queryRental->have_posts() ) {
\n                                                $queryRental->the_post(); 
\n                                    
\n                                                $meta = get_post_meta(get_the_ID());
\n
\n                                                $attachments = get_children(
\n                                                    array(
\n                                                        'post_parent'    => get_the_ID(),
\n                                                        'post_type'      => 'attachment',
\n                                                        'post_mime_type' => 'image',
\n                                                    )
\n                                                );
\n
\n                                                $countGallery = 0;
\n                                            
\n                                                foreach($attachments as $a){
\n                                                    $r = (array)$a;
\n                                
\n                                                    if($r['post_parent'] == get_the_ID()){
\n                                                        $countGallery++;
\n                                                    }
\n                                                }
\n                                    
\n                                                if($meta['property_status'][0] == 'current'){
\n
\n                                                    if(in_array($meta['property_unique_id'][0], $getIDXmlRental)){
\n                                                        foreach($getChckStatusXmlRentalALL as $resData){
\n                                                            if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                
\n                                                                if($resData['image_count'] >= 35 && $countGallery == 35){
\n                                                                    echo '<tr>';
\n                                                                }else{
\n                                                                    if($resData['image_count'] == $countGallery){
\n                                                                        echo '<tr>';
\n                                                                    }else{
\n                                                                        echo '<tr class="status-red">';
\n                                                                    }
\n                                                                }
\n                                                            }
\n                                                        }
\n                                                    }else{
\n                                                        if($meta['property_status'][0] == 'sold'){
\n                                                            echo '<tr>';
\n                                                        }else{
\n                                                            echo '<tr class="status-red">';
\n                                                        }
\n                                                    }
\n                                                    // echo '<tr '.((!in_array($meta['property_unique_id'][0], $apiRentalID) || empty($attachments)) ? 'class="status-red"' : '').'>';
\n                                                        echo '<td>'.$meta['property_unique_id'][0].'</td>';
\n                                                        echo '<td>'.get_the_title().'</td>';
\n                                                        echo '<td>['.$meta['property_status'][0].']</td>';
\n                                                        // WEBSITE
\n                                                        if ( $attachments ) {
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$countGallery.']</td>';
\n                                            
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span> [0]</td>';
\n                                                        }
\n                                                        // FEEDSYNC XML
\n                                                        if(in_array($meta['property_unique_id'][0], $getIDXmlRental)){
\n                                                            foreach($getChckStatusXmlRentalALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['image_count'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                        // VAULTRE
\n                                                        if(in_array($meta['property_unique_id'][0], $apiRentalID)){
\n                                                            foreach($getChckStatusAPIRentalALL as $resData){
\n                                                                if($resData['id'] == $meta['property_unique_id'][0]){
\n                                                                    echo '<td><span class="dashicons dashicons-yes green"></span> ['.$resData['image_count'].']</td>';
\n                                                                }
\n                                                            }
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n                                                        // FEATURED IMAGE
\n                                                        if (has_post_thumbnail( get_the_ID()) ){
\n                
\n                                                            $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID()), 'thumbnail');
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span></td>';
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                    echo '</tr>';
\n                                                }
\n                                            }
\n                                        }
\n                                        wp_reset_postdata();
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                        <!-- COMMERCIAL -->
\n                        <button class="accordion <?php if($property==='commercial'):?>active<?php endif; ?>">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <tr>
\n                                    <td><span class="dashicons dashicons-arrow-right"></span>Commercial (<?= $commercialCountAllpost; ?>)</td>
\n                                </tr>
\n                            </table>
\n                        </button>
\n                        <div class="panel">
\n                            <table class="wp-list-table widefat fixed striped table-view-list posts">
\n                                <thead>
\n                                    <tr>
\n                                        <th>Property ID</th>
\n                                        <th style="width: 430px;">Address</th>
\n                                        <th>Status</th>
\n                                        <th>Property Images</th>
\n                                        <th>VaultRE</th>
\n                                        <th>Featured Image</th>
\n                                    </tr>
\n                                </thead>
\n                                <tbody>
\n                                    <?php 
\n                                        if ( $queryCommercial->have_posts() ) {
\n
\n                                            while ( $queryCommercial->have_posts() ) {
\n                                                $queryCommercial->the_post(); 
\n                                    
\n                                                $meta = get_post_meta(get_the_ID());
\n
\n                                                $attachments = get_children(
\n                                                    array(
\n                                                        'post_parent'    => get_the_ID(),
\n                                                        'post_type'      => 'attachment',
\n                                                        'post_mime_type' => 'image',
\n                                                    )
\n                                                );
\n                                    
\n                                                if($meta['property_status'][0] == 'current' || $meta['property_status'][0] == 'sold' || $meta['property_status'][0] == 'leased'){
\n                                                    echo '<tr>';
\n                                                        echo '<td>'.$meta['property_unique_id'][0].'</td>';
\n                                                        echo '<td>'.get_the_title().'</td>';
\n
\n                                                        if ( $attachments ) {
\n
\n                                                            $countGallery = 0;
\n                                            
\n                                                            foreach($attachments as $a){
\n                                                                $r = (array)$a;
\n                                            
\n                                                                if($r['post_parent'] == get_the_ID()){
\n                                                                    $countGallery++;
\n                                                                }
\n                                                            }
\n                                        
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span> ['.$countGallery.']</td>';
\n                                            
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span> [0]</td>';
\n                                                        }
\n
\n                                                        if (has_post_thumbnail( get_the_ID()) ){
\n                
\n                                                            $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID()), 'thumbnail');
\n                                                            echo '<td><span class="dashicons dashicons-yes green"></span></td>';
\n                                                        }else{
\n                                                            echo '<td><span class="dashicons dashicons-no red"></span></td>';
\n                                                        }
\n
\n                                                    echo '</tr>';
\n                                                }
\n                                            }
\n                                        }else{
\n                                            echo '<tr>';
\n                                                echo '<td>No result found.</td>';
\n                                            echo '</tr>';
\n                                        }
\n                                        wp_reset_postdata();
\n                                    ?>
\n                                </tbody>
\n                            </table>
\n                        </div>
\n                    <?php break; ?>
\n                    <?php default: ?>
\n                        <div style="padding-top: 30px;">
\n                            <table class="wp-list-table widefat fixed striped table-view-list">
\n                                <thead>
\n                                    <tr>
\n                                        <th><a href="?page=property-sync-checker&tab=website">Website</a></th>
\n                                        <th><a href="?page=property-sync-checker&tab=feedsyncxml">FeedSync XML</a></th>
\n                                        <th><a href="?page=property-sync-checker&tab=feedsync">FeedSync DB</a></th>
\n                                        <th><a href="?page=property-sync-checker&tab=vaultre">VaultRE</a></th>
\n                                    </tr>
\n                                </thead>
\n
\n                                <tbody>
\n                                    <tr>
\n                                        <td><a href="?page=property-sync-checker&tab=website&property=residential"><strong>Residential:</strong> current (<?= $totalProperties_current; ?>), sold (<?= $totalProperties_sold; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=feedsyncxml&property=residential"><strong>Residential:</strong> current (<?= $getcountXmlResidential_current; ?>), sold (<?= $getcountXmlResidential_sold; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=feedsync&property=residential"><strong>Residential:</strong> current (<?= $residentialCount_current; ?>), sold (<?= $residentialCount_sold; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=vaultre&property=residential"><strong>Residential:</strong> current (<?= ($CountAllResidentialVaultRE ? $CountAllResidentialVaultRE : '0') ?>)</a></td>
\n                                    </tr>
\n                                    <tr>
\n                                        <td><a href="?page=property-sync-checker&tab=website&property=land"><strong>Land:</strong> current (<?= $totalLand_current; ?>), sold (<?= $totalLand_sold; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=feedsyncxml&property=land"><strong>Land:</strong> current (<?= $getcountXmlLand_current; ?>), sold (<?= $getcountXmlLand_sold; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=feedsync&property=land"><strong>Land:</strong> current (<?= $landCount_current; ?>), sold (<?= $landCount_sold; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=vaultre&property=land"><strong>Land:</strong> current (<?= ($arrayLand_current['totalItems'] ? $arrayLand_current['totalItems'] : '0') ?>)</a></td>
\n                                    </tr>
\n                                    <tr>
\n                                        <td><a href="?page=property-sync-checker&tab=website&property=rental"><strong>Rentals:</strong> current (<?= $totalRental_current; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=feedsyncxml&property=rental"><strong>Rentals:</strong> current (<?= $getcountXmlRental_current; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=feedsync&property=rental"><strong>Rentals:</strong> current (<?= $rentalCount_current; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=vaultre&property=rental"><strong>Rentals:</strong> current (<?= ($apiRentalOverviewCount ? $apiRentalOverviewCount : '0') ?>)</a></td>
\n                                    </tr>
\n                                    <tr>
\n                                        <td><a href="?page=property-sync-checker&tab=website&property=commercial"><strong>Commercial</strong> (<?= $totalCommercial; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=feedsyncxml&property=commercial"><strong>Commercial:</strong> (<?= $getcountXmlCommercial; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=feedsync&property=commercial"><strong>Commercial</strong> (<?= $commercialCount; ?>)</a></td>
\n                                        <td><a href="?page=property-sync-checker&tab=vaultre&property=commercial"><strong>Commercial:</strong> current (<?= ($arrayCommercial_current['totalItems'] || $arrayCommercial_leased['totalItems'] ? $arrayCommercial_current['totalItems'] + $arrayCommercial_leased['totalItems'] : '0') ?>)</a></td>
\n                                    </tr>
\n                                </tbody>
\n                            </table>
\n                            <div>
\n                                <h4>VaultRE API Error Logs:</h4>
\n                                <p style="font-family: monospace;">
\n                                    <?php 
\n                                        $ResidentialResponseCurr = (array)$resResidential_current;
\n                                        // $ResidentialResponseSold = (array)$resResidential_sold;
\n                                        $RentalResponseCurr = (array)$resRental_current;
\n                                        $LandResponseCurr = (array)$resLand_current;
\n                                        $CommercialResponseCurr = (array)$resCommercial_current;
\n                                        $CommercialResponseLeased = (array)$resCommercial_leased;
\n                                        // $LandResponseSold = (array)$resLand_sold;
\n
\n                                        if($ResidentialResponseCurr['errors']['http_request_failed'][0]){
\n                                            
\n                                            echo 'Residential (Current) :'. $ResidentialResponseCurr['errors']['http_request_failed'][0];
\n                                        }
\n                                        elseif($RentalResponseCurr['errors']['http_request_failed'][0]){
\n                                            
\n                                            echo 'Rental (Current) :'. $RentalResponseCurr['errors']['http_request_failed'][0];
\n                                        }
\n                                        elseif($LandResponseCurr['errors']['http_request_failed'][0]){
\n                                            
\n                                            echo 'Land (Current) :'. $LandResponseCurr['errors']['http_request_failed'][0];
\n                                        }
\n                                        elseif($CommercialResponseCurr['errors']['http_request_failed'][0]){
\n                                            
\n                                            echo 'Commercial (Current) :'. $CommercialResponseCurr['errors']['http_request_failed'][0];
\n                                        }
\n                                        elseif($CommercialResponseLeased['errors']['http_request_failed'][0]){
\n                                            
\n                                            echo 'Commercial (Leased) :'. $CommercialResponseLeased['errors']['http_request_failed'][0];
\n                                        }
\n                                        else{
\n                                            echo '----- No Logs -----';
\n                                        }
\n                                    ?>
\n                                </p>
\n                            </div>
\n                        </div>
\n                    <?php break; ?>
\n                <?php endswitch; ?>
\n        </div>
\n    </div>
\n    <script>
\n        jQuery('.accordion.active').next('.panel').show();
\n        jQuery('.accordion.active').find('span').css('transform', 'rotate(90deg)');
\n        jQuery('.accordion').each(function(i){
\n            jQuery(this).on('click', function(){
\n                jQuery(this).toggleClass('active');
\n                var panel = jQuery(this).next('.panel');
\n                var icoon = jQuery(this).find('span');
\n                if(panel.attr('style') === 'display: block;'){
\n                    panel.hide();
\n                    icoon.css('transform', 'rotate(0deg)');
\n                }else{
\n                    panel.show();
\n                    icoon.css('transform', 'rotate(90deg)');
\n                }
\n            });
\n        });
\n        jQuery('#email_cron').on('click', function(){
\n            jQuery('#ppg_success').hide();
\n            jQuery('.ppg-spinner').show();
\n            var data = {
\n                'action': 'sendreportlog_cron_function'
\n            };
\n
\n            jQuery.post(ajaxurl, data, function (response) {
\n                console.log('Response: ' + response);
\n                if(response){
\n                    jQuery('.ppg-spinner').hide();
\n                    jQuery('#ppg_success').show();
\n                }
\n            });
\n        });
\n    </script>
\n<?php }
\n
\n
