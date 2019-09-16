<?php


namespace Opspot\Controllers\Cli\Migrations;

use Opspot\Cli;
use Opspot\Core\Hashtags\HashtagEntity;
use Opspot\Core\Hashtags\User\LegacyRepository;
use Opspot\Core\Hashtags\User\Repository;
use Opspot\Interfaces;


class UserHashtags extends Cli\Controller implements Interfaces\CliControllerInterface
{
    public function help($command = null)
    {
        $this->out('Syntax usage: cli migrations user_hashtags');
    }

    public function exec()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $legacy = new LegacyRepository();
        $repo = new Repository();

        $offset = 0;
        $limit = 1000;

        while (true) {
            $rows = $legacy->_getEverything($offset, $limit);

            if (!$rows) {
                break;
            }

            foreach ($rows as $row) {
                if (!$row['hashtag']) {
                    continue;
                }

                echo "{$row['guid']} -> #{$row['hashtag']}... ";

                $hashtag = (new HashtagEntity())->setGuid($row['guid'])->setHashtag($row['hashtag']);

                $ok = $repo->add([ $hashtag ]);

                if ($ok) {
                    echo "OK!\n";
                } else {
                    echo "Error migrating\n";
                }
            }

            $offset += $limit;
        }
    }
}
