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

use PHPUnit\Framework\TestCase;
use Zephir\ClassDefinition;
use Zephir\Config;
use Zephir\Stubs\Generator;

class StubsGeneratorTest extends TestCase
{
    public function testItCanBuildClass()
    {
        $files = [
            './unit-tests/fixtures/class-definition-1.php',
        ];

        $classDefinition = new ClassDefinition('Test', 'TestStubGenerator');
        $config = new Config();
        $test = new Generator($files, $config);

        $expectedStub = <<<DOC
<?php

namespace Test;

class TestStubGenerator
{

}

DOC;

        $this->assertSame($expectedStub, $test->buildClass($classDefinition, "\t"));
    }
}
