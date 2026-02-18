<?php

namespace App\Interfaces;

interface StoreBalanceHistoryRepositoryInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    );

    public function getAllPaginated(
        ?string $search,
        int $rowPerPage
    );

    public function getById(
        string $id // karena pakai uuid
    );

    public function create(
        array $data
    );

    public function update(
        string $id,
        array $data
    );
}
