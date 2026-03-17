<?php
/* config/component-services.php v4.8 - Generated 2026-03-17 */
/* Import this file in SurvosTablerBundle::loadExtension() */

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Survos\TablerBundle\Components;
use Survos\TablerBundle\Service\FixtureService;
use Symfony\Contracts\HttpClient\HttpClientInterface;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->autowire()
        ->autoconfigure();

    // === Pure UI components (HTML/CSS only) ===
    $services->set(Components\Ui\AccordionComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Ui\ProgressComponent::class);
    $services->set(Components\Ui\RangeComponent::class);
    $services->set(Components\Ui\SelectComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Ui\ModalComponent::class);
    $services->set(Components\Ui\ButtonGroupComponent::class);
    $services->set(Components\Ui\StarsComponent::class);
    $services->set(Components\Ui\StatusIndicatorComponent::class);
    $services->set(Components\Ui\CardTitleComponent::class);
    $services->set(Components\Ui\TagComponent::class);
    $services->set(Components\Ui\FlagComponent::class);
    $services->set(Components\Ui\PaymentComponent::class);
    $services->set(Components\Ui\NavSegmentedComponent::class);
    $services->set(Components\Ui\DropdownMenuComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Ui\CloseComponent::class);
    $services->set(Components\Ui\FooterComponent::class);
    $services->set(Components\Ui\HeaderComponent::class);
    $services->set(Components\Ui\TrendingComponent::class);
    $services->set(Components\Ui\BreadcrumbComponent::class);
    $services->set(Components\Ui\ShapeComponent::class);
    $services->set(Components\Ui\StepsComponent::class);
    $services->set(Components\Ui\DropzoneComponent::class);
    $services->set(Components\Ui\RibbonComponent::class);
    $services->set(Components\Ui\ToastComponent::class);
    $services->set(Components\Ui\StatusDotComponent::class);
    $services->set(Components\Ui\CardDropdownComponent::class);
    $services->set(Components\Ui\DropdownComponent::class);
    $services->set(Components\Ui\SpinnerComponent::class);
    $services->set(Components\Ui\BadgeComponent::class);
    $services->set(Components\Ui\IconComponent::class);
    $services->set(Components\Ui\ProgressStepsComponent::class);
    $services->set(Components\Ui\AvatarComponent::class);
    $services->set(Components\Ui\ProgressbgComponent::class);
    $services->set(Components\Ui\TrackingComponent::class);
    $services->set(Components\Ui\SignatureComponent::class);
    $services->set(Components\Ui\ResponsiveImageComponent::class);
    $services->set(Components\Ui\DropdownMenuAllComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Ui\PhotoComponent::class);
    $services->set(Components\Ui\SvgComponent::class);
    $services->set(Components\Ui\NavComponent::class);
    $services->set(Components\Ui\AvatarListComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Ui\IllustrationComponent::class);
    $services->set(Components\Ui\AvatarUploadComponent::class);
    $services->set(Components\Ui\TimelineComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Ui\TypedComponent::class);
    $services->set(Components\Ui\CarouselComponent::class);
    $services->set(Components\Ui\ProgressDescriptionComponent::class);
    $services->set(Components\Ui\InputSelectgroupComponent::class);
    $services->set(Components\Ui\CheckComponent::class);
    $services->set(Components\Ui\InputFileComponent::class);
    $services->set(Components\Ui\InputMaskComponent::class);
    $services->set(Components\Ui\InputIconComponent::class);
    $services->set(Components\Ui\InputGroupComponent::class);
    $services->set(Components\Ui\TextareaAutosizeComponent::class);
    $services->set(Components\Ui\StatusComponent::class);
    $services->set(Components\Ui\RatingComponent::class);
    $services->set(Components\Ui\PaginationComponent::class);
    $services->set(Components\Ui\InlinePlayerComponent::class);
    $services->set(Components\Ui\HrComponent::class);
    $services->set(Components\Ui\ChatComponent::class);
    $services->set(Components\Ui\EmptyComponent::class);
    $services->set(Components\Ui\TableComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Ui\SwitchIconComponent::class);
    $services->set(Components\Ui\ButtonComponent::class);
    $services->set(Components\Ui\BrowserComponent::class);
    $services->set(Components\Ui\AlertComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());

    // === Data-aware card components ===
    $services->set(Components\Cards\UserInfoComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CardRibbonTextComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\UserCardComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\UsersListComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\StoreProductGridComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ProjectProgressComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\SubscribeComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ProfileComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ProfileContactComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\WelcomeComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\IconsComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\EmptyTeamComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CompanyLookupComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\TracksListComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\TrackInfoComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\OrderStatisticsComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\StoreListComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\TasksComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ConfigurationComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ActivityComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ProfileEditComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\SmallStatsComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\PricingCardEnterpriseComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CardRibbonTopComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CardGroupComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\RibbonComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\UserCardBigComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\SmallStats3Component::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\SmallStats2Component::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\IconsBannerComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ProfileTimelineComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ProjectSummaryComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\SignUpComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\NavbarNotificationsComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\SocialTrafficComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ProfileEditBigComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\TableUsersComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\SponsorComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\SignInComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\DeleteConfirmComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CardBackgroundIconComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ForgotPasswordComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\NavbarAppsComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\StoreProductComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CompanyEmployeesComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\UsersList2Component::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\BlogSingleComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CardComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\StatCardComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\SuccessMessageComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CardImageComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\InvoiceComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\UsersListHeadersComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\InvoicesComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ProgressbgComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\WeatherComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\GalleryPhotoComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\PricingPlanComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\MostVisitedPagesComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\Profile2Component::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CreditCardComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\HappyBirthdayComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CarouselComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\PaymentComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\LayoutComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\PricingCardComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\UserCardBgComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ProjectKanbanComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\StatGradientComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\YouWinComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CommentsComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CodeComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\DevelopmentActivityComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\StorageUsageComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\TabsComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ActiveUsers2Component::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\ActiveUsersComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\SocialReferralsComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\RevenueComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\NewClientsComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\TotalUsersComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\HeatmapComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\TotalSalesComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\SalesComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\Card3Component::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\Card5Component::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\Card1Component::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\Card6Component::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\Card4Component::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\Card2Component::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\CardTabsComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\BodyPlaceholderComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Cards\AuthLockComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());

    // === Layout structure components ===
    $services->set(Components\Layout\SidebarComponent::class);
    $services->set(Components\Layout\PageHeader2Component::class);
    $services->set(Components\Layout\PageHeader1Component::class);
    $services->set(Components\Layout\ProfileComponent::class);
    $services->set(Components\Layout\PageHeader4Component::class);
    $services->set(Components\Layout\UptimeComponent::class);
    $services->set(Components\Layout\PageHeader3Component::class);
    $services->set(Components\Layout\PageHeader5Component::class);
    $services->set(Components\Layout\NavbarSideThemeComponent::class);
    $services->set(Components\Layout\NavbarSideUserComponent::class);
    $services->set(Components\Layout\BannerComponent::class);
    $services->set(Components\Layout\NavbarLogoComponent::class);
    $services->set(Components\Layout\JsComponent::class);
    $services->set(Components\Layout\JsLibsComponent::class);
    $services->set(Components\Layout\NavbarSideComponent::class);
    $services->set(Components\Layout\NavbarComponent::class);
    $services->set(Components\Layout\CalendarComponent::class);
    $services->set(Components\Layout\BreadcrumbComponent::class);
    $services->set(Components\Layout\PrintComponent::class);
    $services->set(Components\Layout\UsersComponent::class);
    $services->set(Components\Layout\PhotosComponent::class);
    $services->set(Components\Layout\AddJobComponent::class);
    $services->set(Components\Layout\ButtonsComponent::class);
    $services->set(Components\Layout\AddBoardComponent::class);
    $services->set(Components\Layout\NewProjectComponent::class);
    $services->set(Components\Layout\NavbarSideLanguageComponent::class);
    $services->set(Components\Layout\CssComponent::class);
    $services->set(Components\Layout\NavbarSideAppsComponent::class);
    $services->set(Components\Layout\NavbarSearchComponent::class);
    $services->set(Components\Layout\SentryComponent::class);
    $services->set(Components\Layout\FooterComponent::class);
    $services->set(Components\Layout\HomepageComponent::class);
    $services->set(Components\Layout\NavbarTogglerComponent::class);
    $services->set(Components\Layout\NavbarSideNotificationsComponent::class);
    $services->set(Components\Layout\PageHeaderComponent::class);
    $services->set(Components\Layout\AnalyticsComponent::class);
    $services->set(Components\Layout\SkipLinkComponent::class);
    $services->set(Components\Layout\OgComponent::class);
    $services->set(Components\Layout\LayoutsListComponent::class);
    $services->set(Components\Layout\LayoutsComponent::class);
    $services->set(Components\Layout\NavbarMenuComponent::class);

    // === Reusable partial components ===
    $services->set(Components\Parts\DaysComponent::class);
    $services->set(Components\Parts\MonthsComponent::class);
    $services->set(Components\Parts\DatagridComponent::class);
    $services->set(Components\Parts\CalendarComponent::class);
    $services->set(Components\Parts\TasksComponent::class);
    $services->set(Components\Parts\ActivityComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Parts\NavAsideComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Parts\NewTaskComponent::class);
    $services->set(Components\Parts\SmallComponent::class);
    $services->set(Components\Parts\NewEmailComponent::class);
    $services->set(Components\Parts\SimpleComponent::class);
    $services->set(Components\Parts\DeactivateComponent::class);
    $services->set(Components\Parts\SuccessComponent::class);
    $services->set(Components\Parts\DangerComponent::class);
    $services->set(Components\Parts\FullWidthComponent::class);
    $services->set(Components\Parts\EditProfileComponent::class);
    $services->set(Components\Parts\LargeComponent::class);
    $services->set(Components\Parts\ReportComponent::class);
    $services->set(Components\Parts\ScrollableComponent::class);
    $services->set(Components\Parts\SignatureComponent::class);
    $services->set(Components\Parts\AddTaskComponent::class);
    $services->set(Components\Parts\ChangePasswordComponent::class);
    $services->set(Components\Parts\TeamComponent::class);
    $services->set(Components\Parts\ConfirmDeleteComponent::class);
    $services->set(Components\Parts\NewEventComponent::class);
    $services->set(Components\Parts\SelectComponent::class);
    $services->set(Components\Parts\InputImageRadioComponent::class);
    $services->set(Components\Parts\InputColorComponent::class);
    $services->set(Components\Parts\InputToggleComponent::class);
    $services->set(Components\Parts\CheckboxesListComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Parts\InputCheckboxesInlineComponent::class);
    $services->set(Components\Parts\InputSizesComponent::class);
    $services->set(Components\Parts\InputImagePeopleComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Parts\SelectgroupProjectManagerComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Parts\InputRangeComponent::class);
    $services->set(Components\Parts\InputImageComponent::class);
    $services->set(Components\Parts\InputIconSeparatedComponent::class);
    $services->set(Components\Parts\InputDatalistComponent::class);
    $services->set(Components\Parts\InputFileComponent::class);
    $services->set(Components\Parts\InputComponent::class);
    $services->set(Components\Parts\InputToggleSingleComponent::class);
    $services->set(Components\Parts\InputRadiosInlineComponent::class);
    $services->set(Components\Parts\ValidationStatesComponent::class);
    $services->set(Components\Parts\InputIconComponent::class);
    $services->set(Components\Parts\InputRadiosComponent::class);
    $services->set(Components\Parts\InputColorpickerComponent::class);
    $services->set(Components\Parts\InputSelectgroupsComponent::class);
    $services->set(Components\Parts\FieldsetComponent::class);
    $services->set(Components\Parts\InputCheckboxesComponent::class);
    $services->set(Components\Parts\SelectgroupPaymentsComponent::class)
        ->arg('$fixtureService', service(FixtureService::class)->nullOnInvalid())
        ->arg('$httpClient', service(HttpClientInterface::class)->nullOnInvalid());
    $services->set(Components\Parts\DemoLayoutComponent::class);
    $services->set(Components\Parts\ActivityComponent::class);
};
