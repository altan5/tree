<?php

namespace Altan\TreeBuilder\Controller;
use Altan\TreeBuilder\Tools\Db\MysqlDb;
use PHPUnit\Framework\TestCase;

class TreeControllerTest extends TestCase
{
	private TreeController $treeController;

	protected function setUp(): void
	{
		$this->treeController = new TreeController(new MysqlDb("", "", ""));
	}

	protected function tearDown(): void
	{
	}
}
