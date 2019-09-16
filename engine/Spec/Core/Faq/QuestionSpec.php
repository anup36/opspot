<?php

namespace Spec\Opspot\Core\Faq;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Core\Faq\Answer;

class QuestionSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Faq\Question');
    }

    function it_should_set_question()
    {
        $this->setQuestion('question')->shouldReturn($this);
        $this->getQuestion()->shouldBe('question');
    }

    function it_should_set_answer(Answer $answer)
    {
        $this->setAnswer($answer)->shouldReturn($this);
        $this->getAnswer()->shouldBe($answer);
    }

}
