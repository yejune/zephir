<?php declare(strict_types=1);

/*
 * This file is part of the Zephir.
 *
 * (c) Zephir Team <team@zephir-lang.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Extension\ZE3\Oo;

use PHPUnit\Framework\TestCase;
use Test\Oo\OoParams;

class OoParamsStrictTest extends TestCase
{
    public function testSetStrictAgeSuccess()
    {
        $t = new OoParams();

        $this->assertSame($t->setStrictAge(17), 17);
    }

    public function testSetStrictAgeException1()
    {
        $t = new OoParams();

        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            $this->expectException('\TypeError');
        }

        $t->setStrictAge(17.0);
    }

    public function testSetStrictAgeException2()
    {
        $t = new OoParams();

        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            $this->expectException('\TypeError');
        }

        $t->setStrictAge('17');
    }

    public function testSetStrictAverageSuccess()
    {
        $t = new OoParams();

        $this->assertSame($t->setStrictAverage(17.1), 17.1);
    }

    public function testSetStrictAverageException2()
    {
        $t = new OoParams();

        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            $this->expectException('\TypeError');
        }

        $t->setStrictAverage('17');
    }

    public function testSetStrictNameSuccess()
    {
        $t = new OoParams();
        $this->assertSame($t->setStrictName('peter'), 'peter');
    }

    public function testSetStrictNameException()
    {
        $t = new OoParams();

        if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
            $this->expectException('\TypeError');
        }

        $t->setStrictName(1234);
    }
}
