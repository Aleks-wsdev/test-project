<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};
use Symfony\Component\Console\Output\OutputInterface;

class UpdateUserCommand extends Base
{
    public static $defaultName = 'api:update-user';

    protected static $defaultDescription = './console.php api:update-user [name] [email] ';

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Input user ID:')
            ->addArgument('name', InputArgument::REQUIRED, 'Input user name:')
            ->addArgument('email', InputArgument::REQUIRED, 'Input user email:')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->apiClient->put('/api/' . $this->apiVersion . '/user/' . 'update/' . $input->getArgument('id'), [
                'headers' => $this->getHeaders(),
                'json' => [
                    'name' => $input->getArgument('name'),
                    'email' => $input->getArgument('email'),
                ],
            ]);
        } catch (RequestException $e) {
            $output->writeln($this->getRequestExceptionErr($e));
            return Command::INVALID;
        }

        $data = $this->getResponseContent($response);

        $output->writeln([

            '',
            '=========================================',
            '<comment>Operation has been successful!</comment>',
            '=========================================',
            '',
            '<fg=green;>' . $this->print($data) . '</>',

            '<fg=blue;>|||||||</><fg=yellow;>|||||||</>',
            '=========================================',
            '',
        ]);

        return Command::SUCCESS;
    }
}
