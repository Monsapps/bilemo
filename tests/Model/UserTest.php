<?php

namespace App\Test\Model;

use App\Model\User;
use PHPUnit\Framework\TestCase;

class Usertest extends TestCase
{
    public function testAddSameMetaException(): void
    {
        $stub = $this->getMockBuilder(User::class)
            ->onlyMethods(['addMeta'])
            ->disableOriginalConstructor()
            ->getMock();

        /*$stub->expects($this->exactly(2))
            ->method('addMeta')
            ->withConsecutive(
                ['name', 'value'],
                ['name', 'value']
            )
            ->willReturnOnConsecutiveCalls($this->returnSelf(), $this->returnSelf());*/

        $stub->addMeta('name', 'value');
        
        $stub->addMeta('name', 'value');

        dd($stub->meta);

    }

}
