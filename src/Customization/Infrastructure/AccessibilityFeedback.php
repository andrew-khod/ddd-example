<?php

declare(strict_types=1);

namespace App\Customization\Infrastructure;

use App\Customization\Application\UseCase\Web\CreateFeedback\Feedback as FeedbackAlias;
use App\Shared\Application\ActiveTenant;
use GuzzleHttp\Client;

final class AccessibilityFeedback implements FeedbackAlias
{
    private $client;
    private ActiveTenant $tenant;
    private string $feedbackApiUrl;

    public function __construct(Client $client,
                                ActiveTenant $tenant,
                                string $feedbackAccessibilityApiUrl)
    {
        $this->client = $client;
        $this->tenant = $tenant;
        $this->feedbackApiUrl = $feedbackAccessibilityApiUrl;
    }

    public function create(string $email, string $message, string $name, string $section): void
    {
        $company = $this->tenant->company();
        $this->client->post($this->feedbackApiUrl, [
            'json' => [
                'name' => $name,
                'email' => $email,
                'problem' => $message,
                'page' => $section,
                'companyId' => sprintf('%s [ID: %s]', $company->alias(), $this->tenant->company()->id()),
            ]
        ]);
    }
}