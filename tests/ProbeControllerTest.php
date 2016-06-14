<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProbeControllerTest extends TestCase
{

    use DatabaseTransactions;
    use DatabaseMigrations;
    // use WithoutMiddleware;

    /**
     * @test
     */
    public function posting_a_valid_probe_with_valid_header_stores_in_db()
    {
        $this->assertEquals('0', DB::select('select count(*) as count from probes')[0]->count);

        // Make a user
        $user = factory(App\User::class)->create();
        
        // Put user's api key in the header
        $headers = ['key' => $user->apiKey];

        $data = $this->getValidProbePostJson();
        $route = '/probe';
        $response = $this->json('POST', $route, [
                'timestamp'        => 123,
                'macAddress'       => 'foobar',
                'signalStrength'   => '-50',
                'ssid'             => 'baz',
                'manufacturerName' => 'Sony',
            ], $headers)
            ->seeJson([
                'created' => true,
            ]);

        $this->assertEquals('1', DB::select('select count(*) as count from probes')[0]->count);
    }

    /**
     * @test
     */
    public function posting_a_valid_probe_with_invalid_header_does_not_store_in_db()
    {
        $this->assertEquals('0', DB::select('select count(*) as count from probes')[0]->count);

        // Make a user
        $user = factory(App\User::class)->create();
        
        // Put user's api key in the header
        $headers = ['key' => 'invalidheaderkey'];

        $data = $this->getValidProbePostJson();
        $route = '/probe';
        $response = $this->json('POST', $route, [
                'timestamp'        => 123,
                'macAddress'       => 'foobar',
                'signalStrength'   => '-50',
                'ssid'             => 'baz',
                'manufacturerName' => 'Sony',
            ], $headers);

        $this->assertEquals('0', DB::select('select count(*) as count from probes')[0]->count);
    }
    /**
     * @test
     */
    public function posting_a_valid_probe_without_header_does_not_store_in_db()
    {
        $this->assertEquals('0', DB::select('select count(*) as count from probes')[0]->count);

        $headers = [];
        $data = $this->getValidProbePostJson();
        $route = '/probe';
        $response = $this->json('POST', $route, [
                'timestamp'        => 123,
                'macAddress'       => 'foobar',
                'signalStrength'   => '-50',
                'ssid'             => 'baz',
                'manufacturerName' => 'Sony',
            ]);

        $this->assertEquals('0', DB::select('select count(*) as count from probes')[0]->count);
    }

    /**
     * @test
     */
    public function posting_an_invalid_probe_with_valid_header_does_not_store_it_in_db()
    {
        $this->assertEquals('0', DB::select('select count(*) as count from probes')[0]->count);

        // Make a user
        $user = factory(App\User::class)->create();
        
        // Put user's api key in the header
        $headers = ['key' => $user->apiKey];

        $data = $this->getValidProbePostJson();
        $route = '/probe';
        $response = $this->json('POST', $route, [
                'timestamp'        => 123,
                // Missing mac address which is required
                'signalStrength'   => '-50',
                'ssid'             => 'baz',
                'manufacturerName' => 'Sony',
            ], $headers)
            ->seeJson([
                'macAddress' => ['The mac address field is required.'],
            ]);

        $this->assertEquals('0', DB::select('select count(*) as count from probes')[0]->count);    
    }
    
    protected function getValidProbePostJson()
    {
        return '{"timestamp":123,"macAddress":"fooBar","signalStrength":"-50","ssid":"baz","manufacturerName":"sony"}';
    }
}
