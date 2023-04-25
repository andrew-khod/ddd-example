<?php

namespace App\Customization\UI\Web;

use App\Customization\Application\Query\AccessibilityPolicyByCriteriaQuery;
use App\Customization\Application\Query\ContactByCriteriaQuery;
use App\Customization\Application\Query\CookiesPolicyByCriteriaQuery;
use App\Customization\Application\Query\PrivacyPolicyByCriteriaQuery;
use App\Customization\Application\Query\QuestionByCriteriaQuery;
use App\Customization\Application\Query\RuleByCriteriaQuery;
use App\Customization\Application\Query\TermsOfUseByCriteriaQuery;
use App\Customization\Application\UseCase\Web\CreateFeedback\CreateAccessibilityFeedbackHandler;
use App\Customization\Application\UseCase\Web\CreateFeedback\CreateFeedbackCommand;
use App\Customization\Application\UseCase\Web\CreateFeedback\CreateFeedbackHandler;
use App\Customization\Infrastructure\AllAccessibilityPolicyCriteria;
use App\Customization\Infrastructure\AllCookiesPolicyCriteria;
use App\Customization\Infrastructure\AllPrivacyPolicyCriteria;
use App\Customization\Infrastructure\AllRuleCriteria;
use App\Customization\Infrastructure\AllTermsOfUseCriteria;
use App\Customization\Infrastructure\ContactByLanguageCriteria;
use App\Customization\Infrastructure\QuestionByLanguageCriteria;
use App\Shared\Application\ActiveTenant;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Domain\ActiveLanguage;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Tag;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Tag(name="Configuration")
 */
class KnowledgeController extends AbstractFOSRestController
{
    /**
     * @Get(summary="Questions list", description="Questions list")
     */
//    public function questions(QuestionByCriteriaQuery $questionByCriteriaQuery): Response
    public function questions(QuestionByCriteriaQuery $questionByCriteriaQuery, ActiveLanguage $activeLanguage): Response
    {
//        $criteria = new AllQuestionCriteria();
        $criteria = new QuestionByLanguageCriteria($activeLanguage->language());
        $questions = $questionByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view($questions, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Contacts list", description="Contacts list")
     */
//    public function contacts(ContactByCriteriaQuery $contactByCriteriaQuery): Response
    public function contacts(ContactByCriteriaQuery $contactByCriteriaQuery, ActiveLanguage $activeLanguage): Response
    {
        $criteria = new ContactByLanguageCriteria($activeLanguage->language());
//        $criteria = new AllContactCriteria();
        $contacts = $contactByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view($contacts, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Rules and tips", description="Rules and tips")
     */
    public function rules(RuleByCriteriaQuery $ruleByCriteriaQuery): Response
    {
        $criteria = new AllRuleCriteria();
        $rules = $ruleByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view($rules, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Privacy policy", description="Privacy policy")
     */
    public function privacyPolicy(PrivacyPolicyByCriteriaQuery $privacyPolicyByCriteriaQuery): Response
    {
        $criteria = new AllPrivacyPolicyCriteria();
        $policy = $privacyPolicyByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view($policy, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Cookies policy", description="Cookies policy")
     */
    public function cookiesPolicy(CookiesPolicyByCriteriaQuery $cookiesPolicyByCriteriaQuery): Response
    {
        $criteria = new AllCookiesPolicyCriteria();
        $policy = $cookiesPolicyByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view($policy, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Accessibility policy", description="Accessibility policy")
     */
    public function accessibilityPolicy(AccessibilityPolicyByCriteriaQuery $accessibilityPolicyByCriteriaQuery): Response
    {
        $criteria = new AllAccessibilityPolicyCriteria();
        $policy = $accessibilityPolicyByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view($policy, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Terms of use", description="Terms of use")
     */
    public function termsOfUse(TermsOfUseByCriteriaQuery $termsOfUseByCriteriaQuery): Response
    {
        $criteria = new AllTermsOfUseCriteria();
        $terms = $termsOfUseByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view($terms, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Information and configuration of company", description="Information and configuration of company")
     */
    public function brand(ActiveTenant $tenant, AssignedToCompanyLanguagesQuery $allLanguageQuery): Response
    {
        $view = $this->view(new KnowledgePayload($tenant->company(), $allLanguageQuery->query()), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Make feedback", description="Make feedback")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="email", type="string"),
     *      @Property(property="message", type="string"),
     *      @Property(property="section", type="string"),
     *      @Property(property="name", type="string"),
     *      @Property(property="captcha", type="string"),
     * )))
     */
    public function feedback(CreateFeedbackHandler $createFeedbackHandler, CreateFeedbackRequest $request): Response
    {
        $email = $request->get('email');
        $message = $request->get('message');
        $section = $request->get('section');
        $name = $request->get('name');
        $captcha = $request->get('captcha');

        $createFeedbackHandler->handle(new CreateFeedbackCommand($captcha, $email, $message, $section, $name));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Make accessibility feedback", description="Make accessibility feedback")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="email", type="string"),
     *      @Property(property="message", type="string"),
     *      @Property(property="section", type="string"),
     *      @Property(property="name", type="string"),
     *      @Property(property="captcha", type="string"),
     * )))
     */
    public function feedbackAccessibility(CreateAccessibilityFeedbackHandler $createFeedbackHandler, CreateFeedbackRequest $request): Response
    {
        $email = $request->get('email');
        $message = $request->get('message');
        $section = $request->get('section');
        $name = $request->get('name');
        $captcha = $request->get('captcha');

        $createFeedbackHandler->handle(new CreateFeedbackCommand($captcha, $email, $message, $section, $name));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }
}
