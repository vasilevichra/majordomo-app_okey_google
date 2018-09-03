<?php
/**
 * Main project script
 *
 * @package MajorDoMo
 * @author Vasilevich Roman <vasilevich@gmail.com> https://t.me/vasilevichra
 * @version 0.1
 */
//$json = '
//{
//  "responseId": "76fbc7f6-a7d4-4bcb-9401-3b1525f65775",
//  "queryResult": {
//    "queryText": "kitchen off",
//    "parameters": {
//      "place": ["kitchen"],
//      "status": "off"
//    },
//    "allRequiredParamsPresent": true,
//    "fulfillmentText": "okey",
//    "fulfillmentMessages": [{
//      "text": {
//        "text": ["done"]
//      }
//    }],
//    "outputContexts": [{
//      "name": "projects/alisa486-67236/agent/sessions/ABwppHGDvFQrM2uQ70-7nK-PyHmlf5-xg89x3CWX-2Gwqfszy-1sq17ln5-XyFYQ3oXdDLYintLoafqgJ_Y/contexts/google_assistant_welcome",
//      "parameters": {
//        "status.original": "off",
//        "place": ["kitchen"],
//        "status": "off",
//        "place.original": "kitchen"
//      }
//    }, {
//      "name": "projects/alisa486-67236/agent/sessions/ABwppHGDvFQrM2uQ70-7nK-PyHmlf5-xg89x3CWX-2Gwqfszy-1sq17ln5-XyFYQ3oXdDLYintLoafqgJ_Y/contexts/google_assistant_input_type_voice",
//      "parameters": {
//        "status.original": "off",
//        "place": ["kitchen"],
//        "status": "off",
//        "place.original": "kitchen"
//      }
//    }, {
//      "name": "projects/alisa486-67236/agent/sessions/ABwppHGDvFQrM2uQ70-7nK-PyHmlf5-xg89x3CWX-2Gwqfszy-1sq17ln5-XyFYQ3oXdDLYintLoafqgJ_Y/contexts/actions_capability_audio_output",
//      "parameters": {
//        "status.original": "off",
//        "place": ["kitchen"],
//        "status": "off",
//        "place.original": "kitchen"
//      }
//    }, {
//      "name": "projects/alisa486-67236/agent/sessions/ABwppHGDvFQrM2uQ70-7nK-PyHmlf5-xg89x3CWX-2Gwqfszy-1sq17ln5-XyFYQ3oXdDLYintLoafqgJ_Y/contexts/actions_capability_media_response_audio",
//      "parameters": {
//        "status.original": "off",
//        "place": ["kitchen"],
//        "status": "off",
//        "place.original": "kitchen"
//      }
//    }],
//    "intent": {
//      "name": "projects/alisa486-67236/agent/intents/e88f0eb1-06d6-47d8-b84b-bf0c51b4564e",
//      "displayName": "Lighting"
//    },
//    "intentDetectionConfidence": 1.0,
//    "diagnosticInfo": {
//      "end_conversation": true
//    },
//    "languageCode": "en-us"
//  },
//  "originalDetectIntentRequest": {
//    "source": "google",
//    "version": "2",
//    "payload": {
//      "isInSandbox": true,
//      "surface": {
//        "capabilities": [{
//          "name": "actions.capability.MEDIA_RESPONSE_AUDIO"
//        }, {
//          "name": "actions.capability.AUDIO_OUTPUT"
//        }]
//      },
//      "inputs": [{
//        "rawInputs": [{
//          "query": "talk to Alisa romanovna kitchen off",
//          "inputType": "VOICE"
//        }],
//        "arguments": [{
//          "rawText": "kitchen off",
//          "textValue": "kitchen off",
//          "name": "trigger_query"
//        }, {
//          "rawText": "kitchen",
//          "textValue": "kitchen",
//          "name": "place"
//        }, {
//          "rawText": "off",
//          "textValue": "off",
//          "name": "status"
//        }],
//        "intent": "Lighting"
//      }],
//      "user": {
//        "lastSeen": "2018-09-03T06:45:28Z",
//        "locale": "en-US",
//        "userId": "ABwppHFppoth453AedxEYofoiW7jfAFuCVCVqQLcespnhj8LHy7JUDfxMJtmVZGog6dRnj-I-hzB6m8V5RQ"
//      },
//      "conversation": {
//        "conversationId": "ABwppHGDvFQrM2uQ70-7nK-PyHmlf5-xg89x3CWX-2Gwqfszy-1sq17ln5-XyFYQ3oXdDLYintLoafqgJ_Y",
//        "type": "NEW"
//      }
//    }
//  },
//  "session": "projects/alisa486-67236/agent/sessions/ABwppHGDvFQrM2uQ70-7nK-PyHmlf5-xg89x3CWX-2Gwqfszy-1sq17ln5-XyFYQ3oXdDLYintLoafqgJ_Y"
//}';
//$decoded = json_decode($json, true);

include_once("./config.php");
include_once("./lib/loader.php");

startMeasure('TOTAL'); // start calculation of execution time

include_once(DIR_MODULES . "application.class.php");

if(strcasecmp($_SERVER['REQUEST_METHOD'], 'POST') != 0){
    throw new Exception('Request method must be POST!');
}

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if(strcasecmp($contentType, 'application/json') != 0){
    throw new Exception('Content type must be: application/json');
}

$raw_content = trim(file_get_contents("php://input"));
$decoded = json_decode($raw_content, true);

if (!is_array($decoded)) {
    throw new Exception('Received content contained invalid JSON!');
}

if ($decoded["queryResult"]["allRequiredParamsPresent"] == false) {
    throw new Exception("Received content doesn't contain all required parameters!");
}

switch ($decoded["queryResult"]["intent"]["displayName"]) {

    case "Lighting":
        $places = $decoded["queryResult"]["parameters"]["place"];
        $status = $decoded["queryResult"]["parameters"]["status"];

        foreach ($places as $place) {
            cm("light". ucfirst($place).".turn". ucfirst($status));
        }
        break;

    default:
        echo "Unknown dialogflow.com intent!";
}

//file_put_contents("outputfile.txt", file_get_contents("php://input"));

endMeasure('TOTAL'); // end calculation of execution time