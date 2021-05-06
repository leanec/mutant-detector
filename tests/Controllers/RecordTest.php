<?php

declare(strict_types= 1 );

namespace Tests\Controllers;

use Tests\BaseTest;

class RecordTest extends BaseTest
{

    /**
     * Test bad Endpoint.
     */
    public function testBadEndPoint(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutants',
            []
        );

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('Endpoint not found', $result);
    }

    /**
     * Test empty data.
     */
    public function testDnaIsEmpty(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            []
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('DNA is empty', $result);
    }

    /**
     * Test String data.
     */
    public function testDnaIsString(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => "ATGCGACAGTGCTTATGTAGAAGGCCCCTATCACTG"]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('DNA must be an array', $result);
    }

    /**
     * Test Matrix MxN.
     */
    public function testDnaIsMxN(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ATGCGA","CAGTGC","TTATGT","AGAAGG"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('DNA must be an array NxN', $result);
    }

    /**
     * Test character not allowed.
     */
    public function testDnaContaintBadChar(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ATGSGA","CAGTGC","TTATGT","AGAAGG","CCCCTA","TCACTG"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('DNA must contain only A,T,C,G', $result);
    }

    /**
     * Test example mutant.
     */
    public function testDnaIsMutant(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ATGCGA","CAGTGC","TTATGT","AGAAGG","CCCCTA","TCACTG"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test example human.
     */
    public function testDnaIsHuman(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ATGCGA","CAGTGC","TTATTT","AGACGG","GCGTCA","TCACTG"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);
    }

    
    /**
     * Test example mutan again (Saved in DB).
     * @depends testDnaIsMutant
     */
    public function testDnaIsMutantInDatabase(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ATGCGA","CAGTGC","TTATGT","AGAAGG","CCCCTA","TCACTG"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test example human again (Saved in DB).
     * @depends testDnaIsHuman
     */
    public function testDnaIsHumanInDatabase(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ATGCGA","CAGTGC","TTATTT","AGACGG","GCGTCA","TCACTG"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test mutant horizontal sequences.
     */
    public function testIsMutantH(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ATCCCC","CAGTGC","TTGTGT","AGAAGG","CCCCTA","TCACTG"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test mutant vertical sequences.
     */
    public function testIsMutantV(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ATCCGC","CAGTGC","TGGTGT","AGAAGG","CGCCTA","TGACTG"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test mutant diagonal sequences.
     */
    public function testIsMutantD(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ATCGGC","CACTGC","CGATGT","GCAATG","CGCCTA","TCTCTG"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test mutant anti-diagonal sequences.
     */
    public function testIsMutantAD(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ATCGGC","CAGGGC","TGGTGT","GGAATG","CGCTTA","TCTCTG"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test mutant large dna.
     */
    public function testLargeDnas(): void
    {
        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["CAGCGCCCTT","TAATAAATTA","CAGCGGATGT","GCTCTTTGTA","GAATAGCGCA","CCCAGTGTTG","CACGTATCGG","TGGACCACAA","TTATGCAGAT","ATTTGAGATA"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);

        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["TCCACAGGAT","GAGCAGGCAA","TAGAACTCTT","GGTTCCTTGC","TTGGCGTCAT","AGAACTGTAG","ACGGGCAGAC","ATAGAGTTCT","TAGTAAGAGT","GGCCCTCCAA"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);

        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ACAGTCTAGTT","TGTGTCTGATC","TTCTGGCGACC","CACCGATTCCT","TCTGCGGCCAA","TCTTCACGTAC","GACAGCACAAG","GCCACAATAGA","GTCCTGCAACG","CGATCATCGCG","TACTATGGAAC"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);

        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["ATTACCACGG","CTTCCTTTAA","GATCTATTGT","GTGCTCCGAG","AAAGTCGGAC","TAACCGTGTG","GTGGTTAAGC","CATTGGCCAT","GGTGGTTAAT","TTGGACAGCT"]]
        );

        $result = (string) $response->getBody();

        $response = $this->runApp(
            'POST', 
            '/mutant',
            ["dna" => ["CGCGTTGAG","ACAATCCGA","AGATGCGTT","GGTATTACT","GTGTGGGTC","GTGAGTTAC","AGACCACTT","TGTTTGCTG","AGACTCTTA"]]
        );

        $result = (string) $response->getBody();

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test GET stats.
     */
    public function testStats(): void
    {
        $response = $this->runApp('GET', '/stats');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('count_mutant_dna', $result);
        $this->assertStringContainsString('count_human_dna', $result);
        $this->assertStringContainsString('ratio', $result);
        $this->assertStringNotContainsString('error', $result);
    }
}