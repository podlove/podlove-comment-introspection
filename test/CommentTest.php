<?php
use Podlove\Comment\Comment;

class CommentTest extends PHPUnit_Framework_TestCase {

	public function testSingleLineComment() {
		$commentString = <<<EOD
/**
 * Single line comment
 */
EOD;
	
		$c = new Comment($commentString);
		$c->parse();

		$this->assertEquals( "Single line comment", $c->getTitle() );
	}

	public function testCommentWithDescription() {
		$commentString = <<<EOD
/**
 * Title
 *
 * Description line 1
 * Description line 2
 */
EOD;
	
		$c = new Comment($commentString);
		$c->parse();

		$this->assertEquals( "Title", $c->getTitle() );
		$this->assertEquals( "Description line 1\nDescription line 2", $c->getDescription() );
	}

	public function testCommentWithTags() {
		$commentString = <<<EOD
/**
 * Tags
 * 
 * @tag1
 * @tag2 tag2 description
 */
EOD;
	
		$c = new Comment($commentString);
		$c->parse();

		$this->assertEquals( "Tags", $c->getTitle() );
		$this->assertEquals( [
			'name' => 'tag1',
			'description' => '',
			'line' => 2,
		], $c->getTags()[1] );
		$this->assertEquals( [
			'name' => 'tag2',
			'description' => 'tag2 description',
			'line' => 3,
		], $c->getTags()[0] );
	}

	public function testTagsFilter() {
		$commentString = <<<EOD
/**
 * Tags
 * 
 * @tag1
 * @tag2 tag2 description
 */
EOD;
	
		$c = new Comment($commentString);
		$c->parse();

		$this->assertEquals( "Tags", $c->getTitle() );
		$this->assertEquals( "tag1", $c->getTags('tag1')[0]['name'] );
		$this->assertEquals( "tag1", $c->getTag('tag1')['name'] );
	}

}