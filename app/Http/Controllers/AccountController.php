<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccountRequest;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function create(CreateAccountRequest $createAccountRequest)
    {
        $user = $createAccountRequest->user();
        $name = "$user->first_name  $user->last_name";
        $email = $user->email;

        $payload = [
            "account_name"=> $name,
            "email"=> $email
        ];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://sagecloud.ng/api/v3/virtual-account/generate',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($payload),
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: '.env(SAGECLOUD_API_KEY)
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
    

        $result = json_decode($response, true);

        if ($result['success'] == true) {
            $account_name = $result['data']['account_details']['account_name'];
            $account_number = $result['data']['account_details']['account_number'];
            $account_reference = $result['data']['account_details']['account_reference'];
            $bank_name = $result['data']['account_details']['bank_name'];

            $account = Account::create([
                'user_id' => $user->id,
                'account_name' => $account_name,
                'account_number'=> $account_number,
                'account_reference'=> $account_reference,
                'bank_name'=> $bank_name
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'account' => $account
                ]
            ]);
        }

            return response()->json([
                'success' => false,
                'message' => 'Failed to create account.'
            ]);
    }
}
