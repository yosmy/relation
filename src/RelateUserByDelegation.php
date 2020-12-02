<?php

namespace Yosmy;

/**
 * @di\service()
 */
class RelateUserByDelegation implements RelateUser
{
    /**
     * @var RelateUser[]
     */
    private $relatedUserServices;

    /**
     * @di\arguments({
     *     relatedUserServices: '#yosmy.relate_user'
     * })
     *
     * @param RelateUser[] $relatedUserServices
     */
    public function __construct(array $relatedUserServices)
    {
        $this->relatedUserServices = $relatedUserServices;
    }

    /**
     * {@inheritDoc}
     */
    public function relate(
        string $user,
        array $included
    ): array {
        foreach ($this->relatedUserServices as $relateUser) {
            $newIncluded = $relateUser->relate(
                $user,
                $included
            );

            $newIncluded = $this->putUserKeys($newIncluded);

            if (array_diff_key($included, $newIncluded)) {
                foreach ($newIncluded as $newUser) {
                    $newIncluded = $this->relate(
                        $newUser->getUser(),
                        $newIncluded
                    );
                }
            }

            $included = array_merge(
                $included,
                $newIncluded
            );
        }

        return $included;
    }

    /**
     * @param Related[] $included
     *
     * @return Related[]
     */
    private function putUserKeys(
        array $included
    ): array {
        $tmp = [];

        foreach ($included as $related) {
            $tmp[$related->getUser()] = $related;
        }

        return $tmp;
    }
}