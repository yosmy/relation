<?php

namespace Yosmy;

interface RelateUser
{
    /**
     * @param string    $user
     * @param Related[] $included
     *
     * @return Related[]
     */
    public function relate(
        string $user,
        array $included
    ): array;
}