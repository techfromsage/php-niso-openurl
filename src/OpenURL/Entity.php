<?php
namespace OpenURL;

class Entity
{
    /**
     * @var string
     */
    protected $identifier;
    /**
     * @var string
     */
    protected $valFormat;
    /**
     * @var string
     */
    protected $refFormat;
    /**
     * @var string
     */
    protected $refLocation;
    /**
     * @var mixed
     */
    protected $privateData;
    /**
     * @var array
     */
    protected $values = array();

    /**
     * @param string $id
     */
    public function setIdentifier($id)
    {
        $this->identifier = $id;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return($this->identifier);
    }

    /**
     * @param string $fmt
     */
    public function setValFormat($fmt)
    {
        $this->valFormat = $fmt;
    }

    /**
     * @return string
     */
    public function getValFormat()
    {
        return($this->valFormat);
    }

    /**
     * @param string $fmt
     */
    public function setRefFormat($fmt)
    {
        $this->refFormat = $fmt;
    }

    /**
     * @return string
     */
    public function getRefFormat()
    {
        return($this->refFormat);
    }

    /**
     * @param string $loc
     */
    public function setRefLocation($loc)
    {
        $this->refLocation = $loc;
    }

    /**
     * @return string
     */
    public function getRefLocation()
    {
        return($this->refLocation);
    }

    /**
     * @param mixed $data
     */
    public function setPrivateData($data)
    {
        $this->privateData = $data;
    }

    /**
     * @return mixed
     */
    public function getPrivateData()
    {
        return($this->privateData);
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return($this->values);
    }

    /**
     * Adds a key/value to the entity.  If the key exists, will set the value to an array.  Additive, unless $replace argument set to true
     * @param string $key
     * @param mixed $value
     * @param bool $replace
     */
    public function setValue($key, $value, $replace=false)
    {
        if(!isset($this->values[$key]) || $replace)
        {
            $this->values[$key] = $value;
        }
        else
        {
            if(!is_array($this->values[$key]))
            {
                $this->values[$key] = array($this->values[$key]);
            }
            $this->values[$key][] = $value;
        }
    }

    /**
     * Returns all existing keys
     *
     * @return array
     */
    public function getValueKeys()
    {
        return(array_keys($this->values));
    }

    /**
     * Returns the value for the given key
     *
     * @param $key
     * @return mixed
     */
    public function getValue($key)
    {
        return((isset($this->values[$key]) ? $this->values[$key] : null));
    }

    /**
     * Generates a KEV (key-encoded values) for the entity.  The abbreviation (e.g. 'rft', 'req', etc.) must be sent.
     * @param string $abbr
     * @return string
     */
    public function toKev($abbr)
    {
        $kevs = array();
        if($ids = $this->getIdentifier())
        {
            if(!is_array($ids))
            {
                $ids = array($ids);
            }
            foreach($ids as $id)
            {
                $kevs[] = $abbr . '_id=' . urlencode($id);
            }
        }
        if($val_fmt = $this->getValFormat())
        {
            $kevs[] = $abbr . '_val_fmt=' . urlencode($val_fmt);
        }
        if($ref_fmt = $this->getRefFormat())
        {
            $kevs[] = $abbr . '_ref_fmt=' . urlencode($ref_fmt);
        }
        if($ref = $this->getRefLocation())
        {
            $kevs[] = $abbr . '_ref=' . urlencode($ref);
        }
        if($dat = $this->getPrivateData())
        {
            $kevs[] = $abbr . '_dat=' . urlencode($dat);
        }
        foreach($this->getValues() as $key=>$value)
        {
            if(!is_array($value))
            {
                $value = array($value);
            }
            foreach($value as $val)
            {
                $kevs[] = $abbr . '.' . $key . '=' . urlencode($val);
            }
        }
        return(implode("&", $kevs));
    }
}
?>