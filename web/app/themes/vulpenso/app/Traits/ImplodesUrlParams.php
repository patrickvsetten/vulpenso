<?php

namespace App\Traits;

trait ImplodesUrlParams
{
    public function mountImplodesUrlParams()
    {
        foreach ($this->urlParamsToImplode() as $param) {
            if (!empty($this->{'queryString' . ucfirst($param)})) {
                $this->{$param} = explode(',', $this->{'queryString' . ucfirst($param)});
            }
        }
    }

    public function dehydrateImplodesUrlParams()
    {
        foreach ($this->urlParamsToImplode() as $param) {
            $this->{'queryString' . ucfirst($param)} = implode(',', $this->{$param});
        }
    }

    protected function urlParamsToImplode()
    {
        return [];
    }
}
