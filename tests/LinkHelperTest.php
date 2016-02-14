<?php
use App\Helpers\LinkHelper;
use App\Factories\LinkFactory;

class LinkHelperTest extends TestCase
{
    /**
     * Test LinkHelper
     *
     * @return void
     */

    public function testLinkHelperAlreadyShortened() {
        $not_short = [
            'https://google.com',
            'https://example.com/google',
            'https://cydrobolt.com',
            'http://github.com/cydrobolt/polr'
        ];

        $shortened = [
            'https://polr.me/1',
            'http://bit.ly/1PUf6Sw',
            'http://'.env('APP_ADDRESS').'/1',
            'https://goo.gl/2pSp9f'
        ];


        foreach ($not_short as $u) {
            $this->assertEquals(false, LinkHelper::checkIfAlreadyShortened($u));
        }

        foreach ($shortened as $u) {
            $this->assertEquals(true, LinkHelper::checkIfAlreadyShortened($u));
        }
    }

    public function testLinkExists() {
        $link = LinkFactory::createLink('http://example.com/ci', $is_secret=false, $custom_ending=null, $link_ip='127.0.0.1', $creator=false, $return_object=true);
        // assert that existent link ending returns true
        $this->assertEquals(LinkHelper::linkExists($link->short_url), true);
        // assert that nonexistent link ending returns false
        $this->assertEquals(LinkHelper::linkExists('nonexistent'), false);

    }
}