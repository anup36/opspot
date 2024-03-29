<?php

namespace Opspot\Core\Hashtags\User;

use Opspot\Core\Data\cache\abstractCacher;
use Opspot\Core\Di\Di;
use Opspot\Core\Hashtags\HashtagEntity;

class LegacyRepository
{
    /** @var \PDO */
    protected $db;

    /** @var abstractCacher */
    protected $cacher;

    public function __construct($db = null, $cacher = null)
    {
        $this->db = $db ?: Di::_()->get('Database\PDO');
        $this->cacher = $cacher ?: Di::_()->get('Cache');
    }

    /**
     * Return all hashtags
     */
    public function getAll($opts = [])
    {
        $opts = array_merge([
            'user_guid' => null
        ], $opts);

        if (!$opts['user_guid']) {
            throw new \Exception('user_guid must be provided');
        }

        $query = "SELECT hashtag FROM user_hashtags WHERE guid = ?";
        $params = [$opts['user_guid']];

        $statement = $this->db->prepare($query);

        $statement->execute($params);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Return all hashtags in db
     * For migration
     */
    public function _getEverything($offset, $limit)
    {
        $query = "SELECT * FROM user_hashtags LIMIT ? OFFSET ?";
        $params = [intval($limit), intval($offset)];

        $statement = $this->db->prepare($query);
        $statement->execute($params);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * @param HashtagEntity[] $hashtags
     * @return bool
     */
    public function add($hashtags)
    {
        $query = "UPSERT INTO user_hashtags(guid, hashtag) VALUES (?, ?)";

        foreach ($hashtags as $hashtag) {
            try {
                $statement = $this->db->prepare($query);

                $this->cacher->destroy("user-selected-hashtags:{$hashtag->getGuid()}");

                return $statement->execute([$hashtag->getGuid(), $hashtag->getHashtag()]);
            } catch (\Exception $e) {
                error_log($e->getMessage());
            }
        }
        return false;
    }

    /**
     * @param $user_guid
     * @param array $hashtags
     * @return bool
     */
    public function remove($user_guid, array $hashtags)
    {
        $variables = implode(',', array_fill(0, count($hashtags), '?'));

        $query = "DELETE FROM user_hashtags WHERE guid = ? AND hashtag IN ({$variables})";

        $statement = $this->db->prepare($query);

        $this->cacher->destroy("user-selected-hashtags:{$user_guid}");

        return $statement->execute(array_merge([$user_guid], $hashtags));
    }

    public function update()
    {

    }

}
