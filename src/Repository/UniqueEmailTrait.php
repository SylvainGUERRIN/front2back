<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;

trait UniqueEmailTrait
{
    /**
     * @param array<string, mixed> $criteria
     *
     * @return array<User>
     */
    public function findByUniqueEmail(array $criteria): array
    {
        return $this->em->getRepository(User::class)->findBy($criteria);
    }
}
