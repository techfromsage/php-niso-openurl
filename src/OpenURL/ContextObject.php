<?php
namespace OpenURL;

class ContextObject
{
    /**
     * @var string
     */
    protected $version = "Z39.88-2004";
    /**
     * @var string
     */
    protected $timestamp;
    /**
     * @var string
     */
    protected $identifier;
    /**
     * @var string
     */
    protected $encoding;
    /**
     * @var \OpenURL\Entity
     */
    protected $referent;
    /**
     * @var \OpenURL\Entity
     */
    protected $referrer;
    /**
     * @var \OpenURL\Entity
     */
    protected $referringEntity;
    /**
     * @var \OpenURL\Entity
     */
    protected $requester;
    /**
     * @var \OpenURL\Entity[]
     */
    protected $serviceType = array();
    /**
     * @var \OpenURL\Entity[]
     */
    protected $resolver = array();
    /**
     * @var array
     */
    protected $entityMap = array("rft"=>"referent", "rfr"=>"referrer","rfe"=>"referringEntity","req"=>"requester","svc"=>"serviceType","res"=>"resolver");


    /**
     * loadKev
     * @param string $kev
     * @return ContextObject
     */

    public static function loadKev($kev)
    {
        return(self::loadArray(self::parseKev($kev)));
    }

    /**
     * @param string $kev
     * @return array
     */
    public static function parseKev($kev)
    {
        $ary = array();
        $keyVals = explode("&", $kev);
        foreach($keyVals as $keyVal)
        {
            if (strpos($keyVal, '=') === FALSE)
            {
                continue;
            }
            list($key,$val) = explode("=", $keyVal);
            if(isset($ary[$key]))
            {
                if(!is_array($ary[$key]))
                {
                    $ary[$key] = array($ary[$key]);
                }
                $ary[$key][] = urldecode($val);
            } else {
                $ary[$key] = urldecode($val);
            }
        }
        return($ary);
    }

