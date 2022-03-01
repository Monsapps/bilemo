<?php

namespace App\Test\Model;

use App\Model\AbstractModel;
use PHPUnit\Framework\TestCase;

class AbstractModelTest extends TestCase
{
    public function testSetMeta(): void
    {
        $stub = $this
        ->getMockBuilder(AbstractModel::class)
        ->getMockForAbstractClass();

        $stub->setMeta('name', 'value');

        $meta['name'] = 'value';

        $this->assertSame($meta, $stub->meta);
    }

    public function testAddSameMetaException() : void
    {
        $this->expectException(\LogicException::class);

        $stub = $this
            ->getMockBuilder(AbstractModel::class)
            ->getMockForAbstractClass();

        $stub->addMeta('name', 'value');
        $stub->addMeta('name', 'value');
    }
}
