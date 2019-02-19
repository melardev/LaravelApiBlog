<?php


namespace App\Http\Controllers;


class BaseController extends Controller
{

    protected function sendSuccess($data, $statusCode = 200, $headers = []) {
        return response()->json($data, $statusCode, $headers);
    }

    public function sendSuccessResponse($result, $messages = null) {
        $response = [
            'success' => true,
            //'messages' => $message
        ];
        if ($result !== null)
            $response['data'] = $result;
        if ($messages !== null) {
            if (is_string($messages))
                $response['full_messages'] = [$messages];
            else if (is_array($messages))
                $response['full_messages'] = $messages;
        }


        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessages = [], $code = 404) {
        $response = [
            'success' => false,
            'messages' => $error
        ];

        if (!empty($errorMessages))
            $response['date'] = $errorMessages;

        return response()->json($response, $code);

    }
}