<?php

namespace Opspot\Core\Helpdesk\Question;

use Opspot\Core\Helpdesk\Category\Category;
use Opspot\Core\Session;
use Opspot\Traits\MagicAttributes;
use phpcassa\UUID;

/**
 * Class Question
 * @package Opspot\Core\Helpdesk\Entities
 * @method string getUuid()
 * @method Question setUuid(string $value)
 * @method string getQuestion()
 * @method Question setQuestion(string $value)
 * @method string getAnswer()
 * @method Question setAnswer(string $value)
 * @method string getCategoryUuid()
 * @method Question setCategoryUuid(string $value)
 * @method Category getCategory()
 * @method Question setCategory()
 * @method array getThumbsUp()
 * @method Question setThumbsUp(array $value)
 * @method array getThumbsDown()
 * @method Question setThumbsDown(array $value)
 * @method int getScore()
 * @method Question setScore(int $value)
 * @method int getPosition()
 * @method Question setPosition(int $value)
 */
class Question
{
    use MagicAttributes;

    /** @var UUID */
    protected $uuid;
    /** @var string */
    protected $question;
    /** @var string */
    protected $answer;
    /** @var UUID */
    protected $category_uuid;
    /** @var Category */
    protected $category;
    /** @var array */
    protected $thumbsUp = [];
    /** @var array */
    protected $thumbsDown = [];
    /** @var int */
    protected $score;

    /** @var int $position */
    protected $position = 10;

    public function export()
    {
        $export = [];

        $export['uuid'] = $this->getUuid();
        $export['question'] = $this->getQuestion();
        $export['answer'] = $this->getAnswer();
        $export['category_uuid'] = $this->getCategoryUuid();
        $export['category'] = $this->getCategory() ? $this->getCategory()->export() : null;
        $export['thumb_up'] = in_array(Session::getLoggedInUserGuid(), $this->getThumbsUp());
        $export['thumb_down'] = in_array(Session::getLoggedInUserGuid(), $this->getThumbsDown());
        $export['position'] = $this->getPosition();

        return $export;
    }
}
