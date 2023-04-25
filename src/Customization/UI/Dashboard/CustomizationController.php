<?php

namespace App\Customization\UI\Dashboard;

use App\Customization\Application\Query\AccessibilityPolicyByCriteriaQuery;
use App\Customization\Application\Query\ContactByCriteriaQuery;
use App\Customization\Application\Query\CookiesPolicyByCriteriaQuery;
use App\Customization\Application\Query\PrivacyPolicyByCriteriaQuery;
use App\Customization\Application\Query\QuestionByCriteriaQuery;
use App\Customization\Application\Query\RuleByCriteriaQuery;
use App\Customization\Application\Query\TermsOfUseByCriteriaQuery;
use App\Customization\Application\UseCase\AssignLanguages\AssignLanguagesCommand;
use App\Customization\Application\UseCase\AssignLanguages\AssignLanguagesHandler;
use App\Customization\Application\UseCase\UpdateAccessibilityPolicy\UpdateAccessibilityPolicyCommand;
use App\Customization\Application\UseCase\UpdateAccessibilityPolicy\UpdateAccessibilityPolicyHandler;
use App\Customization\Application\UseCase\UpdateBrandAndStyle\UpdateBrandAndStyleCommand;
use App\Customization\Application\UseCase\UpdateBrandAndStyle\UpdateBrandAndStyleHandler;
use App\Customization\Application\UseCase\UpdateBrandAndStyle\UpdateBrandAndStyleHeaderCommand;
use App\Customization\Application\UseCase\UpdateBrandAndStyle\UpdateBrandAndStyleHeaderHandler;
use App\Customization\Application\UseCase\UpdateContacts\UpdateContactsCommand;
use App\Customization\Application\UseCase\UpdateContacts\UpdateContactsHandler;
use App\Customization\Application\UseCase\UpdateCookiesPolicy\UpdateCookiesPolicyCommand;
use App\Customization\Application\UseCase\UpdateCookiesPolicy\UpdateCookiesPolicyHandler;
use App\Customization\Application\UseCase\UpdatePrivacyPolicy\UpdatePrivacyPolicyCommand;
use App\Customization\Application\UseCase\UpdatePrivacyPolicy\UpdatePrivacyPolicyHandler;
use App\Customization\Application\UseCase\UpdateQuestions\UpdateQuestionsCommand;
use App\Customization\Application\UseCase\UpdateQuestions\UpdateQuestionsHandler;
use App\Customization\Application\UseCase\UpdateRules\UpdateRulesCommand;
use App\Customization\Application\UseCase\UpdateRules\UpdateRulesHandler;
use App\Customization\Application\UseCase\UpdateTermsOfUse\UpdateTermsOfUseCommand;
use App\Customization\Application\UseCase\UpdateTermsOfUse\UpdateTermsOfUseHandler;
use App\Customization\Infrastructure\AllAccessibilityPolicyCriteria;
use App\Customization\Infrastructure\AllContactCriteria;
use App\Customization\Infrastructure\AllCookiesPolicyCriteria;
use App\Customization\Infrastructure\AllPrivacyPolicyCriteria;
use App\Customization\Infrastructure\AllQuestionCriteria;
use App\Customization\Infrastructure\AllRuleCriteria;
use App\Customization\Infrastructure\AllTermsOfUseCriteria;
use App\Initiative\UI\Web\Initiative\Controller\ImageManager;
use App\Shared\Application\ActiveTenant;
use App\Shared\Application\AssignedToCompanyLanguagesQuery;
use App\Shared\Application\AvailableLanguagesQuery;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Patch;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Tag;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Tag(name="Customization")
 */
