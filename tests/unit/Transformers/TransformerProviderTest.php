<?php
namespace Tests\Unit\Transformers;

use App\Exceptions\TransformerNotFoundException;
use App\Transformers\TransformerInterface;
use App\Transformers\TransformerProvider;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryTestCase;

class TransformerProviderTest extends MockeryTestCase
{

    private $sut;

    private $config;

    /** @var  Mockery\MockInterface */
    private $application;

    public function setUp()
    {
        $this->config = Mockery::mock(Repository::class);
        $this->application = Mockery::mock(Application::class);
        $this->sut = new TransformerProvider($this->config, $this->application);
        parent::setUp();
    }


    public function testDoesNotHaveTransformer()
    {
        $this->assertFalse($this->sut->hasTransformer('transformer'));
    }

    public function testCanRegisterTransformer()
    {
        $this->sut->registerTransformer(new TestTransformer(), 'test');
        $this->assertTrue($this->sut->hasTransformer('test'));
    }

    /**
     * @expectedException TransformerNotFoundException

    public function testExceptionInvalidTransformer()
    {
        $sut = Mockery::mock('App\Transformers\TransformerProvider[hasTransformer]', array($this->config));
        $sut->shouldReceive('hasTransformer')->once()->with('notransformer')->andReturn(false);
        $this->config->shouldReceive('has')->once()->with('notransformer')->andReturn(false);
        $sut->getTransformer('notransformer');

    }*/

}

class TestTransformer implements TransformerInterface
{
    public function transform($twoFactorUser) {}
}
