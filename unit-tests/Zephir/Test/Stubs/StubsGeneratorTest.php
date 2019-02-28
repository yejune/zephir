<?php

/*
 * This file is part of the Zephir.
 *
 * (c) Zephir Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Zephir\Test\Stubs;

use Zephir\ClassDefinition;
use Zephir\Config;
use Zephir\Parser\Parser;
use Zephir\Stubs\Generator;
use Zephir\Test\TestCase;

/**
 * Class StubsGeneratorTest.
 */
class StubsGeneratorTest extends TestCase
{
    public function expectedStub()
    {
        return <<<DOC
<?php

namespace Test;

class BaseClassExample
{

}

DOC;
    }

    /** @test */
    public function itCanBuildClass()
    {
        $file = './unit-tests/fixtures/ide_stubs/BaseClassExample.zep';

        $parser = new Parser();
        $parsed = $parser->parse($file);

        $classDefinition = new ClassDefinition('Test', $parsed[3]['name']);

        /** @var \Zephir\Config $config */
        $config = $this->createMock(Config::class);

        $test = new Generator($parsed, $config);

        // call buildClass($classDefinition, "\t")
        $generatedStub = $this->invokeMethod($test, 'buildClass', [$classDefinition, "\t"]);

        $expectedStub = $this->expectedStub();

        $this->assertSame($expectedStub, $generatedStub);
    }
}
