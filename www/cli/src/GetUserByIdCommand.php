<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};
use Symfony\Component\Console\Output\OutputInterface;

class GetUserByIdCommand extends Base
{
    public static $defaultName = 'api:get-user';

    protected static $defaultDescription = './console.php api:get-user [id]';

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Input user ID:');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->apiClient->get('/api/' . $this->apiVersion . '/user/' . $input->getArgument('id') ?: '', [
                'headers' => $this->getHeaders(),
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
