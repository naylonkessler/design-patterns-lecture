<?php

interface Handler
{
    public function setNext(Handler $handler): void;
    public function handle(Request $request): ?string;
}

abstract class BaseHandler implements Handler
{
    protected Handler $next;

    public function setNext(Handler $next): void
    {
        $this->next = $next;
    }

    abstract public function handle(Request $request): ?string;
}

class AuthenticationHandler extends BaseHandler
{
    public function handle(Request $request): ?string
    {
        $isValid = $request->key === 'acc1' && $request->token === '5om3t0k3n';

        if (!$isValid) {
            return json_encode(['status' => 401]);
        }

        if ($this->next) {
            return $this->next->handle($request);
        }

        return null;
    }
}

class AuthorizationHandler extends BaseHandler
{
    public function handle(Request $request): ?string
    {
        $isAuthorized = $request->role === 'admin';

        if (!$isAuthorized) {
            return json_encode(['status' => 403]);
        }

        if ($this->next) {
            return $this->next->handle($request);
        }

        return null;
    }
}

class UpperCaseHandler extends BaseHandler
{
    public function handle(Request $request): ?string
    {
        if (isset($request->payload)) {
            return json_encode(['status' => 200, 'body' => strtoupper($request->payload)]);
        }

        if ($this->next) {
            return $this->next->handle($request);
        }

        return null;
    }
}

class Request
{
    public function __construct(
        public readonly string $key,
        public readonly string $token,
        public readonly string $role,
        public readonly string $payload
    ) {}
}

$request = new Request('acc1', '5om3t0k3n', 'admin', 'Content to be uppercased.');

$authentication = new AuthenticationHandler();
$authorization = new AuthorizationHandler();
$action = new UpperCaseHandler();

$authentication->setNext($authorization);
$authorization->setNext($action);

echo PHP_EOL;
echo $authentication->handle($request);
echo PHP_EOL . PHP_EOL;
