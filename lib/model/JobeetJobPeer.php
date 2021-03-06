<?php

class JobeetJobPeer extends BaseJobeetJobPeer {
	static public function getActiveJobs(Criteria $criteria = null) {
		//if (is_null($criteria))
		//{
		//  $criteria = new Criteria();
		//}
		//$criteria->add(self::EXPIRES_AT, time(), Criteria::GREATER_THAN);
		//$criteria->addDescendingOrderByColumn(self::EXPIRES_AT);
		return self::doSelect(self::addActiveJobsCriteria($criteria));
	}

	static public function countActiveJobs(Criteria $criteria = null) {
		return self::doCount(self::addActiveJobsCriteria($criteria));
	}

	static public function doSelectActive(Criteria $criteria) {
		//$criteria->add(JobeetJobPeer::EXPIRES_AT, time(), Criteria::GREATER_THAN);

		return self::doSelectOne(self::addActiveJobsCriteria($criteria));
		
	}
	
	static public $types = array('full-time' => 'Full time',
			'part-time' => 'Part time', 'freelance' => 'Freelance',);
	
	static public function addActiveJobsCriteria(Criteria $criteria = null) {
		if (is_null($criteria)) 
		{
			$criteria = new Criteria();
		}

		$criteria->add(self::EXPIRES_AT, time(), Criteria::GREATER_THAN);
		$criteria->addDescendingOrderByColumn(self::CREATED_AT);
        $criteria->add(self::IS_ACTIVATED, true);
		return $criteria;

		//$criteria->add(self::IS_ACTIVATED, true);

		//return $criteria;
	}
	
	static public function cleanup($days) {
		$criteria = new Criteria();
		$criteria->add(self::IS_ACTIVATED, false);
		$criteria->add(self::CREATED_AT, time() - 86400 * $days,Criteria::LESS_THAN);

		return self::doDelete($criteria);
	}
	
	static public function getLatestPost() {
		$criteria = new Criteria();
		self::addActiveJobsCriteria($criteria);

		return JobeetJobPeer::doSelectOne($criteria);
	}
	
	static public function getForToken(array $parameters) {
		$affiliate = JobeetAffiliatePeer::getByToken($parameters['token']);
		if (!$affiliate || !$affiliate->getIsActive()) 
		{
			throw new sfError404Exception(sprintf('Affiliate with token "%s" does not exist or is not activated.',$parameters['token']));
		}

		return $affiliate->getActiveJobs();
	}
	static public function getToken()
	{
	   
	}
	

}
