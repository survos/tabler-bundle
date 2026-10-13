<?php

/* src/Components/Cards/BlogSingleComponent.php v4.8 - Generated 2025-12-30 */

declare(strict_types=1);

namespace Survos\TablerBundle\Components\Cards;

use Survos\TablerBundle\Components\Traits\DataAwareTrait;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(name: 'cards:blog-single', template: '@SurvosTabler/components/cards/blog-single.html.twig')]
final class BlogSingleComponent
{
    use DataAwareTrait;

    public ?int $articleId = 2;
    public ?string $article = 'articles[article-id]';
    public ?string $type = 'none';
    public ?bool $liked = null;
    public ?int $truncate = 100;

    public function __construct(
        ?FixtureService $fixtureService = null,
        ?HttpClientInterface $httpClient = null,
    ) {
        $this->fixtureService = $fixtureService;
        $this->httpClient = $httpClient;
    }
}
