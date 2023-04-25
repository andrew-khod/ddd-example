<?php

namespace App\Identity\UI\Dashboard\Customer;

use App\Identity\Application\Customer\Query\AllCustomerQuery;
use App\Identity\Application\Customer\Query\NotActivatedCustomerByCriteriaQuery;
use App\Identity\Application\Customer\UseCase\Dashboard\BanCustomer\BanCustomerCommand;
use App\Identity\Application\Customer\UseCase\Dashboard\BanCustomer\BanCustomerHandler;
use App\Identity\Application\Customer\UseCase\Dashboard\BanCustomer\UnbanCustomerCommand;
use App\Identity\Application\Customer\UseCase\Dashboard\BanCustomer\UnbanCustomerHandler;
use App\Identity\Application\Customer\UseCase\ResendCustomerActivation\ResendCustomerActivationCommand;
use App\Identity\Application\Customer\UseCase\ResendCustomerActivation\ResendCustomerActivationHandler;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Tag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Tag(name="Customers")
 */
class CustomerController extends AbstractFOSRestController
{
    /**
     * @Post(summary="Customers list", description="Customers list")
     */
//    public function list(CustomerByCriteriaQuery $customerByCriteriaQuery, NotActivatedCustomerByCriteriaQuery $notActivatedCustomerByCriteriaQuery): Response
    public function list(AllCustomerQuery $customerByCriteriaQuery, NotActivatedCustomerByCriteriaQuery $notActivatedCustomerByCriteriaQuery): Response
    {
//        $criteria = new AllCustomerCriteria();
//        $customers = $customerByCriteriaQuery->queryMultiple($criteria);
        $customers = $customerByCriteriaQuery->query();
//        $notActivatedCustomers = $notActivatedCustomerByCriteriaQuery->queryMultiple($criteria);

//        $view = $this->view(new CustomerCollectionPayload($customers, $notActivatedCustomers), Response::HTTP_OK);
        $view = $this->view(new CustomerCollectionPayload($customers), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Ban customer", description="Ban customer")
     */
    public function ban(BanCustomerHandler $banCustomerHandler, Request $request): Response
    {
        $banCustomerHandler->handle(new BanCustomerCommand($request->get('customer')));

        $view = $this->view(null, Response::HTTP_CREATED);

        return $this->handleView($view);
    }

    /**
     * @Delete(summary="Unban customer", description="Unban customer")
     */
    public function unban(UnbanCustomerHandler $banCustomerHandler, Request $request): Response
    {
        $banCustomerHandler->handle(new UnbanCustomerCommand($request->get('customer')));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Resend activation for Customer", description="Resend activation for Customer")
     */
    public function resendActivation(ResendCustomerActivationHandler $resendCustomerActivationHandler, Request $request): Response
    {
        $resendCustomerActivationHandler->handle(new ResendCustomerActivationCommand($request->get('customer')));

        $view = $this->view(null, Response::HTTP_CREATED);

        return $this->handleView($view);
    }
}
