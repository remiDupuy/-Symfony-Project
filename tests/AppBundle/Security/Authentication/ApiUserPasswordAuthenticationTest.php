<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 17/04/18
 * Time: 09:50
 */

namespace Tests\AppBundle\Security\Authentication;

use AppBundle\Entity\User;
use AppBundle\Security\Authentication\ApiUserPasswordAuthentication;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\Argon2iPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class ApiUserPasswordAuthenticationTest extends TestCase
{
    public function testGetCredentialsWithoutUsernameInRequest() {
        $authenticator = new ApiUserPasswordAuthentication(new EncoderFactory([]));

        $request = new Request();
        $request->headers->add([ 'X-PASSWORD' => 'AZAZ']);
        $result = $authenticator->getCredentials($request);

        $this->assertNull( $result);
    }

    public function testGetCredentialsWithoutPasswordInRequest() {
        $authenticator = new ApiUserPasswordAuthentication(new EncoderFactory([]));

        $request = new Request();
        $request->headers->add([ 'X-USERNAME' => 'AZAZ']);
        $result = $authenticator->getCredentials($request);

        $this->assertNull($result);
    }

    public function testGetCredentialsWithHeaders() {
        $authenticator = new ApiUserPasswordAuthentication(new EncoderFactory([]));

        $request = new Request();
        $credentials = [ 'X-USERNAME' => 'AZAZ', 'X-PASSWORD' => 'passwordf'];
        $request->headers->add($credentials);
        $result = $authenticator->getCredentials($request);

        $this->assertSame(['username' => 'AZAZ', 'password' => 'passwordf'], $result);
    }

    /*
     * @dataProvider getHeaders
     */
    public function testGetCredentialsWithoutAnyHeaders() {
        $encoder = new EncoderFactory([]);
        $authenticator = new ApiUserPasswordAuthentication($encoder);

        $request = new Request();
        $request->headers->add($this->getHeaders());
        $result = $authenticator->getCredentials($request);

        $this->assertNull($result);
    }

    public function getHeaders() {
        return [
            ['X-USERNAME' => 'AZAZ'],
            ['X-PASSWORD' => 'passwordf']
        ];
    }

    public function testCheckCredentialsIsCorrect() {
        $encoder = new Argon2iPasswordEncoder();

        $encoderFactory = $this
            ->getMockBuilder(EncoderFactoryInterface::class)
            ->disableOriginalConstructor()->getMock();

        $encoderFactory->method('getEncoder')->willReturn($encoder);
        $authenticator = new ApiUserPasswordAuthentication($encoderFactory);

        $user = new User();
        $user->setEmail('remi.dup73000@gmail.com');
        $user->setPassword('$argon2i$v=19$m=1024,t=2,p=2$VTBlS2RqM2NBZFF1cTZwYQ$H4DJHNxVHitMji5BuItLBZpLRiNo+OOB1sAXFMAVTVY');
        $credentials = ['username' => 'remi.dup73000@gmail.com', 'password' => 'azeaze'];
        $result = $authenticator->checkCredentials($credentials, $user);

        $this->assertTrue($result);
    }



    public function testCheckCredentialsIsWrong() {
        $encoder = new Argon2iPasswordEncoder();

        $encoderFactory = $this
            ->getMockBuilder(EncoderFactoryInterface::class)
            ->disableOriginalConstructor()->getMock();

        $encoderFactory->method('getEncoder')->willReturn($encoder);
        $authenticator = new ApiUserPasswordAuthentication($encoderFactory);

        $user = new User();
        $user->setEmail('remi.dup73000@gmail.com');
        $user->setPassword('$argon2i$v=19$m=1024,t=2,p=2$VTBlS2RqM2NBZFF1cTZwYQ$H4DJHNxVHitMji5BuItLBZpLRiNo+OOB1sAXFMAVTVY');
        $credentials = ['username' => 'remi.dup73000@gmail.com', 'password' => 'mysdfsql'];
        $result = $authenticator->checkCredentials($credentials, $user);

        $this->assertFalse($result);
    }
}