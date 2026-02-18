<?php

namespace App\Interfaces;

use Illuminate\Http\UploadedFile;

interface WithdrawalRepositoryInterface
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

    public function approve(
        string $id,
        UploadedFile $proof
    );
}
