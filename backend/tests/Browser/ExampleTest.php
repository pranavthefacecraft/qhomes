<?php
use Playwright\Browser;
use Playwright\Test;

class ExampleTest extends Test
{
    public function testExample()
    {
        $this->browser->launch();
        $page = $this->browser->newPage();
        $page->goto('https://example.com');
        $this->assertEquals('Example Domain', $page->title());
        $this->browser->close();
    }
}