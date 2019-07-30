<?php

namespace ApiVideo\StatusPage\Api;

use ApiVideo\StatusPage\Model\GlobalStatus;
use ApiVideo\StatusPage\Traits\Marshal;
use ApiVideo\StatusPage\Traits\Throwing;
use ApiVideo\StatusPage\Traits\LastError;
use ApiVideo\StatusPage\Model\Page;
use IteratorAggregate;
use Buzz\Browser;

final class Pages implements IteratorAggregate
{
    use Marshal, LastError, Throwing;

    /** @var Browser */
    private $browser;

    /** @var string */
    private $pageId;

    /**
     * @param Browser $browser
     * @param string|null $pageId
     */
    public function __construct(Browser $browser, $pageId = null)
    {
        $this->browser = $browser;
        $this->pageId = $pageId;
    }

    /**
     * @param string $pageId
     * @return GlobalStatus
     * @throws \Exception
     */
    public function getOverallStatus($pageId = null)
    {
        $notNullPageId = $this->coalescePageId($pageId);
        $uri = sprintf('https://%s.statuspage.io/api/v2/status.json', $notNullPageId);
        $response = $this->browser->get($uri);
        $this->ensureSuccessfulResponse($response, 'Retrieve page status', 'Page '.$notNullPageId);
        $body = json_decode($response->getContent(), true);

        return GlobalStatus::fromArray($body['status']);
    }

    public function create(array $data)
    {

    }

    /**
     * @param array $data
     * @param string|null $pageId
     * @throws \Exception
     * @return Page
     */
    public function update(array $data, $pageId = null)
    {
        $notNullPageId = $this->coalescePageId($pageId);

        $response = $this->browser->patch(sprintf('/pages/%s.json', $notNullPageId), [], $data);

        $this->ensureSuccessfulResponse($response, 'Retrieve metric status', 'Page '.$notNullPageId);

        return $this->unmarshal($response);
    }

    /**
     * @param string $id
     * @return Page
     */
    public function get($id)
    {
        return $this->unmarshal($this->browser->get('/pages/'.$id));
    }

    public function delete($id)
    {

    }

    /**
     * @param int $page
     * @param int $perPage
     * @return Page[]
     */
    public function all($page = 1, $perPage = 100)
    {
        return $this->unmarshalAll($this->browser->get('/pages?page='.$page.'&per_page='.$perPage));
    }

    /**
     * @param string|null $pageId
     * @return string
     */
    private function coalescePageId($pageId)
    {
        if ($pageId) {
            return $pageId;
        }

        if (!$this->pageId) {

        }

        return $this->pageId;
    }

    protected function cast(array $data)
    {
        return Page::fromArray($data);
    }

    // IteratorAggregate implementation

    /** @return Page[] */
    public function getIterator()
    {
        $page = 1;
        $pageSize = 100;


    }
}
