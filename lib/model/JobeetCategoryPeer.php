<?php

class JobeetCategoryPeer extends BaseJobeetCategoryPeer {
	static public function getWithJobs() 
	{
		$criteria = new Criteria();
		$criteria->addJoin(self::ID, JobeetJobPeer::CATEGORY_ID);
		$criteria->add(JobeetJobPeer::EXPIRES_AT, time(), Criteria::GREATER_THAN);
		$criteria->setDistinct();
		$criteria->add(JobeetJobPeer::IS_ACTIVATED, true);
		return self::doSelect($criteria);
	}
	
	static public function getActiveJobs(Criteria $criteria = null)
	 {
		if (is_null($criteria)) 
		{
			$criteria = new Criteria();
		}

		$criteria->add(JobeetJobPeer::EXPIRES_AT, time(), Criteria::GREATER_THAN);
		$criteria->addDescendingOrderByColumn(JobeetJobPeer::EXPIRES_AT);

		return self::doSelect(self::addActiveJobsCriteria($criteria));
	}
	
	static public function countActiveJobs(Criteria $criteria = null) 
	{
		return self::doCount(self::addActiveJobsCriteria($criteria));
	}
	
	static public function addActiveJobsCriteria(Criteria $criteria = null) 
	{
		if (is_null($criteria)) 
		{
			$criteria = new Criteria();
		}

		$criteria->add(JobeetJobPeer::EXPIRES_AT, time(), Criteria::GREATER_THAN);
		$criteria->addDescendingOrderByColumn(JobeetJobPeer::CREATED_AT);

		return $criteria;
	}
	
	static public function doSelectActive(Criteria $criteria) 
	{
		return self::doSelectOne(self::addActiveJobsCriteria($criteria));
	}
	
	static public function getForSlug($slug)
	 {
		$criteria = new Criteria();
		$criteria->add(self::SLUG, $slug);

		return self::doSelectOne($criteria);
	}

}