class CustomizationController extends AbstractFOSRestController
{
    private ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager();
    }

    /**
     * @Get(summary="FAQ list", description="FAQ list")
     */
    public function questions(QuestionByCriteriaQuery $questionByCriteriaQuery): Response
    {
        $criteria = new AllQuestionCriteria();
        $questions = $questionByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view(new QuestionCollectionPayload($questions), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Patch(summary="Update FAQ", description="Update FAQ")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object", @Property(property="questions", type="array", @Items(type="object",
     *      @Property(property="en", type="object", @Property(property="question", type="string"), @Property(property="answer", type="string")),
     *      @Property(property="fi", type="object", @Property(property="question", type="string"), @Property(property="answer", type="string")),
     * )))))
     */
    public function updateQuestions(UpdateQuestionsHandler $updateQuestionsHandler, UpdateQuestionsRequest $request): Response
    {
        $questions = $request->get('questions');
        $updateQuestionsHandler->handle(new UpdateQuestionsCommand($questions));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Contacts list", description="Contacts list")
     */
    public function contacts(ContactByCriteriaQuery $contactByCriteriaQuery): Response
    {
        $criteria = new AllContactCriteria();
        $contacts = $contactByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view(new ContactCollectionPayload($contacts), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Patch(summary="Update contacts", description="Update contacts")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="array", @Items(type="object",
     *      @Property(property="en", type="object", @Property(property="type", type="string"), @Property(property="value", type="string")),
     *      @Property(property="fi", type="object", @Property(property="type", type="string"), @Property(property="value", type="string")),
     * ))))
     */
    public function updateContacts(UpdateContactsHandler $updateContactsHandler, UpdateContactsRequest $request): Response
    {
        $contacts = $request->getRequest()->toArray();
        $updateContactsHandler->handle(new UpdateContactsCommand($contacts));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Rules and tips list", description="Rules and tips list")
     */
    public function rules(RuleByCriteriaQuery $ruleByCriteriaQuery): Response
    {
        $criteria = new AllRuleCriteria();
        $rules = $ruleByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view(new RuleCollectionPayload($rules), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Update rules and tips", description="Update rules and tips")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="en", type="object", @Property(property="title", type="string"), @Property(property="description", type="string")),
     *      @Property(property="fi", type="object", @Property(property="title", type="string"), @Property(property="description", type="string")),
     * )))
     */
    public function updateRules(UpdateRulesHandler $updateRulesHandler, UpdateRulesRequest $request): Response
    {
        $rules = $request->getRequest()->toArray();
        $updateRulesHandler->handle(new UpdateRulesCommand($rules));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Cookies policy", description="Cookies policy")
     */
    public function cookiesPolicy(CookiesPolicyByCriteriaQuery $cookiesPolicyByCriteriaQuery): Response
    {
        $criteria = new AllCookiesPolicyCriteria();
        $policy = $cookiesPolicyByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view(new CookiesPolicyCollectionPayload($policy), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Update cookies policy", description="Update cookies policy")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="en", type="object", @Property(property="title", type="string"), @Property(property="description", type="string")),
     *      @Property(property="fi", type="object", @Property(property="title", type="string"), @Property(property="description", type="string")),
     * )))
     */
    public function updateCookiesPolicy(UpdateCookiesPolicyHandler $updateCookiesPolicyHandler, UpdateAccessibilityPolicyRequest $request): Response
    {
        $policy = $request->getRequest()->toArray();
        $updateCookiesPolicyHandler->handle(new UpdateCookiesPolicyCommand($policy));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Accessibility policy", description="Accessibility policy")
     */
    public function accessibilityPolicy(AccessibilityPolicyByCriteriaQuery $accessibilityPolicyByCriteriaQuery): Response
    {
        $criteria = new AllAccessibilityPolicyCriteria();
        $policy = $accessibilityPolicyByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view(new AccessibilityPolicyCollectionPayload($policy), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Update accessibility policy", description="Update accessibility policy")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="en", type="object", @Property(property="title", type="string"), @Property(property="description", type="string")),
     *      @Property(property="fi", type="object", @Property(property="title", type="string"), @Property(property="description", type="string")),
     * )))
     */
    public function updateAccessibilityPolicy(UpdateAccessibilityPolicyHandler $updateAccessibilityPolicyHandler, UpdateAccessibilityPolicyRequest $request): Response
    {
        $policy = $request->getRequest()->toArray();
        $updateAccessibilityPolicyHandler->handle(new UpdateAccessibilityPolicyCommand($policy));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Terms of use", description="Terms of use")
     */
    public function termsOfUse(TermsOfUseByCriteriaQuery $termsOfUseByCriteriaQuery): Response
    {
        $criteria = new AllTermsOfUseCriteria();
        $terms = $termsOfUseByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view(new TermsOfUseCollectionPayload($terms), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Update terms of use", description="Update terms of use")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="array", @Items(type="object",
     *      @Property(property="en", type="object", @Property(property="title", type="string"), @Property(property="description", type="string")),
     *      @Property(property="fi", type="object", @Property(property="title", type="string"), @Property(property="description", type="string")),
     * ))))
     */
    public function updateTermsOfUse(UpdateTermsOfUseHandler $updateTermsOfUseHandler, UpdateAccessibilityPolicyRequest $request): Response
    {
        $terms = $request->getRequest()->toArray();
        $updateTermsOfUseHandler->handle(new UpdateTermsOfUseCommand($terms));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Privacy policy", description="Privacy policy")
     */
    public function privacyPolicy(PrivacyPolicyByCriteriaQuery $privacyPolicyByCriteriaQuery): Response
    {
        $criteria = new AllPrivacyPolicyCriteria();
        $policy = $privacyPolicyByCriteriaQuery->queryMultiple($criteria);

        $view = $this->view(new PrivacyPolicyCollectionPayload($policy), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Update privacy policy", description="Update privacy policy")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="en", type="string", description="URL"),
     *      @Property(property="fi", type="string", description="URL"),
     * )))
     */
    public function updatePrivacyPolicy(UpdatePrivacyPolicyHandler $updatePrivacyPolicyHandler, UpdatePrivacyPolicyRequest $request): Response
    {
        $policy = $request->getRequest()->toArray();
        $updatePrivacyPolicyHandler->handle(new UpdatePrivacyPolicyCommand($policy));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Brand and style", description="Brand and style")
     */
    public function brandAndStyle(ActiveTenant $tenant): Response
    {
        $view = $this->view(new BrandAndStylePayload($tenant->company()), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Update brand and style", description="Update brand and style")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="name", type="string"),
     *      @Property(property="alias", type="string"),
     *      @Property(property="url", type="string"),
     *      @Property(property="color", type="string"),
     *      @Property(property="footer", type="string"),
     * )))
     */
    public function updateBrandAndStyle(UpdateBrandAndStyleHandler $updateBrandAndStyleHandler, UpdateBrandAndStyleRequest $request): Response
    {
        $name = $request->get('name');
        $alias = $request->get('alias');
        $url = $request->get('url');
        $color = $request->get('color');
        $footer = $request->get('footer');

        $updateBrandAndStyleHandler->handle(new UpdateBrandAndStyleCommand(
            $name,
            $alias,
            $url,
            $color,
            $footer,
        ));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Update header & logo", description="Update header & logo")
     * @RequestBody(@MediaType(mediaType="multipart/form-data", @Schema(type="object",
     *      @Property(property="header", format="binary", type="string"),
     *      @Property(property="logo", format="binary", type="string"),
     *      @Property(property="logo_second", format="binary", type="string"),
     * )))
     */
    public function updateBrandAndStyleHeader(UpdateBrandAndStyleHeaderHandler $updateBrandAndStyleHeaderHandler, UpdateBrandAndStyleHeaderRequest $request): Response
    {
        $header = $request->getRequest()->files->get('header');
        $logo = $request->getRequest()->files->get('logo');
        $secondLogo = $request->getRequest()->files->get('logo_second');

        if ($header) {
            $header = $this->imageManager->prepare($header)->toArray()[0];
        }

        if ($logo) {
            $logo = $this->imageManager->prepare($logo)->toArray()[0];
        }

        if ($secondLogo) {
            $secondLogo = $this->imageManager->prepare($secondLogo)->toArray()[0];
        }

        $updateBrandAndStyleHeaderHandler->handle(new UpdateBrandAndStyleHeaderCommand($header, $logo, $secondLogo));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Get(summary="Available languages to assign for company and languages already assigned to", description="Available languages to assign for company and languages already assigned to")
     */
    public function languagesConfiguration(AvailableLanguagesQuery $availableLanguagesQuery, AssignedToCompanyLanguagesQuery $assignedToCompanyLanguagesQuery): Response
    {
        $view = $this->view(new LanguageConfigurationPayload(
            $availableLanguagesQuery->query(),
            $assignedToCompanyLanguagesQuery->query(),
        ), Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Post(summary="Assign languages to company", description="Assign languages to company")
     * @RequestBody(@MediaType(mediaType="application/json", @Schema(type="object",
     *      @Property(property="name", type="string"),
     *      @Property(property="alias", type="string"),
     *      @Property(property="url", type="string"),
     *      @Property(property="color", type="string"),
     *      @Property(property="footer", type="string"),
     * )))
     */
    public function assignLanguages(AssignLanguagesHandler $assignLanguagesHandler, AssignLanguagesRequest $request): Response
    {
        $assignLanguagesHandler->handle(new AssignLanguagesCommand($request->getRequest()->toArray()));

        $view = $this->view(null, Response::HTTP_OK);

        return $this->handleView($view);
    }
}