    /**
     * @param array $ary
     * @return ContextObject
     */
    public static function loadArray(array $ary)
    {
        $ctx = new self();

        foreach ($ary as $key => $value)
        {
            switch ($key) {
                case 'ctx_ver':
                    $ctx->setVersion($value);
                    break;
                case 'ctx_enc':
                    $ctx->setEncoding($value);
                    break;
                case 'ctx_id':
                    $ctx->setIdentifier($value);
                    break;
                case 'ctx_tim':
                    $ctx->setTimestamp($value);
                    break;
                case preg_match("/^url_/", $key) != 0:
                    break;
                case preg_match("/^(rft|rfr|rfe|req|svc|res)_/", $key) != 0:
                    $ctx->setEntityMetadata($key, $value);
                    break;
                case preg_match('/^(rft|rfr|rfe|req|svc|res)\./', $key) != 0:
                    list($entity, $field) = explode(".", $key, 2);
                    $ctx->setEntityValue($entity, $field, $value);
                    break;
                default:
                    // Assume this is a 0.1 or hybrid style OpenURL
                    $ctx->setEntityValue('rft', $key, $value);
                    break;
            }
        }
        return($ctx);

    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return($this->version);
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getEncoding()
    {
        return($this->encoding);
    }

    /**
     * @param string $encoding
     */
    public function setEncoding($encoding)
    {
        $this->encoding = $encoding;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return($this->identifier);
    }

    /**
     * @param string $identifier
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return($this->timestamp);
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * Set entity metadata (_id, _val_fmt, _dat, etc.)
     *
     * @param string $key
     * @param mixed $value
     */
    protected function setEntityMetadata($key, $value)
    {
        list($entity, $property) = explode("_", $key, 2);
        $entityName = $this->entityMap[$entity];

        if($entity == "svc" || $entity == "res")
        {
            if(empty($this->$entityName))
            {
                $method = "add" . ucfirst($entityName);
                $this->$method(new Entity());
            }
            $ent = $this->$entityName;
            /** @var \OpenURL\Entity $ent */
            $ent = $ent[0];
        } else {
            if(!($this->$entityName instanceof \OpenURL\Entity))
            {
                $method = "set" . ucfirst($entityName);
                $this->$method(new Entity());
            }
            /** @var \OpenURL\Entity $ent */
            $ent = $this->$entityName;
        }
        switch ($property) {
            case 'id':
                $ent->setIdentifier($value);
                break;
            case 'val_fmt':
                $ent->setValFormat($value);
                break;
            case 'ref_fmt':
                $ent->setRefFormat($value);
                break;
            case 'ref':
                $ent->setRefLocation($value);
                break;
            case 'dat':
                $ent->setPrivateData($value);
                break;
            default:
                break;
        }
    }

    /**
     * Set the value on an entity
     * @param string $entity
     * @param string $key
     * @param mixed $value
     */
    protected function setEntityValue($entity, $key, $value)
    {
        if(empty($value))
        {
            return;
        }
        $entityName = $this->entityMap[$entity];
        if($entity == "svc" || $entity == "res")
        {
            if(empty($this->$entityName))
            {
                $method = "add" . ucfirst($entityName);
                $this->$method(new Entity());
            }
            $ent = $this->$entityName;
            /** @var \OpenURL\Entity $ent */
            $ent = $ent[0];
        } else {
            if(!($this->$entityName instanceof \OpenURL\Entity))
            {
                $method = "set" . ucfirst($entityName);
                $this->$method(new Entity());
            }
            /** @var \OpenURL\Entity $ent */
            $ent = $this->$entityName;
        }
        if(!is_array($value))
        {
            $value = array($value);
        }
        foreach($value as $val)
        {
            $ent->setValue($key, $val);
        }
    }

    /**
     * @return \OpenURL\Entity
     */
    public function getReferent()
    {
        return($this->referent);
    }

    /**
     * @param \OpenURL\Entity $referent
     */
    public function setReferent(\OpenURL\Entity $referent)
    {
        $this->referent = $referent;
    }

    /**
     * @return \OpenURL\Entity
     */
    public function getReferrer()
    {
        return($this->referrer);
    }

    /**
     * @param Entity $referrer
     */
    public function setReferrer(\OpenURL\Entity $referrer)
    {
        $this->referrer = $referrer;
    }

    /**
     * @return \OpenURL\Entity
     */
    public function getReferringEntity()
    {
        return $this->referringEntity;
    }

    /**
     * @param Entity $referringEntity
     */
    public function setReferringEntity(\OpenURL\Entity $referringEntity)
    {
        $this->referringEntity = $referringEntity;
    }

    /**
     * @return \OpenURL\Entity
     */
    public function getRequester()
    {
        return $this->requester;
    }

    /**
     * @param Entity $requester
     */
    public function setRequester(\OpenURL\Entity $requester)
    {
        $this->requester = $requester;
    }

    /**
     * @param Entity $serviceType
     */
    public function addServiceType(\OpenURL\Entity $serviceType)
    {
        $this->serviceType[] = $serviceType;
    }

    /**
     * @return array|Entity[]
     */
    public function getServiceType()
    {
        return($this->serviceType);
    }

    /**
     * @param Entity $resolver
     */
    public function addResolver(\OpenURL\Entity $resolver)
    {
        $this->resolver[] = $resolver;
    }

    /**
     * @return array|Entity[]
     */
    public function getResolver()
    {
        return($this->resolver);
    }

    /**
     * Returns a KEV (key-encoded values) string for the ContextObject
     * @return string
     */
    public function toKev()
    {
        $kevs = array('url_ver=Z39.88-2004', 'url_ctx_fmt=info%3Aofi%2Ffmt%3Akev%3Amtx%3Actx');
        if($id = $this->getIdentifier())
        {
            $kevs[] = 'ctx_id='.urlencode($id);
        }
        if($ver = $this->getVersion())
        {
            $kevs[] = 'ctx_ver='. urlencode($ver);
        }
        if($enc = $this->getEncoding())
        {
            $kevs[] = 'ctx_enc=' . urlencode($enc);
        }
        if($tim = $this->getTimestamp())
        {
            $kevs[] = 'ctx_tim=' . urlencode($tim);
        }
        $kev = implode("&", $kevs);
        if($rft = $this->getReferent())
        {
            $kev .= '&' . $rft->toKev('rft');
        }

        if($rfe = $this->getReferringEntity())
        {
            $kev .= '&' . $rfe->toKev('rfe');
        }
        if($rfr = $this->getReferrer())
        {
            $kev .= '&' . $rfr->toKev('rfr');
        }
        if($req = $this->getRequester())
        {
            $kev .= '&' . $req->toKev('req');
        }
        /** @var \OpenURL\Entity[] $resolvers */
        $resolvers = $this->getResolver();
        if(!empty($resolvers))
        {
            $kev .= '&' . $resolvers[0]->toKev('res');
        }

        /** @var \OpenURL\Entity[] $svc */
        $svc = $this->getServiceType();
        if(!empty($svc))
        {
            $kev .= '&' . $svc[0]->toKev('svc');
        }
        return($kev);
    }
}