<?php

class JobeetCategory extends BaseJobeetCategory {
	public function __toString() {
		return $this->getName();
	}
	public function getActiveJobs($max = 10) {
		//$criteria = new Criteria();
		$criteria = $this->getActiveJobsCriteria();
		//$criteria->add(JobeetJobPeer::CATEGORY_ID, $this->getId());
		$criteria->setLimit($max);
		return JobeetJobPeer::getActiveJobs($criteria);
		return JobeetJobPeer::doSelect($criteria);
		

	}
	public function getSlug() {
		return Jobeet::slugify($this->getName());
	}

	public function countActiveJobs() {
		//$criteria = new Criteria();
		//$criteria->add(JobeetJobPeer::CATEGORY_ID, $this->getId());

		//return JobeetJobPeer::countActiveJobs($criteria);
		$criteria = $this->getActiveJobsCriteria();
		return JobeetJobPeer::doCount($criteria);

	}
	public function setName($name) {
		parent::setName($name);
		$this->setSlug(Jobeet::slugify($name));
	}
	public function getActiveJobsCriteria() {
		$criteria = new Criteria();
		$criteria->add(JobeetJobPeer::CATEGORY_ID, $this->getId());
		return JobeetJobPeer::addActiveJobsCriteria($criteria);
	}
	public function getLatestPost() {
		$jobs = $this->getActiveJobs(1);

		return $jobs[0];
	}

}
