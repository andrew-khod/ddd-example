<?php

declare(strict_types=1);

namespace App\Initiative\Application\Initiative\Query;

use App\Identity\Domain\Customer\BannedCustomer;
use App\Initiative\Domain\Initiative\InitiativeId;
use Doctrine\ORM\Query\Expr\Comparison;
use Happyr\DoctrineSpecification\Spec;
use Happyr\DoctrineSpecification\Specification\BaseSpecification;

final class InitiativeByIdCriteria extends BaseSpecification implements InitiativeCriteria
{
    private array $initiatives;

    public function __construct(InitiativeId ...$initiatives)
    {
        parent::__construct();

        // TODO using array of EntityId is impossible with findBy(), find the proper way
        $this->initiatives = array_map(fn (InitiativeId $id) => $id->toBinary(), $initiatives);
    }

    protected function getSpec()
    {
        return Spec::andX(
            Spec::leftJoin('comments', 'comments'),
            Spec::leftJoin('customer', 'comment_customer', 'comments'),
            Spec::leftJoin('images', 'images'),
//            Spec::leftJoin('followers', 'followers'),
            Spec::addSelect(
                Spec::selectEntity('comments'),
                Spec::selectEntity('images'),
//                Spec::selectEntity('comment_customer'),
//                Spec::selectEntity('followers'),
            ),
//            Spec::not(Spec::instanceOfX(BannedCustomer::class, 'comments.customer')),
            Spec::orX(
                Spec::not(Spec::instanceOfX(BannedCustomer::class, 'comments.customer')),
                Spec::isNull('customer', 'comments')
            ),
//            Spec::addSelect('comments', 'images'),
//            Spec::addSelect(Spec::field('comments')),
//            Spec::addSelect(Spec::field('comments', 'comments')),
//            Spec::instanceOfX(Customer::class, 'followers'),
            Spec::in(Spec::field('id'), $this->initiatives),
//            Spec::eq(Spec::field('id'), $this->initiatives),
//            Spec::eq(Spec::field('id'), $this->initiatives[0]),
            Spec::orderBy(Spec::field('created'), 'DESC', 'comments'),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->initiatives,
        ];
    }

    public function operatorFor(string $field): ?string
    {
        return Comparison::EQ;
    }

//    public function sort(): array|string
    public function sort(): ?string
    {
        return null;
//        return [];
    }
}
