<?php

namespace Spec\Opspot\Core\Faq;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

use Opspot\Core\Faq\Question;

class AnswerSpec extends ObjectBehavior
{

    function it_is_initializable()
    {
        $this->shouldHaveType('Opspot\Core\Faq\Answer');
    }

    function it_should_set_answer()
    {
        $this->setAnswer('test')->shouldReturn($this);
        $this->getAnswer()->shouldBe('test');
    }

    function it_should_set_question(Question $question)
    {
        $this->setQuestion($question)->shouldReturn($this);
        $this->getQuestion()->shouldBe($question);
    }
}
