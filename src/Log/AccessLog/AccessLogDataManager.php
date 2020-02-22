<?php

namespace src\Log\AccessLog;

use src\Log\LogDataManagerInterface;

class AccessLogDataManager implements LogDataManagerInterface
{
    private $urlKey = 6;
    private $httpStatusKey = 8;
    private $trafficKey = 9;
    private $view = 0;
    private $traffic = 0;
    private $urlsCount = 0;
    private $statusCodes = [];
    private $urls = [];
    private $crawlers = [];

    /**
     * @return array
     */
    public function loadData()
    {
        return [
            'view' => $this->view,
            'urls' => $this->urlsCount,
            'traffic' => $this->traffic,
            'crawlers' => $this->crawlers,
            'statusCodes' => $this->statusCodes,
        ];
    }

    /**
     * @param string $line
     * @throws \Exception
     */
    public function executeParams(string $line)
    {
        $lineChunks = explode(' ', $line);

        $httpStatus = $this->executeStatusCode($lineChunks);
        $traffic = $this->executeTraffic($lineChunks);
        $url = $this->executeUrl($lineChunks);
        $crawler = $this->executeCrawler($lineChunks);

        $this->incrementCrawlers($crawler);
        $this->incrementHttpStatusCode($httpStatus);
        $this->incrementUrl($url);
        $this->incrementTraffic($traffic);
        $this->incrementView();
    }
    
    public function urlKey($urlKey)
    {
        $this->urlKey = $urlKey;
    }
    
    public function httpStatusKey($httpStatusKey)
    {
        $this->httpStatusKey = $httpStatusKey;
    }
    
    public function trafficKey($trafficKey)
    {
        $this->trafficKey = $trafficKey;
    }

    private function incrementView()
    {
        $this->view++;
    }

    /**
     * @param int $traffic
     * @throws \Exception
     */
    private function incrementTraffic(int $traffic)
    {
        if($traffic === null) {
            throw new \Exception('Traffic size equals null');
        }

        $this->traffic += $traffic;
    }

    /**
     * @param string $url
     * @throws \Exception
     */
    private function incrementUrl($url)
    {
        if($url === null) {
            throw new \Exception('Url equals null');
        }

        if(!array_key_exists($url, $this->urls)) {
            $this->urls[$url] = 1;
            $this->urlsCount++;
        }
    }

    /**
     * @param string $statusCode
     * @throws \Exception
     */
    private function incrementHttpStatusCode($statusCode)
    {
        if($statusCode === null) {
            throw new \Exception('Status code euqals null');
        }

        if(array_key_exists($statusCode, $this->statusCodes)) {
            $this->statusCodes[$statusCode]++;
        } else {
            $this->statusCodes[$statusCode] = 1;
        }
    }

    /**
     * @param $crawler
     * @throws \Exception
     */
    private function incrementCrawlers($crawler)
    {
        if($crawler) {
            if (array_key_exists($crawler, $this->crawlers)) {
                $this->crawlers[$crawler]++;
            } else {
                $this->crawlers[$crawler] = 1;
            }
        }
    }

    /**
     * @param array $lineChunks
     * @return bool|string
     */
    private function executeCrawler(array $lineChunks)
    {
        foreach ($lineChunks as $lineChunk) {
            $botPos = stripos($lineChunk, 'bot');

            if(!$botPos) {
                continue;
            }

            return substr($lineChunk, 0, $botPos);
        }
    }

    /**
     * @param array $lineChunks
     * @return mixed
     */
    private function executeStatusCode(array $lineChunks)
    {
        if((array_key_exists($this->httpStatusKey, $lineChunks)
            && !empty($lineChunks[$this->httpStatusKey])) && isset($lineChunks[$this->httpStatusKey])) {
            return $lineChunks[$this->httpStatusKey];
        }
    }

    /**
     * @param array $lineChunks
     * @return mixed
     */
    private function executeTraffic(array $lineChunks)
    {
        if((array_key_exists($this->trafficKey, $lineChunks)
            && !empty($lineChunks[$this->trafficKey])) && isset($lineChunks[$this->trafficKey])) {
            return $lineChunks[$this->trafficKey];
        }
    }

    /**
     * @param array $lineChunks
     * @return mixed
     */
    private function executeUrl(array $lineChunks)
    {
        if((array_key_exists($this->urlKey, $lineChunks)
            && !empty($lineChunks[$this->urlKey])) && isset($lineChunks[$this->urlKey])) {
            return $lineChunks[$this->urlKey];
        }
    }
}