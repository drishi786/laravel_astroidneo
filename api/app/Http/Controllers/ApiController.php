<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function index()
    {
        $response = '';
        $start_date = '';
        $end_date = '';
        if(isset($_POST['submit_form'])){
            
            //print_r($_POST); die();
            $start_date = $_POST['s_date'];
            $end_date = $_POST['e_date'];

            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.nasa.gov/neo/rest/v1/feed?start_date=".$start_date."&end_date=".$end_date."&api_key=Y1fi1ye02spLC0KUxauNTHbV0sGLhR05nkz9DslL",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "GET",
              CURLOPT_SSL_VERIFYHOST => 0,
              CURLOPT_SSL_VERIFYPEER => 0,  
              CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: 8d4292b7-f227-af8c-e2eb-a2c90f595802"
              ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);
        }

         return view('show', ['apidata' => $response, 's_date' => $start_date, 'e_date' => $end_date]);
    }
}
