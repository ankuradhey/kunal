<?php

namespace Doctrine\ORM\DoctrineExtentions;

use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\AST\Functions\FunctionNode;

/**
 * TimeToSecFunction ::= "TIME_TO_SEC" "(" ArithmeticPrimary "," ArithmeticPrimary ")"
 */
class TimeToSec extends FunctionNode
{
    /** @var \Doctrine\ORM\Query\AST\SimpleArithmeticExpression  */
    public $timeExpression = null;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->timeExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'TIME_TO_SEC(' .
        $this->timeExpression->dispatch($sqlWalker) .
        ')';
    }
}