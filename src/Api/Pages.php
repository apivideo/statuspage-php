<?php

namespace ApiVideo\StatusPage\Api;

use ApiVideo\StatusPage\Model\Status;
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

    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * @param string $pageId
     * @return Status
     * @throws \Exception
     */
    public function getOverallStatus($pageId)
    {
        $response = $this->browser->get(sprintf('/pages/%s/status.json', $pageId));

        $this->ensureSuccessfulResponse($response, 'Retrieve metric status', 'Page '.$pageId);

        $body = json_decode($response->getContent(), true);

        return Status::fromArray($body['status']);
    }

    public function create(array $data)
    {

    }

    public function update($id, array $data)
    {

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
