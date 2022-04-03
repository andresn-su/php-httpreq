<?php

/**
 * Class HttpReq
 */
class HttpReq
{
    /** @var array */
    private $options;

    /** @var CurlHandle */
    private $ch;

    /** @var array|bool */
    private $callback;

    /**
     * Constructor
     *
     * @param string $path
     * @return void
     */
    public function __construct(string $path = null)
    {
        $this->ch = curl_init();

        if ($path) {
            $this->options[CURLOPT_URL] = $path;
        }

        $this->ch = curl_init();
    }

    /**
     * Changes path
     *
     * @param string $path
     * @return HttpReq
     */
    public function path(string $path): HttpReq
    {
        $this->options[CURLOPT_URL] = $path;
        return $this;
    }

    /**
     * Params
     *
     * @param array $params
     * @return HttpReq
     */
    public function params(array $params): HttpReq
    {
        $this->options[CURLOPT_POSTFIELDS] = http_build_query($params);
        return $this;
    }

    /**
     * Return transfer
     *
     * @param boolean $value
     * @return HttpReq
     */
    public function returnTransfer(bool $value): HttpReq
    {
        $this->options[CURLOPT_RETURNTRANSFER] = $value;
        return $this;
    }

    /**
     * Headers
     *
     * @param array $headers
     * @return HttpReq
     */
    public function headers(array $headers): HttpReq
    {
        $this->options[CURLOPT_HTTPHEADER] = $headers;
        return $this;
    }

    /**
     * Post
     *
     * @return array|bool
     */
    public function post(): array|bool
    {
        return $this->exec("POST");
    }

    /**
     * Put
     *
     * @return array|bool
     */
    public function put(): array|bool
    {
        return $this->exec("PUT");
    }

    /**
     * Get
     *
     * @return array|bool
     */
    public function get(): array|bool
    {
        return $this->exec("GET");
    } 

    /**
     * Delete
     *
     * @return array|bool
     */
    public function delete(): array|bool
    {
        return $this->exec("DELETE");
    }

    /**
     * Does the Http request
     */
    private function exec(string $method): array|bool
    {
        $this->options[CURLOPT_CUSTOMREQUEST] = $method;
        curl_setopt_array($this->ch, $this->options);

        $this->callback = json_decode(curl_exec($this->ch));
        $this->error = curl_error($this->ch);
        curl_close($this->ch);

        return $this->callback;
    }
}
