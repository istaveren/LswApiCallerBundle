<?php
namespace Lsw\ApiCallerBundle\Call;

/**
 * cURL based API call with request data send as GET parameters
 *
 * @author J. Cary Howell <cary.howell@gmail.com>
 */
class HttpGetXML extends CurlCall implements ApiCallInterface
{
    /**
     * {@inheritdoc}
     */
    public function generateRequestData()
    {
        $this->requestData = http_build_query($this->requestObject);
    }

    /**
     * {@inheritdoc}
     */
    public function parseResponseData()
    {
        // Only parse on success
        if ($this->getStatusCode() >= 200 && $this->getStatusCode() < 400) {
            $xml = simplexml_load_string($this->responseData, 'SimpleXMLElement', LIBXML_NOCDATA);
            if ($this->asAssociativeArray) {
                $json = json_encode($xml);
                $this->responseObject = json_decode( $json, TRUE );
            } else {
                $this->responseObject = $xml;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function makeRequest($curl, $options)
    {
        $curl->setopt(CURLOPT_URL, $this->url.'?'.$this->requestData);
        $curl->setopt(CURLOPT_HTTPGET, TRUE);
        $curl->setoptArray($options);
        $this->curlExec($curl);
    }

}
