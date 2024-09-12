<?php

/*
 * This file is part of the Pho package.
 *
 * (c) Emre Sokullu <emre@phonetworks.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Pho\Lib\Graph;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected $graph;

    public function setUp(): void {
        $this->graph = new Graph();
    }

    public function tearDown(): void {
        unset($this->graph);
    }
}
