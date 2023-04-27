<?php

namespace Console;

use GuzzleHttp\Exception\RequestException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};
use Symfony\Component\Console\Output\OutputInterface;

class ListUsersByGroupCommand extends Base
{
    public static $defaultName = 'api:list-users-group';

    protected static $defaultDescription = './console.php api:list-users-group [mame]';

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Input group Name:');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $response = $this->apiClient->get('/api/' . $this->apiVersion . '/users/' . 'list/?name=' . $input->getArgument('name') ?: '', [
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
