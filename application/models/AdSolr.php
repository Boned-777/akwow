<?php
class Application_Model_AdSolr {
    protected $_client;

    protected $_query;

    function __construct() {
        $this->initClient();
    }

    public function initClient() {
        $config = array(
            'endpoint' => array(
                'localhost' => array(
                    'host' => '127.0.0.1',
                    'port' => 8983,
                    'path' => '/solr/',
                )
            )
        );
        $this->_client = new Solarium\Client($config);
    }

    public function updateAllSolrData() {
        $this->clearAllSolrData();

        $ad = new Application_Model_Ad();
        $list = $ad->getRegularList();
        $update = $this->getClient()->createUpdate();

        foreach($list as $item) {
            $update->addDocument($item->createSolrDocument());
        }

        $update->addCommit();
        $result = $this->getClient()->update($update);

        return $result->getStatus();


    }

    public function getAds($params=null) {
        $query = $this->getQuery();
        $query->setRows(10000000);
        $query->setFields(array(
            "id",
            "name",
            "status",
            //"description",
            "banner",
            "category",
            "brand",
            "brand_name",
            "product",
            "product_name",
            "geo"
        ));
        $this->applyParams($params);

        $resultSet = $this->getClient()->execute($query);

        return $resultSet->getResponse()->getBody();
    }

    public function getAdsCount($temp, $params=null) {
        $params["geo"] = $temp;

        $query = $this->getQuery();
        $query->setFields(array(
            "id"
        ));
        $this->applyParams($params);

        $resultSet = $this->getClient()->execute($query);

        return $resultSet->getNumFound();
    }

    public function getFacets($facetField, $params=null) {
        $query = $this->getQuery();
        $facetSet = $query->getFacetSet();
        $facetSet->createFacetField($facetField)->setField($facetField);
        if ($facetField == "geo") {
            if (!isset($params["geo"])) {
                $params["geo"] = "1";
            }
            $geoFacetField = "geoLvl_" . (((int)substr_count($params["geo"], "-")) + 1);
            $facetSet->createFacetField($geoFacetField)->setField($geoFacetField);
        }

        $this->applyParams($params);

        $resultSet = $this->getClient()->execute($query);

        $facetResult = $resultSet->getFacetSet()->getFacet($facetField);
        $result = $facetResult->getValues();

        if ($facetField == "geo") {
            $geoParts = explode("-", $params["geo"]);
            $geoPartsCount = count($geoParts);

            $totalOriginGeo = 0;
            $tmp = "";
            for ($i=0; $i<$geoPartsCount; $i++) {
                $tmp .= $geoParts[$i];
                $totalOriginGeo += $result[$tmp];
                $tmp .= "-";
            }

            $result = $resultSet->getFacetSet()->getFacet($geoFacetField)->getValues();
            $result["origin"] = $totalOriginGeo;
        }
        return $result;
    }

    public function getGeoFacets($facetField, $params=null) {
        $geo = $params["geo"];
        if (!isset($params["geo"])) {
            $geo = 1;
        }

        $query = $this->getQuery();
        $facetSet = $query->getFacetSet();
        $facetSet->createFacetField($facetField)->setField($facetField);
    }

    public function applyParams($params=null) {
        foreach((array)$params as $key=>$value) {
            switch ($key) {
                case 'geo' :
                    $geoArr = explode("-", $value);
                    $tmp = "";
                    foreach ($geoArr as $val) {
                        $tmp .= $val;
                        $geoList[] = $tmp;
                        $tmp .= "-";
                    }
                    $geoList[] = $tmp . "*";
                    $value = "(" . implode(" OR ", $geoList) . ")";
                    break;

            }
            $this->getQuery()->createFilterQuery($key)->setQuery($key.':'.$value);
        }
    }

    public function getClient() {
        return $this->_client;
    }

    public function getQuery() {
        if (empty($this->_query)) {
            $this->_query = $this->getClient()->createQuery(Solarium\Client::QUERY_SELECT);
        }
        return $this->_query;
    }

    protected function clearAllSolrData() {
        $update = $this->getClient()->createUpdate();
        $update->addDeleteQuery('id:*');
        $update->addCommit();
        $result = $this->getClient()->update($update);


        return $result->getStatus();
    }
}