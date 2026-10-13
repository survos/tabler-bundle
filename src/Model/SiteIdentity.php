<?php

/* src/Model/SiteIdentity.php v1.0 */

declare(strict_types=1);

namespace Survos\TablerBundle\Model;

final readonly class SiteIdentity
{
    /**
     * @param array<string,string> $links
     * @param array<string,string> $social
     * @param array<string,string> $meta
     * @param array<string,mixed>  $header
     */
    public function __construct(
        public string $code,
        public string $title,
        public string $description = '',
        public string $abbrHtml = '',
        public ?string $logo = null,
        public ?string $logoSmall = null,
        public ?string $homepageRoute = null,
        public ?string $homepageUrl = null,
        public array $links = [],
        public array $social = [],
        public array $meta = [],
        public array $header = [],
    ) {
    }

    public function brandHtml(): string
    {
        return $this->abbrHtml ?: htmlspecialchars($this->title, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    public function link(string $key): ?string
    {
        return $this->links[$key] ?? null;
    }

    public function social(string $key): ?string
    {
        return $this->social[$key] ?? null;
    }
}
