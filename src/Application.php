<?php

declare(strict_types=1);

namespace App;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Middleware\AuthorizationMiddleware;
use Authorization\Policy\OrmResolver;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Core\Exception\MissingPluginException;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use Authentication\Identifier\IdentifierInterface;
use Cake\Routing\Router;

if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

class Application extends BaseApplication implements AuthenticationServiceProviderInterface, AuthorizationServiceProviderInterface
{
    public function bootstrap(): void
    {
        parent::bootstrap();

        $this->addPlugin('Authentication');
        $this->addPlugin('Authorization');
        $this->addPlugin('MetronicV4');
        $this->addPlugin('Pdf');

        if (PHP_SAPI === 'cli') {
            $this->bootstrapCli();
            if (Configure::read('debug')) {
                $this->addPlugin('IdeHelper');
            }
        } else {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false),
            );
        }

        if (Configure::read('debug')) {
            $this->addPlugin('DebugKit');
            Configure::write('DebugKit.ignoreAuthorization', true);
        }
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $csrf = new CsrfProtectionMiddleware([
            'httponly' => true,
            'cookieName' => 'brasils_csrf_token',
        ]);

        $middlewareQueue
            ->add(new ErrorHandlerMiddleware(Configure::read('Error')))
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))
            ->add(new RoutingMiddleware($this))
            ->add(new BodyParserMiddleware())
            ->add($csrf)
            ->add(new RoutingMiddleware($this))
            ->add(new AuthenticationMiddleware($this))
            ->add(new AuthorizationMiddleware($this));

        return $middlewareQueue;
    }

    /**
     * Register application container services.
     *
     * @param \Cake\Core\ContainerInterface $container The Container to update.
     * @return void
     * @link https://book.cakephp.org/4/en/development/dependency-injection.html#dependency-injection
     */
    public function services(ContainerInterface $container): void {}

    /**
     * Bootstrapping for CLI application.
     *
     * That is when running commands.
     *
     * @return void
     */
    protected function bootstrapCli(): void
    {
        try {
            $this->addPlugin('Bake');
        } catch (MissingPluginException $e) {
        }
        $this->addPlugin('Migrations');
    }

    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService([
            'unauthenticatedRedirect' => Router::url(['controller' => 'Irmaos', 'action' => 'login']),
            'queryParam' => 'redirect',
        ]);

        $service->loadIdentifier('Authentication.Password', [
            'fields' => [
                IdentifierInterface::CREDENTIAL_USERNAME => 'email',
                IdentifierInterface::CREDENTIAL_PASSWORD => 'senha',
            ],
            'resolver' => [
                'className' => 'Authentication.Orm',
                'userModel' => 'Irmaos',
            ],
        ]);

        $service->loadAuthenticator('Authentication.Session');
        $service->loadAuthenticator('Authentication.Form', [
            'fields' => ['username' => 'email_login', 'password' => 'senha_login'],
            'loginUrl' => Router::url(['controller' => 'Irmaos', 'action' => 'login']),
        ]);

        return $service;
    }

    public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
    {
        $resolver = new OrmResolver();

        return new AuthorizationService($resolver);
    }
}
