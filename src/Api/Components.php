<?php

namespace ApiVideo\StatusPage\Api;

use ApiVideo\StatusPage\Model\Component;
use ApiVideo\StatusPage\Traits\Marshal;
use ApiVideo\StatusPage\Traits\Throwing;
use ApiVideo\StatusPage\Traits\LastError;
use ApiVideo\StatusPage\Exception\NotFound;
use ApiVideo\StatusPage\Exception\Forbidden;
use ApiVideo\StatusPage\Exception\Unauthorized;
use Buzz\Browser;
use IteratorAggregate, Exception;

final class Components implements IteratorAggregate
{
    use Marshal, Throwing, LastError;

    /** @var Browser */
    private $browser;

    /** @var string */
    private $pageId;

    /**
     * @param Browser $browser
     * @param string $pageId
     */
    public function __construct(Browser $browser, $pageId)
    {
        $this->browser = $browser;
        $this->pageId = $pageId;
    }

    /**
     * @param array $data
     * @return Component
     * @throws Forbidden
     * @throws NotFound
     * @throws Unauthorized
     */
    public function create(array $data)
    {
        $response = $this->browser->submit(
            sprintf('/pages/%s/components.json', $this->pageId),
            [
                'data' => $data
            ]
        );

        $this->ensureSuccessfulResponse($response, 'Create component');

        return $this->unmarshal($response);
    }

    /**
     * @param $componentId
     * @param array $data
     * @return Component
     * @throws Forbidden
     * @throws NotFound
     * @throws Unauthorized
     * @throws Exception
     */
    public function update($componentId, array $data)
    {
        $response = $this->browser->patch(
            sprintf('/pages/%s/components/%s.json', $this->pageId, $componentId),
            [],
            $data
        );

        $this->ensureSuccessfulResponse($response, 'Update component', 'Component '.$componentId);

        return $this->unmarshal($response);
    }

    /**
     * @param string $componentId
     * @param string $status
     * @return Component
     * @throws Forbidden
     * @throws NotFound
     * @throws Unauthorized
     * @throws Exception
     */
    public function setStatus($componentId, $status)
    {
        return $this->update($componentId, ['status' => $status]);
    }

    /**
     * @param string $componentId
     * @return Component
     * @throws Forbidden
     * @throws NotFound
     * @throws Unauthorized
     * @throws Exception
     */
    public function get($componentId)
    {
        $response = $this->browser->get(sprintf('/pages/%s/components/%s.json', $this->pageId, $componentId));

        $this->ensureSuccessfulResponse($response, 'Update component', 'Component '.$componentId);

        return $this->unmarshal($response);
    }

    /**
     * @param string $componentId
     * @throws Forbidden
     * @throws NotFound
     * @throws Unauthorized
     * @return $this
     */
    public function delete($componentId)
    {
        $response = $this->browser->delete(sprintf('/pages/%s/components/%s.json', $this->pageId, $componentId));

        $this->ensureSuccessfulResponse($response, 'Delete component', 'Component '.$componentId);

        return $this;
    }

    /**
     * @param int $page
     * @param int $perPage
     * @return Component[]
     */
    public function all($page = 1, $perPage = 100)
    {
        return $this->unmarshalAll($this->browser->get('/pages/'.$this->pageId.'/components?page='.$page.'&per_page='.$perPage));
    }

    /**
     * @param array $data
     * @return Component
     * @throws \Exception
     */
    protected function cast(array $data)
    {
        return Component::fromArray($data);
    }

    // IteratorAggregate implementation

    /** @return Component[] */
    public function getIterator()
    {
        $page = 1;
        $perPage = 100;
        $components = [];
        do {
            $current_page = $this->all($page++);
            $found = count($current_page);
            $components = array_merge($components, $current_page);
        } while ($found === $perPage);

        return $components;
    }
}
