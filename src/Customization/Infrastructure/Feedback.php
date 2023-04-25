<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\UseCase\Web\CreateFeedback\Feedback as FeedbackAlias;
use App\Shared\Application\ActiveTenant;
use GuzzleHttp\Client;

final class Feedback implements FeedbackAlias
{
    private $client;
    private ActiveTenant $tenant;
    private string $feedbackApiUrl;

    public function __construct(Client $client,
                                ActiveTenant $tenant,
                                string $feedbackApiUrl)
    {
        $this->client = $client;
        $this->tenant = $tenant;
        $this->feedbackApiUrl = $feedbackApiUrl;
    }

    public function create(string $email, string $message, string $name, string $section): void
    {
        $company = $this->tenant->company();
        $this->client->post($this->feedbackApiUrl, [
            'json' => [
                'username' => $name,
                'email' => $email,
                'feedback' => $message,
                'section' => $section,
                'companyId' => sprintf('%s [ID: %s]', $company->alias(), $this->tenant->company()->id()),
            ]
        ]);
    }
}