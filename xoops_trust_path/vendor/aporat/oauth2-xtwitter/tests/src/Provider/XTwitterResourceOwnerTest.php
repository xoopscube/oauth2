<?php

namespace Aporat\OAuth2\Client\Test\Provider;

use Aporat\OAuth2\Client\Provider\PinterestResourceOwner;
use Aporat\OAuth2\Client\Provider\XTwitter;
use Aporat\OAuth2\Client\Provider\XTwitterResourceOwner;
use PHPUnit\Framework\TestCase;

class XTwitterResourceOwnerTest extends TestCase
{
    public function testUrlIsNicknameWithoutDomain(): void
    {
        $user_id = uniqid();
        $username = uniqid();
        $user = new XTwitterResourceOwner(['data' => ['id' => $user_id, 'username' => $username]]);

        $this->assertEquals($user_id, $user->getId());
    }
}
