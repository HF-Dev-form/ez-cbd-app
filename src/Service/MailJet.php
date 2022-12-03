<?php

namespace App\Service;

use Mailjet\Client;
use Mailjet\Resources;

class MailJet
{
    private $api_key = "207e10a471b216d37425e3f8f9cca2ff";
    private $api_key_secret = "16ff8e6986b90c2fd3ddc2eb04e2ba31";

    public function send($to_email, $to_name, $subject, $content)
    {
        $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);
            $body = [
                'Messages' => [
                    [
                        'From' => [
                            'Email' => "ezcbdshop@gmail.com",
                            'Name' => "eZ-CBD"
                        ],
                        'To' => [
                            [
                                'Email' => $to_email,
                                'Name' => $to_name
                            ]
                        ],
                        'TemplateID' => 4388288,
                        'TemplateLanguage' => true,
                        'Subject' => $subject,
                        'Variables' => [
                            'content' => $content
                        ]
                    ]
                ]
            ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}    
