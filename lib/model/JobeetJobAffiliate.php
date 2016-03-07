<?php

class JobeetJobAffiliate extends BaseJobeetJobAffiliate
{
	public function __toString()
	{
		return $this->getUrl();
	}
}
