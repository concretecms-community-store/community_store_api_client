<?php

declare(strict_types=1);

namespace CommunityStore\APIClient\Entity;

use CommunityStore\APIClient\Entity;

/**
 * @readonly
 */
class Pagination extends Entity
{
    public int $total;

    public int $count;

    public int $pageSize;

    public int $currentPage;

    public int $totalPages;

    public array $links;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->total = $data['total'];
        $this->count = $data['count'];
        $this->pageSize = $data['per_page'];
        $this->currentPage = $data['current_page'];
        $this->totalPages = $data['total_pages'];
        $this->links = $data['links'] ?? [];
    }

    public function getLink(string $key): string
    {
        return $this->links[$key] ?? '';
    }
}
