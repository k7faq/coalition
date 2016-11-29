<?php

// If I was certain as to which server was hosting this a simple check to verify is AJAX:
//if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

/**
 *  Following is a very basic function that receives data from an AJAX Post command in index.php
 *
 *  Upon verification the request is a Post request (AJAX not detected, see note above) this function
 *  will look for an existing file (user adjustable) and convert its contents to a PHP simple array.
 *
 *  If the file does not yet exist a new array is created. The data is added to the array and then the
 *  new file is created containing the data.
 */
if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    require('config.php');

    if( isset($_POST['action']) && $_POST['action'] === 'updateOnly')
    {
        unset($_POST['action']);

        $data = array(); $i;
        foreach($_POST as $key => $value)
        {
            $dataArray[$key] = $value;
            var_dump($dataArray);
        }

        $fileData = json_encode($data);
        if( writeToFile( $fileData, $fileName ) )
        {
            $response['status'] = 'success';
        }
        header('Content-type: application/json');
        echo json_encode($response);
        exit;
    }

    // check if file exists
    if (file_exists($fileName)) {
        $fileData = file_get_contents( $fileName );
        if ( $fileData ) {
            $data = json_decode( $fileData, true );
        }
    } else
        $data = array();

    // append data

    $data[] = [
        'productName'   => filter_var( $_POST[ 'productName' ], FILTER_SANITIZE_STRING),
        'qtyInStock'    => filter_var( $_POST[ 'qtyInStock' ], FILTER_SANITIZE_NUMBER_INT ),
        'pricePerItem'  => filter_var( $_POST[ 'pricePerItem' ], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ),
        'submitted'     => date('Y-m-d H:i:s'),
    ];

    if(count($data)>1)
        uasort( $data, 'sortByTime' );

    $fileData   = json_encode( $data );

    if( writeToFile( $fileData, $fileName ) )
    {
        $response['status'] = 'success';
    }

    header('Content-type: application/json');
    echo json_encode($response);

    // free memory
    $fileName   = null;
    $fileData   = null;
    $data       = null;
    $result     = null;
}

/**
 * Writes data to file
 * @param $fileData
 * @param $fileName
 * @return int
 */
function writeToFile( $fileData, $fileName )
{
    $result     = file_put_contents( $fileName, $fileData );
    return $result;
}

/**
 * Call back function for sorting arrays
 *
 * @param $a
 * @param $b
 * @return mixed
 */
function sortByTime( $a, $b ) {
    return $a[ 'submitted' ] + $b[ 'submitted' ];
}