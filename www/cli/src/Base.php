<?php

namespace Console;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};

abstract class Base extends Command
{
    protected Client $apiClient;

    protected string $apiVersion = 'v1';

    public function __construct(?string $name = null)
    {
        parent::__construct($name);

        $this->apiClient = new Client([
            'base_uri' => $this->getBaseUrl(),
            'timeout' => 1000.0,
        ]);
    }

    protected function getBaseUrl(): string
    {
        return $_ENV['API_URL'] ?? '';
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    final public function getResponseContent(ResponseInterface $response): ?array
    {
        return json_decode($response->getBody()?->getContents(), true);
    }

    final public function getRequestExceptionErr(RequestException $exception): string
    {
        if ($exception->getResponse()?->getBody()) {
            return $exception->getResponse()->getBody()->getContents();
        }

        return $exception->getMessage();
    }

    final public function print($array, int $i = 0): string
    {
        $printStr = '';

        foreach ($array as $key => $value) {
            $printStr .= str_repeat("\t", $i);
            $key = '・' . $key . (is_numeric($key) ? '' : "\t") . " ➤ \t";

            if (is_array($value)) {
                $i++;
                $printStr .= $key . "\n" . $this->print($value, $i);
                $i--;
            } else {
                $printStr .= $key . $value . "\n";
            }
        }

        return $printStr;
    }
}
